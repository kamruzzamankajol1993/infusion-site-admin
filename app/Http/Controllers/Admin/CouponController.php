<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function __construct()
    {
         $this->middleware('permission:couponView|couponAdd|couponUpdate|couponDelete', ['only' => ['index','data']]);
         $this->middleware('permission:couponAdd', ['only' => ['store']]);
         $this->middleware('permission:couponUpdate', ['only' => ['show', 'update']]);
         $this->middleware('permission:couponDelete', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('admin.coupon.index');
    }

    public function data(Request $request): JsonResponse
    {
        try {
            $query = Coupon::query();

            if ($request->filled('search')) {
                $query->where('code', 'like', '%' . $request->search . '%');
            }

            // Sort by ID desc by default
            $query->orderBy('id', 'desc');
            
            $paginated = $query->paginate(10);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch coupons: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'amount' => 'required|numeric|min:0',
            'expire_date' => 'nullable|date',
            'status' => 'required|boolean',
        ]);

        try {
            // Convert code to uppercase
            $data = $request->all();
            $data['code'] = strtoupper($request->code);
            
            Coupon::create($data);
            return redirect()->route('coupon.index')->with('success', 'Coupon created successfully!');

        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create coupon.']);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            return response()->json(Coupon::findOrFail($id));
        } catch (Exception $e) {
            return response()->json(['error' => 'Coupon not found.'], 404);
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($id)],
            'type' => 'required|in:fixed,percent',
            'amount' => 'required|numeric|min:0',
            'expire_date' => 'nullable|date',
            'status' => 'required|boolean',
        ]);

        try {
            $coupon = Coupon::findOrFail($id);
            $data = $request->all();
            $data['code'] = strtoupper($request->code);
            
            $coupon->update($data);
            return redirect()->route('coupon.index')->with('success', 'Coupon updated successfully');

        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update coupon.']);
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            Coupon::findOrFail($id)->delete();
            return redirect()->route('coupon.index')->with('success', 'Coupon deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('coupon.index')->with('error', 'Failed to delete coupon.');
        }
    }
}