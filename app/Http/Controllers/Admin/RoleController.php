<?php
    
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Admin\CommonController;
use App\Exports\RoleExport;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf; 
use Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:roleAdd|roleView|roleEdit|roleDelete', ['only' => ['index','store']]);
         $this->middleware('permission:roleAdd', ['only' => ['create','store']]);
         $this->middleware('permission:roleEdit', ['only' => ['edit','update']]);
         $this->middleware('permission:roleDelete', ['only' => ['destroy']]);
    }


    public function downloadRoleExcel()
    {
        try {
            return Excel::download(new RoleExport, 'role.xlsx');
        } catch (Exception $e) {
            Log::error('Failed to export roles to Excel: ' . $e);
            return redirect()->back()->with('error', 'Could not export Excel file. Please check logs.');
        }
    }

    public function downloadRolePdf()
    {
        try {
            $roleListall = Role::select('name')->groupBy('name')->get();
            $html = view('admin.roles._partial.pdfSheet', ['roleListall' => $roleListall ])->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output('roleList.pdf', 'D'))
                ->header('Content-Type', 'application/pdf');
        } catch (Exception $e) {
            Log::error('Failed to export roles to PDF: ' . $e);
            return response("Could not generate PDF. Please check logs for more information.", 500);
        }
    }


    public function data(Request $request)
    {
        try {
            $query = Role::query();

            if ($request->filled('search')) {
                $query->where('name', 'like', $request->search . '%');
            }

            $query->orderBy('id','desc');
            $paginated = $query->paginate(10);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'can_edit' => Auth::user()->can('roleEdit'),
                'can_delete' => Auth::user()->can('roleDelete'),
                'can_show' => Auth::user()->can('roleView'),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to fetch roles data: ' . $e);
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function index(Request $request): View
    {
        try {
            // CommonController::addToLog('role-list');
            $roles = Role::orderBy('id','DESC')->get();
            return view('admin.roles.index',compact('roles'));
        } catch (Exception $e) {
            Log::error('Failed to load roles index: ' . $e->getMessage());
            // It's better to abort or redirect with an error message
            // For simplicity, we can redirect back with an error.
            return view('admin.roles.index')->with('error', 'Could not retrieve roles.');
        }
    }
    
    public function create(): View
    {
        try {
            // CommonController::addToLog('role-add');
            $permission = Permission::get();
            return view('admin.roles.create',compact('permission'));
        } catch (Exception $e) {
            Log::error('Failed to open create role page: ' . $e->getMessage());
            return view('admin.roles.create')->with('error', 'Could not load the page properly.');
        }
    }
    
     public function store(Request $request): RedirectResponse
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name',
                'permission' => 'required',
            ]);

            CommonController::addToLog('role-store');

            $permissionsID = array_map(
                function($value) { return (int)$value; },
                $request->input('permission')
            );
        
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($permissionsID);
        
            return redirect()->route('roles.index')
                            ->with('success','Role created successfully');
        } catch (Exception $e) {
            Log::error('Failed to store role: ' . $e->getMessage());
            return redirect()->route('roles.index')
                            ->with('error','Failed to create role.');
        }
    }

    public function show($id): View
    {
        try {
            // CommonController::addToLog('role-view');
            $role = Role::find($id);
            $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                ->where("role_has_permissions.role_id",$id)
                ->get();
        
            return view('admin.roles.show',compact('role','rolePermissions'));
        } catch (Exception $e) {
            Log::error("Failed to show role with ID {$id}: " . $e->getMessage());
            // Redirecting to index as the specific role could not be found or loaded.
            return view('admin.roles.index')->with('error', 'Could not display the role details.');
        }
    }
    
    public function edit($id): View
    {
        try {
            $role = Role::find($id);
            $permission = Permission::get();
            $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();

            // CommonController::addToLog('role-edit');
            return view('admin.roles.edit',compact('role','permission','rolePermissions'));
        } catch (Exception $e) {
            Log::error("Failed to load edit page for role ID {$id}: " . $e->getMessage());
            return view('admin.roles.index')->with('error', 'Could not load the edit page.');
        }
    }
    
    public function update(Request $request, $id): RedirectResponse
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'permission' => 'required',
            ]);
        
            CommonController::addToLog('role-update');

            $role = Role::find($id);
            $role->name = $request->input('name');
            $role->save();

            $permissionsID = array_map(
                function($value) { return (int)$value; },
                $request->input('permission')
            );
        
            $role->syncPermissions($permissionsID);
        
            return redirect()->route('roles.index')
                            ->with('success','Role updated successfully');
        } catch (Exception $e) {
            Log::error("Failed to update role ID {$id}: " . $e->getMessage());
            return redirect()->route('roles.index')
                            ->with('error','Failed to update role.');
        }
    }

    public function destroy($id)
    {
        try {
            DB::table("roles")->where('id',$id)->delete();
            Log::info("Role deleted successfully.", ['role_id' => $id]);
            return response()->json(['message' => 'Role deleted successfully']);
        } catch (Exception $e) {
            Log::error("Failed to delete role ID {$id}: " . $e);
            return response()->json(['error' => 'Failed to delete role.'], 500);
        }
    }
}