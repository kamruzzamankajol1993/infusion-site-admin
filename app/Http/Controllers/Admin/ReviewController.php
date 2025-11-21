<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function __construct()
    {
         // Permissions for viewing, updating (approving), and deleting reviews
         // Ensure these permissions exist in your database or Permission seeder
         $this->middleware('permission:reviewView|reviewUpdate|reviewDelete', ['only' => ['index','data']]);
         $this->middleware('permission:reviewUpdate', ['only' => ['update']]);
         $this->middleware('permission:reviewDelete', ['only' => ['destroy', 'destroyMultiple', 'destroyImage']]);
    }

    /**
     * Display the review management page.
     */
    public function index(): View
    {
        return view('admin.review.index');
    }

    /**
     * Fetch data for the AJAX data table.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            // Eager load product and user to avoid N+1 query performance issues
            $query = ProductReview::with(['product', 'user']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('review', 'like', "%$search%")
                      ->orWhere('rating', 'like', "%$search%")
                      // Search within the related Product name
                      ->orWhereHas('product', function($pq) use ($search) {
                          $pq->where('name', 'like', "%$search%");
                      })
                      // Search within the related User name or email
                      ->orWhereHas('user', function($uq) use ($search) {
                          $uq->where('name', 'like', "%$search%")
                             ->orWhere('email', 'like', "%$search%");
                      });
                });
            }

            // Filter by status if passed (optional filter dropdown)
            if ($request->filled('status') && $request->status !== 'all') {
                $status = $request->status === 'approved' ? 1 : 0;
                $query->where('status', $status);
            }

            // Default sort by newest first
            $query->orderBy('created_at', 'desc');
            
            $paginated = $query->paginate(10);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch reviews: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve reviews.'], 500);
        }
    }

    /**
     * Toggle the status of a review (Approve/Pending).
     * Uses PUT method.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $review = ProductReview::findOrFail($id);
            
            // Toggle the boolean status
            $review->status = !$review->status; 
            $review->save();
            
            $statusMsg = $review->status ? 'Approved' : 'Pending';
            
            // Log the action
            Log::info("Review ID {$id} status changed to {$statusMsg} by User ID " . auth()->id());

            return redirect()->route('review.index')->with('success', "Review status changed to $statusMsg.");

        } catch (Exception $e) {
            Log::error("Failed to update review ID {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update review status.');
        }
    }

    /**
     * Delete a single review.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $review = ProductReview::findOrFail($id);
            
            // If you implement review images later, delete them here:
            // if ($review->images) { ... delete files ... }

            $review->delete();
            
            return redirect()->route('review.index')->with('success', 'Review deleted successfully.');
        } catch (Exception $e) {
            Log::error("Failed to delete review ID {$id}: " . $e->getMessage());
            return redirect()->route('review.index')->with('error', 'Failed to delete review.');
        }
    }

    /**
     * Bulk delete reviews.
     * Expects an array of IDs in the request body.
     */
    public function destroyMultiple(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:product_reviews,id'
        ]);

        try {
            // If you have images, you might need to loop through to delete files first
            // For now, simple bulk delete:
            ProductReview::whereIn('id', $request->ids)->delete();
            
            return response()->json(['message' => 'Selected reviews deleted successfully.']);
        } catch (Exception $e) {
            Log::error('Failed to delete multiple reviews: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete reviews.'], 500);
        }
    }
    
    /**
     * Placeholder for image deletion if you implement review images later.
     * (Route exists in web.php: review-images/{image})
     */
    public function destroyImage($id): RedirectResponse
    {
        // 1. Find the review/image record
        // 2. Delete file from storage using File::delete()
        // 3. Update database record
        
        return redirect()->back()->with('success', 'Image deleted (Placeholder).');
    }
}