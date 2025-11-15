<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUsMessage; // Import the model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\RedirectResponse;
class ContactUsController extends Controller
{
    /**
     * Add permissions middleware (adjust permission names as needed).
     */
    function __construct()
{
     $this->middleware('permission:contactUsView', ['only' => ['index','data', 'show']]); // <-- Added 'show'
     $this->middleware('permission:contactUsDelete', ['only' => ['destroy', 'destroyMultiple']]);
}

    /**
 * Display the specified resource. (For AJAX modal)
 */
public function show($id): JsonResponse
{
    try {
        // Find the message by ID or fail with a 404 error
        $message = ContactUsMessage::findOrFail($id);
        // Return the message details as JSON
        return response()->json($message);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
         Log::warning("Attempted to view non-existent contact message ID {$id}");
         return response()->json(['error' => 'Message not found.'], 404);
    } catch (Exception $e) {
        Log::error("Failed to fetch contact message ID {$id}: " . $e->getMessage());
        return response()->json(['error' => 'Failed to retrieve message details.'], 500);
    }
}

    /**
     * Display the listing page.
     */
    public function index(): View
    {
        return view('admin.contact_us.index'); // Points to the index blade file
    }

    /**
     * Process AJAX request for datatable.
     * Handles searching, sorting, and pagination.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = ContactUsMessage::query();

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('fullname', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('mobilenumber', 'like', '%' . $searchTerm . '%')
                      ->orWhere('message', 'like', '%' . $searchTerm . '%');
                });
            }

            // Sorting functionality
            $sortColumn = $request->input('sort', 'created_at'); // Default sort: newest first
            $sortDirection = $request->input('direction', 'desc');

            $allowedSortColumns = ['id', 'fullname', 'email', 'mobilenumber', 'created_at']; // Valid columns
            if (in_array($sortColumn, $allowedSortColumns)) {
                 $query->orderBy($sortColumn, $sortDirection);
            } else {
                 $query->orderBy('created_at', 'desc'); // Fallback sort
            }

            // Pagination
            $paginated = $query->paginate(10); // Adjust page size (e.g., 10)

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(), // Send per_page for calculations in JS
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch contact us messages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve messages.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage (single delete).
     */
    public function destroy($id): RedirectResponse // <-- CHANGED return type
    {
        try {
            $message = ContactUsMessage::findOrFail($id);
            $message->delete();

            // --- CHANGED ---
            // Redirect back to the index page with a success flash message
            return redirect()->route('contactUs.index')->with('success', 'Message deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to delete non-existent contact message ID {$id}");

             // --- CHANGED ---
             return redirect()->route('contactUs.index')->with('error', 'Message not found.');

        } catch (Exception $e) {
            Log::error("Failed to delete contact message ID {$id}: " . $e->getMessage());

             // --- CHANGED ---
            return redirect()->route('contactUs.index')->with('error', 'Failed to delete message.');
        }
    }

    /**
     * Remove multiple specified resources from storage (bulk delete).
     */
    public function destroyMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:contact_us_messages,id' // Validate each ID exists
        ]);

        try {
            $idsToDelete = $request->input('ids');
            ContactUsMessage::whereIn('id', $idsToDelete)->delete();
            $count = count($idsToDelete);
            return response()->json(['message' => "Successfully deleted {$count} messages."]);
        } catch (Exception $e) {
            Log::error('Failed to bulk delete contact messages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete selected messages.'], 500);
        }
    }
}