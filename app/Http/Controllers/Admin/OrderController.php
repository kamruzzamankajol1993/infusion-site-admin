<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf; // Import mPDF

class OrderController extends Controller
{
    public function __construct()
    {
         $this->middleware('permission:orderView|orderUpdate|orderDelete', ['only' => ['index','data', 'show', 'printA4', 'printA5', 'printPOS']]);
         $this->middleware('permission:orderUpdate', ['only' => ['updateStatus', 'bulkUpdateStatus', 'storePayment']]);
         $this->middleware('permission:orderDelete', ['only' => ['destroy', 'destroyMultiple']]);
    }

    /**
     * Display the order list.
     */
    public function index(): View
    {
        return view('admin.order.index');
    }

    /**
     * AJAX Data for DataTables.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = Order::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%$search%")
                      ->orWhere('first_name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
                });
            }

            if ($request->filled('status') && $request->status != 'all') {
                $query->where('order_status', $request->status);
            }

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
            Log::error('Failed to fetch orders: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve orders.'], 500);
        }
    }

    /**
     * Show order details.
     */
    public function show($id): View
    {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.order.show', compact('order'));
    }

    /**
     * Update Order & Payment Status.
     */
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'order_status' => 'required|string',
            'payment_status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Bulk Update Status (Optional).
     */
    public function bulkUpdateStatus(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|string'
        ]);

        Order::whereIn('id', $request->ids)->update(['order_status' => $request->status]);

        return response()->json(['message' => 'Orders updated successfully.']);
    }

    /**
     * Record a manual payment (Optional).
     */
    public function storePayment(Request $request, Order $order): RedirectResponse
    {
        // Logic to store payment details manually if needed
        return redirect()->back()->with('success', 'Payment recorded.');
    }

    /**
     * Delete single order.
     */
    public function destroy($id): RedirectResponse
    {
        try {
            Order::findOrFail($id)->delete();
            return redirect()->route('order.index')->with('success', 'Order deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('order.index')->with('error', 'Failed to delete order.');
        }
    }

    /**
     * Delete multiple orders.
     */
    public function destroyMultiple(Request $request): JsonResponse
    {
        $request->validate(['ids' => 'required|array']);
        Order::whereIn('id', $request->ids)->delete();
        return response()->json(['message' => 'Orders deleted successfully.']);
    }

    // ==========================================
    // PRINTING FUNCTIONS (mPDF)
    // ==========================================

    /**
     * Print A4 Invoice.
     */
    public function printA4($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        // Render the view to HTML
        $html = view('admin.order.print_a4', compact('order'))->render();
        
        // Setup mPDF for A4
        $mpdf = new Mpdf([
            'mode' => 'utf-8', 
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'default_font' => 'dejavusans' // Supports unicode/currency symbols
        ]);

        $mpdf->WriteHTML($html);
        // Output to browser: 'I' = Inline (preview), 'D' = Download
        return $mpdf->Output('Invoice-' . $order->order_number . '.pdf', 'I');
    }

    /**
     * Print A5 Invoice (Half page).
     */
    public function printA5($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        $html = view('admin.order.print_a5', compact('order'))->render();
        
        // Setup mPDF for A5
        $mpdf = new Mpdf([
            'mode' => 'utf-8', 
            'format' => 'A5',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'default_font' => 'dejavusans'
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('Invoice-A5-' . $order->order_number . '.pdf', 'I');
    }

    /**
     * Print POS Receipt (Thermal Printer).
     */
    public function printPOS($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        $html = view('admin.order.print_pos', compact('order'))->render();
        
        // POS Setup: 80mm width, variable height (e.g. 5000mm max) to behave like a roll
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => [80, 5000], // Width 80mm, Height Auto (Roll)
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 2,
            'default_font' => 'dejavusans'
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('Receipt-' . $order->order_number . '.pdf', 'I');
    }
}