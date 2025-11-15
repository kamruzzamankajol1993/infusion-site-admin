<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialLink;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\View\View;            // Add View
use Illuminate\Http\JsonResponse;     // Add JsonResponse
use Illuminate\Http\RedirectResponse; // Add RedirectResponse
use Illuminate\Validation\Rule;
class SocialLinkController extends Controller
{
    /**
     * Define permissions for controller methods.
     */
    function __construct()
    {
         // Adjust permission names according to your system (e.g., socialLinkView, socialLinkAdd)
         $this->middleware('permission:socialLinkView|socialLinkAdd|socialLinkUpdate|socialLinkDelete', ['only' => ['index','data']]); // Added data
         $this->middleware('permission:socialLinkAdd', ['only' => ['store']]); // Standard POST for store
         $this->middleware('permission:socialLinkUpdate', ['only' => ['show','update']]); // show for AJAX edit data, update for AJAX save
         $this->middleware('permission:socialLinkDelete', ['only' => ['destroy']]); // AJAX delete
    }

    /**
     * Predefined list of social media platform names for the dropdown.
     * @var array
     */
    private $socialMediaNames = [
        'Facebook', 'Twitter', 'Instagram', 'LinkedIn', 'YouTube', 'TikTok',
        'Pinterest', 'Snapchat', 'Reddit', 'WhatsApp', 'Telegram', 'Vimeo',
        'GitHub', 'Stack Overflow', 'Flickr', 'Tumblr', 'Discord',
        // Add more platforms as needed
    ];

    /**
     * Display the listing page for Social Links.
     * Passes the platform names to the view for use in modals.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        try {
            // Pass the predefined names to the view for modal dropdowns
            $socialMediaNames = $this->socialMediaNames;
            return view('admin.social_link.index', compact('socialMediaNames'));
        } catch (Exception $e) {
            Log::error('Failed to load social links index page: ' . $e->getMessage());
            // Redirect back or to dashboard with an error message
            return redirect()->route('home')->with('error', 'Could not load social links page.');
        }
    }

    /**
     * Fetch data for the AJAX datatable on the index page.
     * Handles pagination, searching, and sorting.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = SocialLink::query();

            // --- Search Functionality ---
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                // Search against title and link columns
                $query->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('link', 'like', '%' . $searchTerm . '%');
            }

            // --- Sorting Functionality ---
            $sortColumn = $request->input('sort', 'title'); // Default sort column
            $sortDirection = $request->input('direction', 'asc'); // Default sort direction
            $allowedSorts = ['id', 'title', 'link', 'created_at']; // Columns allowed for sorting

            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('title', 'asc'); // Fallback sort column
            }

            // --- Pagination ---
            $perPage = $request->input('perPage', 10); // Default to 10 items per page
            $paginated = $query->paginate($perPage);

            // Return JSON response suitable for the datatable script
            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch social links data: ' . $e->getMessage());
            // Return error response
            return response()->json(['error' => 'Failed to retrieve social links data.'], 500);
        }
    }


    /**
     * Store a newly created social link (handles submission from the Add Modal).
     * Uses standard POST request and redirects.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Validation rules
        $request->validate([
            'title' => [
                'required',
                'string',
                Rule::in($this->socialMediaNames), // Ensure title is from the predefined list
                'unique:social_links,title'         // Ensure title is unique in the table
            ],
            'link' => 'required|url|max:1000', // Ensure link is a valid URL
        ], [
            // Custom error messages
            'title.in' => 'Please select a valid social media platform from the list.',
            'title.unique' => 'A social link for this platform already exists.',
            'link.url' => 'The link must be a valid URL (e.g., https://...).',
            'link.required' => 'The Link URL field is required.',
        ]);

        try {
            // Create the new social link
            SocialLink::create($request->only(['title', 'link']));

            Log::info('Social link created successfully.', ['title' => $request->title]);
            // Redirect back to the index page with a success message
            return redirect()->route('socialLink.index')->with('success', 'Social link added successfully!');

        } catch (Exception $e) {
            Log::error('Failed to create social link: ' . $e->getMessage());
            // Redirect back to the previous page (the modal source) with errors and input
            return redirect()->back()->with('error', 'Failed to add social link. Please check logs.')->withInput();
        }
    }

    /**
     * Fetch data for a specific social link to populate the Edit Modal.
     * Returns JSON data.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
     public function show($id): JsonResponse
    {
        try {
            // Find the link by ID or fail with a 404 error
            $socialLink = SocialLink::findOrFail($id);
            // Return the link data as JSON
            return response()->json($socialLink);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Attempted to fetch non-existent social link ID {$id} for edit.");
            return response()->json(['error' => 'Social link not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch social link ID {$id} for edit: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve social link data.'], 500);
        }
    }


    /**
     * Update the specified social link in storage (handles AJAX submission from Edit Modal).
     * Uses PUT method via AJAX.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id): RedirectResponse // <-- Changed to RedirectResponse
    {
        try {
            // Find the link or fail (throws ModelNotFoundException if not found)
            $socialLink = SocialLink::findOrFail($id);

            // Validation rules for update (allow current title)
            $validatedData = $request->validate([
                'title' => [
                    'required',
                    'string',
                    Rule::in($this->socialMediaNames),
                    Rule::unique('social_links', 'title')->ignore($socialLink->id) // Ignore current ID for unique check
                ],
                'link' => 'required|url|max:1000',
            ], [ /* ... custom messages ... */ ]);

            // Update the record
            $socialLink->update($validatedData);

            Log::info('Social link updated successfully.', ['id' => $id]);
            // Redirect back to index with success
            return redirect()->route('socialLink.index')->with('success', 'Social link updated successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Attempt to update non-existent social link ID: {$id}");
            return redirect()->route('socialLink.index')->with('error', 'Social link not found.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // On validation error, Laravel will automatically redirect back
            // to the previous page (index) with errors.
            // We can customize this to redirect back to the index *with* the modal open,
            // but that's more complex. A simple redirect is standard.
             return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error("Failed to update social link (ID: {$id}): " . $e->getMessage());
            // Return general error JSON response
            return redirect()->route('socialLink.index')->with('error', 'Failed to update social link.');
        }
    }

    /**
     * Remove the specified social link from storage (handles AJAX DELETE request from table).
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): RedirectResponse // <-- Changed to RedirectResponse
    {
        try {
            // Find the link or fail
            $socialLink = SocialLink::findOrFail($id);
            $title = $socialLink->title;
            // Delete the record
            $socialLink->delete();

            Log::info('Social link deleted successfully.', ['id' => $id, 'title' => $title]);
            // Redirect back to index with success
            return redirect()->route('socialLink.index')->with('success', 'Social link deleted successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempt to delete non-existent social link ID: {$id}");
             return redirect()->route('socialLink.index')->with('error', 'Social link not found.');
        }
        catch (Exception $e) {
            Log::error("Failed to delete social link (ID: {$id}): " . $e->getMessage());
             // Redirect back to index with error
            return redirect()->route('socialLink.index')->with('error', 'Failed to delete social link.');
        }
    }

    // Note: create() and edit() methods returning Views are typically not needed
    // when using modals loaded from the index page.
}