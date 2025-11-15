<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use App\Exports\SupplierExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Exception;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return view('admin.supplier.index');
        } catch (Exception $e) {
            Log::error('Failed to load supplier index page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the supplier page.');
        }
    }

    /**
     * Fetch data for the index page via AJAX.
     */
    public function data(Request $request)
    {
        try {
            $query = Supplier::query();

            if ($request->filled('search')) {
                $query->where('company_name', 'like', $request->search . '%')
                    ->orWhere('contact_person', 'like', $request->search . '%')
                    ->orWhere('email', 'like', $request->search . '%')
                    ->orWhere('phone', 'like', $request->search . '%');
            }

            $query->orderBy($request->get('sort', 'id'), $request->get('direction', 'desc'));
            $suppliers = $query->paginate(10);

            return response()->json([
                'data' => $suppliers->items(),
                'total' => $suppliers->total(),
                'current_page' => $suppliers->currentPage(),
                'last_page' => $suppliers->lastPage(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to fetch supplier data via AJAX: ' . $e);
            return response()->json(['error' => 'Failed to fetch supplier data.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('admin.supplier.create');
        } catch (Exception $e) {
            Log::error('Failed to load create supplier page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the page.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:suppliers'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:suppliers'],
            'address' => ['nullable', 'string'],
            'vat_number' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $supplier = Supplier::create($request->all());
            Log::info('Supplier created successfully.', ['supplier_id' => $supplier->id]);
            return redirect()->route('supplier.index')->with('success', 'Supplier created successfully.');
        } catch (Exception $e) {
            Log::error('Failed to create supplier: ' . $e);
            return redirect()->back()->with('error', 'Failed to create supplier. Please check the logs.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        try {
            return view('admin.supplier.show', compact('supplier'));
        } catch (Exception $e) {
            Log::error("Failed to show supplier ID {$supplier->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load supplier details.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        try {
            return view('admin.supplier.edit', compact('supplier'));
        } catch (Exception $e) {
            Log::error("Failed to load edit page for supplier ID {$supplier->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the edit page.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:suppliers,phone,' . $supplier->id],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:suppliers,email,' . $supplier->id],
            'address' => ['nullable', 'string'],
            'vat_number' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $supplier->update($request->all());
            Log::info('Supplier updated successfully.', ['supplier_id' => $supplier->id]);
            return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
        } catch (Exception $e) {
            Log::error("Failed to update supplier ID {$supplier->id}: " . $e);
            return redirect()->back()->with('error', 'Failed to update supplier. Please check the logs.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier->delete();
            Log::info('Supplier deleted successfully.', ['supplier_id' => $supplier->id]);
            return response()->json(['message' => 'Supplier deleted successfully.']);
        } catch (Exception $e) {
            Log::error("Failed to delete supplier ID {$supplier->id}: " . $e);
            return response()->json(['message' => 'Failed to delete supplier.'], 500);
        }
    }
    
    /**
     * Export supplier list to PDF.
     */
    public function exportPdf()
    {
        try {
            $suppliers = Supplier::latest()->get();
            $html = view('admin.supplier._partial.pdfSheet', compact('suppliers'))->render();
            $mpdf = new Mpdf();
            $mpdf->WriteHTML($html);
            return $mpdf->Output('supplier-list.pdf', 'D');
        } catch (Exception $e) {
            Log::error('Failed to export suppliers to PDF: ' . $e);
            return redirect()->back()->with('error', 'Could not export PDF. Please check the logs.');
        }
    }

    /**
     * Export supplier list to Excel.
     */
    public function exportExcel()
    {
        try {
            return Excel::download(new SupplierExport, 'suppliers.xlsx');
        } catch (Exception $e) {
            Log::error('Failed to export suppliers to Excel: ' . $e);
            return redirect()->back()->with('error', 'Could not export Excel file. Please check the logs.');
        }
    }
}