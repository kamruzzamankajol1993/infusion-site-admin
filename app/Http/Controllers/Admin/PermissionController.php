<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Auth;
use App\Exports\PermissionExport;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\JsonResponse;
class PermissionController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:permissionView|permissionAdd|permissionUpdate|permissionDelete', ['only' => ['index','store','destroy','update']]);
         $this->middleware('permission:permissionAdd', ['only' => ['create','store']]);
         $this->middleware('permission:permissionUpdate', ['only' => ['edit','update']]);
         $this->middleware('permission:permissionDelete', ['only' => ['destroy']]);
    }

   public function destroyMultiple(Request $request): JsonResponse // <--- CORRECT RETURN TYPE HINT
{
    // Validate that 'ids' is an array and each element is an integer
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'integer'
    ]);

    try {
        $idsToDelete = $request->input('ids');

        // Find the group names associated with the first permission ID of each group
        $groupNames = DB::table('permissions')
                        ->whereIn('id', $idsToDelete)
                        ->distinct()
                        ->pluck('group_name');

        if ($groupNames->isEmpty()) {
            // Return a JsonResponse even for errors
            return response()->json(['message' => 'No valid permission groups found for the provided IDs.'], 404);
        }

        DB::beginTransaction();
        // Delete all permissions belonging to the identified groups
        Permission::whereIn('group_name', $groupNames)->delete();
        DB::commit();

        $count = $groupNames->count();
        Log::info("Multiple permission groups deleted successfully.", ['groups' => $groupNames->toArray(), 'count' => $count]);
        // Ensure you return the Illuminate\Http\JsonResponse
        return response()->json(['message' => "Successfully deleted {$count} permission groups."]);

    } catch (Exception $e) {
        DB::rollBack();
        Log::error('Failed to bulk delete permission groups: ' . $e->getMessage());
        // Ensure you return the Illuminate\Http\JsonResponse
        return response()->json(['error' => 'Failed to delete selected permission groups.'], 500);
    }
}

    public function downloadPermissionExcel()
    {
        try {
            return Excel::download(new PermissionExport, 'permission.xlsx');
        } catch (Exception $e) {
            Log::error('Failed to export permissions to Excel: ' . $e);
            return redirect()->back()->with('error', 'Could not export Excel file. Please check logs.');
        }
    }

    public function downloadPermissionPdf()
    {
        try {
            $permissionListall = Permission::select('group_name')->groupBy('group_name')->get();
            $html = view('admin.permission._partial.pdfSheet', ['permissionListall' => $permissionListall ])->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output('permissionList.pdf', 'D'))
                ->header('Content-Type', 'application/pdf');
        } catch (Exception $e) {
            Log::error('Failed to export permissions to PDF: ' . $e);
            return response("Could not generate PDF. Please check logs for more information.", 500);
        }
    }

    public function data(Request $request)
    {
        try {
            $query = DB::table('permissions')
                ->select('group_name', DB::raw('MIN(id) as first_permission_id'))
                ->groupBy('group_name');

            if ($request->filled('search')) {
                $query->where('group_name', 'like', '%' . $request->search . '%');
            }

            $query->orderBy($request->get('sort', 'group_name'), $request->get('direction', 'asc'));
            $paginated = $query->paginate($request->get('perPage', 10));

            $mapped = $paginated->getCollection()->map(function ($item) {
                $item->permissions = DB::table('permissions')
                    ->where('group_name', $item->group_name)
                    ->select('id', 'name')
                    ->get();
                return $item;
            });

            $paginated->setCollection($mapped);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'can_edit' => Auth::user()->can('permissionUpdate'),
                'can_delete' => Auth::user()->can('permissionDelete'),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to fetch permissions data: ' . $e);
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function index(): View
    {
        // CommonController::addToLog('permissionView');
        $pers = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
        return view('admin.permission.index',compact('pers'));
    }

    public function edit($id): View
    {
        // CommonController::addToLog('permissionedit');
        $pers = DB::table('permissions')->where('id',$id)->value('group_name');
        $persEdit = DB::table('permissions')->where('group_name',$pers)->get();
        return view('admin.permission.edit',compact('pers','persEdit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name.*' => 'required|string',
            'group_name' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            // CommonController::addToLog('permissionStore');

            $permissionsToInsert = [];
            foreach ($request->name as $name) {
                $permissionsToInsert[] = [
                    'name' => $name,
                    'guard_name' => 'web',
                    'group_name' => $request->group_name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($permissionsToInsert)) {
                Permission::insert($permissionsToInsert);
            }

            DB::commit();
            Log::info('Permissions created successfully.', ['group_name' => $request->group_name, 'count' => count($permissionsToInsert)]);
            return redirect()->back()->with('success','Created successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Failed to create permissions: ' . $e);
            return redirect()->back()->with('error', 'Failed to create permissions. Please check logs.')->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name.*' => 'required|string',
            'group_name' => 'required|string',
        ]);
        
        try {
            DB::beginTransaction();
            // CommonController::addToLog('permissionUpdate');
            
            // Delete old permissions for the group
            Permission::where('group_name', $request->group_name)->delete();

            // Prepare new permissions for insertion
            $permissionsToInsert = [];
            if ($request->has('name')) {
                foreach ($request->name as $name) {
                    $permissionsToInsert[] = [
                        'name' => $name,
                        'guard_name' => 'web',
                        'group_name' => $request->group_name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            if (!empty($permissionsToInsert)) {
                Permission::insert($permissionsToInsert);
            }

            DB::commit();
            Log::info('Permissions updated successfully.', ['group_name' => $request->group_name, 'count' => count($permissionsToInsert)]);
            return redirect()->route('permissions.index')->with('info','Updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to update permissions for group {$request->group_name}: " . $e);
            return redirect()->back()->with('error', 'Failed to update permissions. Please check logs.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            // CommonController::addToLog('permissionDelete');
            
            $groupName = DB::table('permissions')->where('id', $id)->value('group_name');

            if ($groupName) {
                Permission::where('group_name', $groupName)->delete();
                DB::commit();
                Log::info('Permission group deleted successfully.', ['group_name' => $groupName]);
                return response()->json(['message' => 'Permissions deleted successfully']);
            } else {
                DB::rollBack();
                return response()->json(['message' => 'Permission group not found.'], 404);
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete permission group based on ID {$id}: " . $e);
            return response()->json(['error' => 'Failed to delete permissions.'], 500);
        }
    }
}