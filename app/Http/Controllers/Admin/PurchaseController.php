<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return view('admin.purchase.index');
        } catch (Exception $e) {
            Log::error('Failed to load purchase index page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the purchase page.');
        }
    }

    /**
     * Fetch data for the index page via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        try {
            $query = Purchase::with('supplier');

            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where('purchase_no', 'like', $searchTerm . '%')
                    ->orWhereHas('supplier', function ($q) use ($searchTerm) {
                        $q->where('company_name', 'like', $searchTerm . '%');
                    });
            }

            $query->orderBy($request->get('sort', 'id'), $request->get('direction', 'desc'));
            $purchases = $query->paginate(10);

            return response()->json([
                'data' => $purchases->items(),
                'total' => $purchases->total(),
                'current_page' => $purchases->currentPage(),
                'last_page' => $purchases->lastPage(),
            ]);
        } catch (Exception $e) {
            Log::error('Failed to fetch purchase data via AJAX: ' . $e);
            return response()->json(['error' => 'Failed to fetch purchase data.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $suppliers = Supplier::where('status', true)->get();
            $products = Product::where('status', true)->select('id', 'name', 'buying_price')->get();
            return view('admin.purchase.create', compact('suppliers', 'products'));
        } catch (Exception $e) {
            Log::error('Failed to load create purchase page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the create purchase page.');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_cost' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'purchase_no' => 'PR-' . time(),
                'purchase_date' => $request->purchase_date,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount,
                'shipping_cost' => $request->shipping_cost,
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'due_amount' => $request->total_amount - $request->paid_amount,
                'payment_status' => ($request->total_amount - $request->paid_amount) <= 0 ? 'paid' : 'due',
                'notes' => $request->notes,
                'created_by' => Auth::id(),
            ]);

            foreach ($request->products as $item) {
                 $purchase->purchaseDetails()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $item['quantity'] * $item['unit_cost'],
                ]);
                $product = Product::find($item['product_id']);
                $stock = $product->stock()->firstOrCreate([], ['quantity' => 0]);
                $previousQuantity = $stock->quantity;
                $newQuantity = $previousQuantity + $item['quantity'];
                $stock->update(['quantity' => $newQuantity]);
                StockHistory::create([
                    'product_id' => $item['product_id'],
                    'previous_quantity' => $previousQuantity,
                    'new_quantity' => $newQuantity,
                    'quantity_change' => $item['quantity'],
                    'type' => 'purchase',
                    'notes' => 'Purchase #' . $purchase->purchase_no,
                    'user_id' => Auth::id(),
                ]);
            }

            DB::commit();
            
            Log::info('Purchase created successfully.', ['purchase_id' => $purchase->id, 'user_id' => Auth::id()]);

            return redirect()->route('purchase.index')->with('success', 'Purchase created successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create purchase: ' . $e);
            
            return redirect()->back()->with('error', 'Failed to create purchase. Please check the logs.')->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    
public function show(Purchase $purchase)
{
    try {
        // MODIFIED: Eager load the new 'payments' relationship and the user who recorded it
        $purchase->load('supplier', 'purchaseDetails.product', 'payments.user');
        return view('admin.purchase.show', compact('purchase'));
    } catch (Exception $e) {
        Log::error("Failed to show purchase details for ID {$purchase->id}: " . $e->getMessage());
        return redirect()->back()->with('error', 'Could not load purchase details.');
    }
}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        try {
            $purchase->load('purchaseDetails.product');
            $suppliers = Supplier::where('status', true)->get();
            $products = Product::where('status', true)->select('id', 'name','buying_price')->get();
            return view('admin.purchase.edit', compact('purchase', 'suppliers', 'products'));
        } catch (Exception $e) {
            Log::error("Failed to load edit page for purchase ID {$purchase->id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Could not load the edit purchase page.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_date' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_cost' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            foreach ($purchase->purchaseDetails as $detail) {
                $product = Product::find($detail->product_id);
                if ($product && $product->stock) {
                    $stock = $product->stock;
                    $previousQuantity = $stock->quantity;
                    $newQuantity = $previousQuantity - $detail->quantity;
                    $stock->update(['quantity' => $newQuantity]);

                    StockHistory::create([
                        'product_id' => $detail->product_id,
                        'previous_quantity' => $previousQuantity,
                        'new_quantity' => $newQuantity,
                        'quantity_change' => -$detail->quantity,
                        'type' => 'purchase_update_revert',
                        'notes' => 'Reverting Purchase #' . $purchase->purchase_no,
                        'user_id' => Auth::id(),
                    ]);
                }
            }
            $purchase->purchaseDetails()->delete();
            foreach ($request->products as $item) {
                $purchase->purchaseDetails()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'total_cost' => $item['quantity'] * $item['unit_cost'],
                ]);
                $product = Product::find($item['product_id']);
                $stock = $product->stock()->firstOrCreate([], ['quantity' => 0]);
                $previousQuantity = $stock->quantity;
                $newQuantity = $previousQuantity + $item['quantity'];
                $stock->update(['quantity' => $newQuantity]);
                StockHistory::create([
                    'product_id' => $item['product_id'],
                    'previous_quantity' => $previousQuantity,
                    'new_quantity' => $newQuantity,
                    'quantity_change' => $item['quantity'],
                    'type' => 'purchase_update',
                    'notes' => 'Updating Purchase #' . $purchase->purchase_no,
                    'user_id' => Auth::id(),
                ]);
            }
            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $request->purchase_date,
                'subtotal' => $request->subtotal,
                'discount' => $request->discount,
                'shipping_cost' => $request->shipping_cost,
                'total_amount' => $request->total_amount,
                'paid_amount' => $request->paid_amount,
                'due_amount' => $request->total_amount - $request->paid_amount,
                'payment_status' => ($request->total_amount - $request->paid_amount) <= 0 ? 'paid' : 'due',
                'notes' => $request->notes,
            ]);

            DB::commit();

            Log::info('Purchase updated successfully.', ['purchase_id' => $purchase->id, 'user_id' => Auth::id()]);
            
            return redirect()->route('purchase.index')->with('success', 'Purchase updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();

            Log::error("Failed to update purchase ID {$purchase->id}: " . $e);
            
            return redirect()->back()->with('error', 'Failed to update purchase. Please check the logs.')->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Purchase $purchase)
    {
        try {
            DB::beginTransaction();
            
            foreach($purchase->purchaseDetails as $detail) {
                $product = Product::find($detail->product_id);
                if ($product && $product->stock) {
                    $product->stock->decrement('quantity', $detail->quantity);
                }
            }

            $purchase->delete();
            DB::commit();

            Log::info('Purchase deleted successfully.', ['purchase_id' => $purchase->id, 'user_id' => Auth::id()]);

            return response()->json(['message' => 'Purchase deleted and stock reverted successfully.']);

        } catch (Exception $e) {
            DB::rollBack();

            Log::error("Failed to delete purchase ID {$purchase->id}: " . $e);

            return response()->json(['message' => 'Failed to delete purchase. Please check the logs.'], 500);
        }
    }


     public function addPayment(Request $request, Purchase $purchase)
    {
        // Validate the request. The amount cannot be more than the due amount.
        $request->validate([
            'amount' => 'required|numeric|gt:0|max:' . $purchase->due_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // 1. Record the new payment
            $purchase->payments()->create([
                'user_id' => Auth::id(),
                'payment_date' => $request->payment_date,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            // 2. Update the parent purchase record
            $purchase->paid_amount += $request->amount;
            $purchase->due_amount -= $request->amount;

            // 3. Update the payment status
            if ($purchase->due_amount <= 0) {
                $purchase->payment_status = 'paid';
            } else {
                $purchase->payment_status = 'partial';
            }
            
            $purchase->save();

            DB::commit();

            Log::info('Payment recorded for purchase.', ['purchase_id' => $purchase->id, 'amount' => $request->amount, 'user_id' => Auth::id()]);

            return redirect()->route('purchase.show', $purchase->id)->with('success', 'Payment recorded successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to record payment for purchase ID {$purchase->id}: " . $e);
            return redirect()->route('purchase.show', $purchase->id)->with('error', 'Failed to record payment. Please check the logs.');
        }
    }
}