<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Traits\ImageUploadTrait; // Import trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For file operations
use Exception;
use Illuminate\Support\Str;
class EventController extends Controller
{
    use ImageUploadTrait; // Use the trait

    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names
        $this->middleware('permission:eventView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:eventAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:eventUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eventDelete', ['only' => ['destroy']]);
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        // Build the query to check for existence
        $query = Event::where('slug', $slug);

        // If updating, ignore the current item's ID
        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        // Loop until a unique slug is found
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            // Reset query for the next loop
            $query = Event::where('slug', $slug);
            if ($ignoreId !== null) {
                $query->where('id', '!=', $ignoreId);
            }
        }

        return $slug;
    }
    // --- END HELPER FUNCTION ---

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.event.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Event::query();

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%')
                      ->orWhere('time', 'like', '%' . $searchTerm . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'start_date'); // Default sort
            $sortDirection = $request->input('direction', 'desc'); // Newest first
            $allowedSorts = ['id', 'title', 'start_date', 'end_date', 'status', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('start_date', 'desc'); // Fallback sort
            }

            $paginated = $query->paginate(10); // Adjust page size

            // Add image_url using accessor
            $paginated->getCollection()->transform(function ($item) {
                // $item->image_url = $item->image_url; // Accessor handles this automatically if called
                return $item;
            });

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch events: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve events.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $statuses = [1 => 'Published', 0 => 'Draft']; // Define statuses
        return view('admin.event.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:events,title',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'time' => 'nullable|string|max:100', // e.g., "10:00 AM - 4:00 PM"
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Max 2MB example
        ]);

        DB::beginTransaction();
        try {
            $eventData = $validatedData;
            unset($eventData['image']); // Remove image for initial data
// --- Generate and add slug ---
            $eventData['slug'] = $this->generateUniqueSlug($request->title);
            // --- End slug ---
            // Handle Image Upload using Trait
            $tempModel = new Event();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'events', 1200, 750); // Pass dimensions
            if ($imagePath) {
                $eventData['image'] = $imagePath; // Path relative to public/uploads/
            } else {
                 throw new Exception("Event image upload failed or missing.");
            }

            Event::create($eventData);
            DB::commit();

            Log::info('Event created successfully.', ['title' => $request->title]);
            return redirect()->route('event.index')->with('success', 'Event created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create event: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save event. Please check logs.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
         return view('admin.event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        $statuses = [1 => 'Published', 0 => 'Draft'];
        return view('admin.event.edit', compact('event', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255|unique:events,title,' . $event->id,
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d|after_or_equal:start_date',
            'time' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Nullable on update
        ]);

        DB::beginTransaction();
        try {
            $eventData = $validatedData;
            unset($eventData['image']); // Remove image initially
// --- Generate slug ONLY if title changed ---
            if ($request->title !== $event->title) {
                $eventData['slug'] = $this->generateUniqueSlug($request->title, $event->id);
            }
            // --- End slug ---
            // Handle Image Update using Trait
            $imagePath = $this->handleImageUpdate($request, $event, 'image', 'events', 1200, 750); // Pass dimensions
            $eventData['image'] = $imagePath; // Trait returns old or new path

            $event->update($eventData);
            DB::commit();

            Log::info('Event updated successfully.', ['id' => $event->id]);
            return redirect()->route('event.index')->with('success', 'Event updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update event ID ' . $event->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update event. Please check logs.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Delete Image file using Trait's logic (or direct File::delete)
            // Adjust path based on how your trait saves (relative to public/uploads or public/)
            $imageFullPath = public_path('uploads/' . $event->image); // Assuming trait saves relative to public/uploads
            // Or: $imageFullPath = public_path($event->image); // If trait saves relative to public/
             if ($event->image && File::exists($imageFullPath)) {
                 File::delete($imageFullPath);
             }

            $event->delete();
            DB::commit();

            // --- CHANGED ---
            return redirect()->route('event.index')->with('success', 'Event deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete event ID {$event->id}: " . $e->getMessage());
            
            // --- CHANGED ---
            return redirect()->route('event.index')->with('error', 'Failed to delete event.');
        }
    }
}