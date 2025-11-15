<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use App\Exports\DesignationExport;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Validator;
class DesignationController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:designationView|designationAdd|designationUpdate|designationDelete', ['only' => ['index','store','destroy','update']]);
         $this->middleware('permission:designationAdd', ['only' => ['create','store']]);
         $this->middleware('permission:designationUpdate', ['only' => ['edit','update']]);
         $this->middleware('permission:designationDelete', ['only' => ['destroy']]);
    }

    public function downloadDesignationExcel()
    {
        try {
            return Excel::download(new DesignationExport, 'designations.xlsx');
        } catch (Exception $e) {
            Log::error('Failed to export designations to Excel: ' . $e);
            return redirect()->back()->with('error', 'Could not export Excel file. Please check logs.');
        }
    }

    public function downloadDesignationPdf()
    {
        try {
            $designationList = Designation::latest()->select('name')->get();
            $html = view('admin.designation._partial.pdfSheet', ['designationList' => $designationList])->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return response($mpdf->Output('designationList.pdf', 'D'))
                ->header('Content-Type', 'application/pdf');
        } catch (Exception $e) {
            Log::error('Failed to export designations to PDF: ' . $e);
            return response("Could not generate PDF. Please check logs.", 500);
        }
    }

    public function data(Request $request)
    {
        try {
            $query = Designation::query();

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
            Log::error('Failed to fetch designation data: ' . $e);
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = Designation::findOrFail($id);
            return response()->json($user);
        } catch (Exception $e) {
            Log::error("Failed to fetch designation ID {$id}: " . $e);
            return response()->json(['error' => 'Designation not found.'], 404);
        }
    }

    public function index(): View
    {
        // CommonController::addToLog('designationView');
        $pers = DB::table('designations')->latest()->get();
        return view('admin.designation.index', compact('pers'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:designations,name']);
        
        try {
            $designation = Designation::create($request->all());
            // CommonController::addToLog('designationStore');
            Log::info('Designation created successfully.', ['id' => $designation->id, 'name' => $designation->name]);
            return redirect()->back()->with('success','Created successfully!');
        } catch (Exception $e) {
            Log::error('Failed to create designation: ' . $e);
            return redirect()->back()->with('error', 'Failed to create designation. Please check logs.');
        }
    }

   // --- MODIFIED UPDATE METHOD ---
    public function update(Request $request, $id): RedirectResponse // <-- MODIFIED RETURN TYPE
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:designations,name,' . $id]
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                         ->withErrors($validator, 'update') // <-- Use named error bag 'update'
                         ->withInput()
                         ->with('error_modal_id', $id); // <-- Add this to re-open the modal
        }

        try {
            $designation = Designation::findOrFail($id);
            $designation->update($request->all());
            // CommonController::addToLog('designationUpdate');
            Log::info('Designation updated successfully.', ['id' => $id, 'new_name' => $request->name]);
            
            // --- MODIFIED RESPONSE ---
            return redirect()->route('designation.index')->with('success', 'Designation updated successfully');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to update non-existent designation ID {$id}");
             // --- MODIFIED RESPONSE ---
             return redirect()->back()
                        ->withErrors(['error' => 'Designation not found.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        } catch (Exception $e) {
            Log::error("Failed to update designation ID {$id}: " . $e);
            // --- MODIFIED RESPONSE ---
             return redirect()->back()
                        ->withErrors(['error' => 'Failed to update designation.'], 'update')
                        ->withInput()
                        ->with('error_modal_id', $id);
        }
    }
    // --- END MODIFIED UPDATE METHOD ---


    // --- MODIFIED DESTROY METHOD ---
    public function destroy($id): RedirectResponse // <-- MODIFIED RETURN TYPE
    {
        try {
            $designation = Designation::findOrFail($id); // Find first
            $designation->delete();
            // CommonController::addToLog('designationDelete');
            Log::info('Designation deleted successfully.', ['id' => $id]);
            
            // --- MODIFIED RESPONSE ---
            return redirect()->route('designation.index')->with('success', 'Designation deleted successfully');
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::warning("Attempted to delete non-existent designation ID {$id}");
            // --- MODIFIED RESPONSE ---
            return redirect()->route('designation.index')->with('error', 'Designation not found.');

        } catch (Exception $e) {
            Log::error("Failed to delete designation ID {$id}: " . $e);
            // --- MODIFIED RESPONSE ---
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                  return redirect()->route('designation.index')->with('error', 'Cannot delete designation. It is associated with other records.');
             }
            return redirect()->route('designation.index')->with('error', 'Failed to delete designation.');
        }
    }
    // --- END MODIFIED DESTROY METHOD ---
}