<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media; // Import the model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         $this->middleware('permission:mediaView|mediaAdd|mediaUpdate|mediaDelete', ['only' => ['index','data']]);
         $this->middleware('permission:mediaAdd', ['only' => ['store']]);
         $this->middleware('permission:mediaUpdate', ['only' => ['show', 'update', 'updateOrder']]);
         $this->middleware('permission:mediaDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page with tabs.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'table');
        $items = [];

        if ($activeTab === 'reorder') {
            // Eager load video_id for the reorder tab preview
            $items = Media::orderBy('order', 'asc')->get();
        }

        return view('admin.media.index', compact('activeTab', 'items'));
    }

   /**
    * Provide data for the AJAX Data Table.
    * No change needed here, 'video_id' is returned automatically.
    */
   public function data(Request $request): JsonResponse
    {
        try {
            $query = Media::query();

            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('youtube_link', 'like', '%' . $request->search . '%');
            }

            $sortColumn = $request->input('sort', 'order');
            $sortDirection = $request->input('direction', 'asc');
            // 'video_id' is not sortable, but 'youtube_link' is
            $allowedSorts = ['id', 'title', 'youtube_link', 'order', 'created_at']; 
            
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('order', 'asc');
            }

            $paginated = $query->paginate(10);
            
            // The 'video_id' column is automatically included in $paginated->items()
            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch media data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $youtubeRegex = '%(http(s)?://)?(www\.)?(youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/|youtube\.com/shorts/)([a-zA-Z0-9_-]{11})%';

        $request->validate([
            'title' => 'required|string|max:255',
            'youtube_link' => ['required', 'url', 'regex:' . $youtubeRegex],
        ], [
            'youtube_link.regex' => 'The link must be a valid YouTube URL (e.g., youtube.com/watch?v=... or youtu.be/...).'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->only('title', 'youtube_link');
            
            // *** NEW: Extract and add video_id ***
            $data['video_id'] = $this->extractYouTubeId($request->youtube_link);
            if (!$data['video_id']) {
                throw new Exception("Could not extract YouTube video ID.");
            }

            Media::create($data);
            DB::commit();

            Log::info("Media item created successfully.", ['title' => $request->title]);
            return redirect()->route('media.index')->with('success','Media item created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to create media item: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => "Failed to create item. Please check logs."]);
        }
    }

    /**
     * Display the specified resource (for edit modal).
     * No change needed.
     */
    public function show($id): JsonResponse { /* ... no change ... */ 
        try {
            $item = Media::findOrFail($id);
            return response()->json($item);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent Media ID {$id}");
             return response()->json(['error' => 'Item not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch Media ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve item data.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $youtubeRegex = '%(http(s)?://)?(www\.)?(youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/|youtube\.com/shorts/)([a-zA-Z0-9_-]{11})%';
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'youtube_link' => ['required', 'url', 'regex:' . $youtubeRegex],
        ], [
            'youtube_link.regex' => 'The link must be a valid YouTube URL (e.g., youtube.com/watch?v=... or youtu.be/...).'
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update')
                         ->withInput()
                         ->with('error_modal_id', $id);
        }

        DB::beginTransaction();
        try {
            $item = Media::findOrFail($id);
            $data = $request->only('title', 'youtube_link');

            // *** NEW: Extract and add video_id ***
            $data['video_id'] = $this->extractYouTubeId($request->youtube_link);
            if (!$data['video_id']) {
                throw new Exception("Could not extract YouTube video ID.");
            }

            $item->update($data);
            DB::commit();

            Log::info("Media item updated successfully.", ['id' => $id]);
            return redirect()->route('media.index')->with('success', 'Item updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update Media item ID {$id}: " . $e->getMessage());
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update item.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        }
    }

    /**
     * Handle the AJAX request to update the order.
     * No change needed.
     */
    public function updateOrder(Request $request): JsonResponse { /* ... no change ... */ 
        $request->validate(['itemIds' => 'required|array']);

        DB::beginTransaction();
        try {
            foreach ($request->itemIds as $index => $id) {
                Media::where('id', $id)->update(['order' => $index + 1]);
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Order updated successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update media order: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     * No change needed.
     */
    public function destroy($id): RedirectResponse { /* ... no change ... */ 
        try {
            $item = Media::findOrFail($id);
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            
            DB::statement('SET @count = 0;');
            DB::update('UPDATE media SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');

            return redirect()->route('media.index')->with('success', 'Item deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack();
             Log::warning("Attempted to delete non-existent Media ID {$id}");
             return redirect()->route('media.index')->with('error', 'Item not found.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete Media ID {$id}: " . $e->getMessage());
            return redirect()->route('media.index')->with('error', 'Failed to delete item.');
        }
    }

    /**
     * --- NEW HELPER FUNCTION ---
     * Extracts the YouTube video ID from various URL formats.
     */
    private function extractYouTubeId(string $url): ?string
    {
        $patterns = [
            '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/|youtube\.com/shorts/)([^"&?/ ]{11})%i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1]; // Returns the 11-character ID
            }
        }
        return null;
    }
}