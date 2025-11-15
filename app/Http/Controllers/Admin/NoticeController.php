<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeCategory; // Import category model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // Use File facade for direct operations
// Remove Storage facade if only used for this controller's files
// use Illuminate\Support\Facades\Storage;
use Exception;

class NoticeController extends Controller
{
    // NOTE: Removed ImageUploadTrait usage for this controller

    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names as needed
        $this->middleware('permission:noticeView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:noticeAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:noticeUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:noticeDelete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.notice.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Notice::with('category'); // Eager load category

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('category', fn($cq) => $cq->where('name', 'like', '%' . $searchTerm . '%'));
                });
            }

            // Sorting
            $sortColumn = $request->input('sort', 'date'); // Default sort: notice date
            $sortDirection = $request->input('direction', 'desc'); // Newest first
            $allowedSorts = ['id', 'title', 'date', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('date', 'desc'); // Fallback sort
            }

            $paginated = $query->paginate(10); // Adjust page size

            // --- IMPORTANT: Modify pdf_file path for asset() ---
            $paginated->getCollection()->transform(function ($item) {
                if ($item->pdf_file) {
                    // Prepend asset path for easy use in JS/Blade
                    $item->pdf_url = asset('public/'.$item->pdf_file);
                } else {
                    $item->pdf_url = null;
                }
                return $item;
            });
            // --- END MODIFICATION ---


            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch notices: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve notices.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = NoticeCategory::orderBy('name')->get();
        return view('admin.notice.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:notice_categories,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'pdf_file' => 'required|file|mimes:pdf|max:5120', // Required PDF, Max 5MB example
        ]);

        DB::beginTransaction();
        try {
            $noticeData = $validatedData;
            unset($noticeData['pdf_file']); // Remove file for initial creation data

            // Handle PDF File Upload Directly to Public Path
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/notices'); // Define public path
                $file->move($destinationPath, $fileName); // Move the file

                // Store the path relative to the public directory
                $noticeData['pdf_file'] = 'uploads/notices/' . $fileName;
            } else {
                 throw new Exception("PDF file upload failed or missing.");
            }

            // Create the Notice
            Notice::create($noticeData);

            DB::commit();
            Log::info('Notice created successfully.', ['title' => $request->title]);
            return redirect()->route('notice.index')->with('success', 'Notice created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create notice: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save notice. Please check logs.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Notice $notice): View
    {
         $notice->load('category');
         return view('admin.notice.show', compact('notice'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notice $notice): View
    {
        $categories = NoticeCategory::orderBy('name')->get();
        return view('admin.notice.edit', compact('notice', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notice $notice): RedirectResponse
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:notice_categories,id',
            'title' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'pdf_file' => 'nullable|file|mimes:pdf|max:5120', // Nullable on update
        ]);

        DB::beginTransaction();
        try {
            $noticeData = $validatedData;
            unset($noticeData['pdf_file']); // Remove file for initial update data

            // Handle PDF File Update Directly to Public Path
            if ($request->hasFile('pdf_file')) {
                // --- Delete old file if it exists ---
                if ($notice->pdf_file && File::exists(public_path($notice->pdf_file))) {
                    File::delete(public_path($notice->pdf_file));
                }

                // --- Store new file ---
                $file = $request->file('pdf_file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('uploads/notices');
                $file->move($destinationPath, $fileName);
                $noticeData['pdf_file'] = 'uploads/notices/' . $fileName; // Store relative path
            }
            // If no new file, $noticeData doesn't include 'pdf_file', so the old one remains

            // Update Notice details
            $notice->update($noticeData);

            DB::commit();
            Log::info('Notice updated successfully.', ['id' => $notice->id]);
            return redirect()->route('notice.index')->with('success', 'Notice updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update notice ID ' . $notice->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update notice. Please check logs.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     * --- UPDATED TO RETURN REDIRECTRESPONSE ---
     */
    public function destroy(Notice $notice): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // --- Delete associated PDF file from public path ---
            if ($notice->pdf_file && File::exists(public_path($notice->pdf_file))) {
                File::delete(public_path($notice->pdf_file));
            }

            // Delete the notice record
            $notice->delete();

            DB::commit();
            // --- CHANGED ---
            return redirect()->route('notice.index')->with('success', 'Notice deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete notice ID {$notice->id}: " . $e->getMessage());
            // --- CHANGED ---
            return redirect()->route('notice.index')->with('error', 'Failed to delete notice.');
        }
    }
}