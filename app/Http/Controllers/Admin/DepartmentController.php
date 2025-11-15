<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Exports\BranchExport;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;
class DepartmentController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:departmentView|departmentAdd|departmentUpdate|departmentDelete', ['only' => ['index','store','destroy','update']]);
         $this->middleware('permission:departmentAdd', ['only' => ['create','store']]);
         $this->middleware('permission:departmentUpdate', ['only' => ['edit','update']]);
         $this->middleware('permission:departmentDelete', ['only' => ['destroy']]);
    }

    public function downloadDepartmentExcel()
    {
        try {
            return Excel::download(new BranchExport, 'departmentes.xlsx');
        } catch (Exception $e) {
            Log::error('Failed to export branches to Excel: ' . $e);
            return redirect()->back()->with('error', 'Could not export Excel file. Please check logs.');
        }
    }

    public function downloadDepartmentPdf()
    {
        try {
            $departmentList = Department::latest()->select('name')->get();
            $html = view('admin.department._partial.pdfSheet', ['departmentList' => $departmentList])->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output('departmentList.pdf', 'D'))
                ->header('Content-Type', 'application/pdf');
        } catch (Exception $e) {
            Log::error('Failed to export departmentes to PDF: ' . $e);
            return response("Could not generate PDF. Please check logs.", 500);
        }
    }

    public function index(): View
    {
        // CommonController::addToLog('departmentView');
        $pers = Department::where('id','!=',1)->latest()->get();
        return view('admin.department.index', compact('pers'));
    }

    public function data(Request $request)
    {
        try {
            $query = Department::query();

            if ($request->filled('search')) {
                $query->where('name', 'like', $request->search . '%');
            }

            $query->orderBy('name','asc');
            $paginated = $query->paginate(10);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to fetch department data: ' . $e);
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = Department::findOrFail($id);
            return response()->json($user);
        } catch (Exception $e) {
            Log::error("Failed to fetch department ID {$id}: " . $e);
            return response()->json(['error' => 'department not found.'], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:departments,name']);
        
        try {
            $department = Department::create($request->all());
            // CommonController::addToLog('departmentStore');
            Log::info('Department created successfully.', ['id' => $department->id, 'name' => $department->name]);
            return redirect()->back()->with('success','Created successfully!');
        } catch (Exception $e) {
            Log::error('Failed to create department: ' . $e);
            return redirect()->back()->with('error', 'Failed to create department. Please check logs.');
        }
    }

    // --- MODIFIED UPDATE METHOD ---
    public function update(Request $request, $id): RedirectResponse // <-- MODIFIED RETURN TYPE
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:departments,name,' . $id]
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update') // <-- Use named error bag 'update'
                         ->withInput()
                         ->with('error_modal_id', $id); // <-- Add this to re-open the modal
        }

        try {
            $department = Department::findOrFail($id);
            $department->update($request->all());
            // CommonController::addToLog('departmentUpdate');
            Log::info('Department updated successfully.', ['id' => $id, 'new_name' => $request->name]);
            
            // --- MODIFIED RESPONSE ---
            return redirect()->route('department.index')->with('success', 'Department updated successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Attempted to update non-existent department ID {$id}");
             // --- MODIFIED RESPONSE ---
             return redirect()->back()
                        ->withErrors(['error' => 'Department not found.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        } catch (Exception $e) {
            Log::error("Failed to update department ID {$id}: " . $e);
            // --- MODIFIED RESPONSE ---
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update department.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        }
    }
    // --- END MODIFIED UPDATE METHOD ---

    // --- MODIFIED DESTROY METHOD ---
    public function destroy($id): RedirectResponse // <-- MODIFIED RETURN TYPE
    {
        try {
            $department = Department::findOrFail($id); // Find first
            $department->delete();
            // CommonController::addToLog('departmentDelete');
            Log::info('Department deleted successfully.', ['id' => $id]);
            
            // --- MODIFIED RESPONSE ---
            return redirect()->route('department.index')->with('success', 'Department deleted successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Attempted to delete non-existent department ID {$id}");
            // --- MODIFIED RESPONSE ---
            return redirect()->route('department.index')->with('error', 'Department not found.');
        
        } catch (Exception $e) {
            Log::error("Failed to delete department ID {$id}: " . $e);
            // --- MODIFIED RESPONSE ---
            // Handle foreign key constraint
             if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                  return redirect()->route('department.index')->with('error', 'Cannot delete department. It is associated with other records.');
             }
            return redirect()->route('department.index')->with('error', 'Failed to delete department.');
        }
    }
    // --- END MODIFIED DESTROY METHOD ---
}