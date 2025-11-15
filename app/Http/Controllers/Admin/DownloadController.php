<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download; // 1. Use new model
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // Use File facade
use Exception;

class DownloadController extends Controller
{
    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // 2. Adjust permission names
        $this->middleware('permission:downloadView', ['only' => ['index', 'data']]);
        $this->middleware('permission:downloadAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:downloadUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:downloadDelete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // 3. Point to new view folder
        return view('admin.download.index');
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            // 4. Query new model, remove category logic
            $query = Download::query();

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where('title', 'like', '%' . $searchTerm . '%');
            }

            // Sorting
            $sortColumn = $request->input('sort', 'date');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'title', 'date', 'created_at'];
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('date', 'desc');
            }

            $paginated = $query->paginate(10);

            // Modify pdf_file path for asset()
            $paginated->getCollection()->transform(function ($item) {
                if ($item->pdf_file) {
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
            Log::error('Failed to fetch downloads: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve downloads.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // 5. Point to new view, no categories needed
        return view('admin.download.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // 6. Remove category_id validation
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'pdf_file' => 'required|file|mimes:pdf|max:5120', // Required PDF, Max 5MB
        ]);

        DB::beginTransaction();
        try {
            $downloadData = $validatedData;
            unset($downloadData['pdf_file']);

            // Handle PDF File Upload
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                // 7. Use new folder path
                $destinationPath = public_path('uploads/downloads');
                $file->move($destinationPath, $fileName);
                $downloadData['pdf_file'] = 'uploads/downloads/' . $fileName;
            } else {
                 throw new Exception("PDF file upload failed or missing.");
            }

            // 8. Create new Download
            Download::create($downloadData);

            DB::commit();
            Log::info('Download created successfully.', ['title' => $request->title]);
            // 9. Point to new index route
            return redirect()->route('download.index')->with('success', 'Download created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create download: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to save download. Please check logs.']);
        }
    }

    /**
     * Display the specified resource. (Not typically used, but good to have)
     */
    public function show(Download $download): View
    {
         // 10. Point to new view folder
         return view('admin.download.show', compact('download'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Download $download): View
    {
        // 11. Point to new view folder, no categories
        return view('admin.download.edit', compact('download'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Download $download): RedirectResponse
    {
        // 12. Remove category validation
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'pdf_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $downloadData = $validatedData;
            unset($downloadData['pdf_file']);

            if ($request->hasFile('pdf_file')) {
                // Delete old file
                if ($download->pdf_file && File::exists(public_path($download->pdf_file))) {
                    File::delete(public_path($download->pdf_file));
                }

                // Store new file
                $file = $request->file('pdf_file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                // 13. Use new folder path
                $destinationPath = public_path('uploads/downloads');
                $file->move($destinationPath, $fileName);
                $downloadData['pdf_file'] = 'uploads/downloads/' . $fileName;
            }

            // 14. Update Download
            $download->update($downloadData);

            DB::commit();
            Log::info('Download updated successfully.', ['id' => $download->id]);
            // 15. Point to new index route
            return redirect()->route('download.index')->with('success', 'Download updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update download ID ' . $download->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => 'Failed to update download. Please check logs.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Download $download): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Delete associated PDF file
            if ($download->pdf_file && File::exists(public_path($download->pdf_file))) {
                File::delete(public_path($download->pdf_file));
            }

            // Delete the record
            $download->delete();

            DB::commit();
            // 16. Point to new index route
            return redirect()->route('download.index')->with('success', 'Download deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete download ID {$download->id}: " . $e->getMessage());
            return redirect()->route('download.index')->with('error', 'Failed to delete download.');
        }
    }
}