<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team; // Import the model
use App\Traits\ImageUploadTrait; // Import the trait
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse; // For store method redirect
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; // For deleting file
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    use ImageUploadTrait; // Use the image upload trait

    /**
     * Add permissions middleware.
     */
    public function __construct()
    {
         // *** IMPORTANT: You must create these permissions: ***
         // teamView, teamAdd, teamUpdate, teamDelete
         $this->middleware('permission:teamView|teamAdd|teamUpdate|teamDelete', ['only' => ['index','data']]);
         $this->middleware('permission:teamAdd', ['only' => ['store']]);
         $this->middleware('permission:teamUpdate', ['only' => ['show', 'update', 'updateOrder']]); // Added updateOrder
         $this->middleware('permission:teamDelete', ['only' => ['destroy']]);
    }

    /**
     * Display the listing page with tabs.
     */
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'table'); // Default to 'table' tab
        $teams = [];

        // If 'reorder' tab is active, fetch all teams in order
        if ($activeTab === 'reorder') {
            $teams = Team::orderBy('order', 'asc')->get();
        }

        // Pass active tab and teams (if any) to the view
        return view('admin.team.index', compact('activeTab', 'teams'));
    }

   /**
    * Provide data for the AJAX Data Table.
    */
   public function data(Request $request): JsonResponse
    {
        try {
            $query = Team::query();

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('designation', 'like', '%' . $request->search . '%');
            }

            // Sorting
            // *** UPDATED: Default sort is by 'order' ascending ***
            $sortColumn = $request->input('sort', 'order');
            $sortDirection = $request->input('direction', 'asc');
            $allowedSorts = ['id', 'name', 'designation', 'order', 'created_at'];
            
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('order', 'asc'); // Fallback sort
            }

            $paginated = $query->paginate(10); // Adjust page size

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch team data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage (from Add modal).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:512', // Max 512KB for 300x300
        ]);

        DB::beginTransaction();
        try {
            $teamData = $request->only('name', 'designation');

            // Set dimensions to 300x300
            $width = 300;
            $height = 300; 

            $tempModel = new Team();
            $imagePath = $this->handleImageUpload($request, $tempModel, 'image', 'team', $width, $height); 
            
            if ($imagePath) {
                $teamData['image'] = $imagePath;
            } else {
                 throw new Exception("Team member image upload failed or missing.");
            }

            // 'order' is set automatically by the model's boot method
            Team::create($teamData);
            DB::commit();

            Log::info("Team member created successfully.", ['name' => $request->name]);
            return redirect()->route('team.index')->with('success','Team member created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to create team member: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors($e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : ['error' => "Failed to create member. Please check logs."]);
        }
    }

    /**
     * Display the specified resource (used to fetch data for edit modal).
     */
    public function show($id): JsonResponse
    {
        try {
            $team = Team::findOrFail($id);
            if ($team->image) {
                $team->image_url = asset($team->image);
            } else {
                $team->image_url = null;
            }
            return response()->json($team);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to fetch non-existent Team ID {$id}");
             return response()->json(['error' => 'Member not found.'], 404);
        } catch (Exception $e) {
            Log::error("Failed to fetch Team ID {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve member data.'], 500);
        }
    }

    /**
     * Update the specified resource in storage (from Edit modal).
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:512', // Max 512KB
        ]);
        // Note: 'order' is not updated here.

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update')
                         ->withInput()
                         ->with('error_modal_id', $id);
        }

        DB::beginTransaction();
        try {
            $team = Team::findOrFail($id);
            
            $teamData = $request->only('name', 'designation');

            // Set dimensions to 300x300
            $width = 300;
            $height = 300;

            if ($request->hasFile('image')) {
                $imagePath = $this->handleImageUpdate($request, $team, 'image', 'team', $width, $height);
                $teamData['image'] = $imagePath;
            }

            $team->update($teamData);
            DB::commit();

            Log::info("Team member updated successfully.", ['id' => $id]);
            return redirect()->route('team.index')->with('success', 'Member updated successfully');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update team member ID {$id}: " . $e->getMessage());
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update member.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        }
    }

    /**
     * Handle the AJAX request to update the order of team members.
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $request->validate(['teamIds' => 'required|array']);

        DB::beginTransaction();
        try {
            foreach ($request->teamIds as $index => $id) {
                // Update order, starting from 1
                Team::where('id', $id)->update(['order' => $index + 1]);
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Team order updated successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to update team order: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update order.'], 500);
        }
    }


    /**
     * Remove the specified resource from storage (single delete).
     */
    public function destroy($id): RedirectResponse
    {
        try {
            $team = Team::findOrFail($id);
            DB::beginTransaction();

            if ($team->image && File::exists(public_path($team->image))) {
                File::delete(public_path($team->image));
            }

            $team->delete();
            DB::commit();
            
            // Re-order remaining items after deletion (optional but clean)
            DB::statement('SET @count = 0;');
            DB::update('UPDATE teams SET `order` = (@count:=@count+1) ORDER BY `order` ASC;');

            return redirect()->route('team.index')->with('success', 'Member deleted successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             DB::rollBack();
             Log::warning("Attempted to delete non-existent Team ID {$id}");
             return redirect()->route('team.index')->with('error', 'Member not found.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete Team ID {$id}: " . $e->getMessage());
            return redirect()->route('team.index')->with('error', 'Failed to delete member.');
        }
    }
}