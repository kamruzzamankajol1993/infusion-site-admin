<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function __construct()
    {
         // Define permissions in your seeder: customerView, customerAdd, customerUpdate, customerDelete
         $this->middleware('permission:customerView|customerAdd|customerUpdate|customerDelete', ['only' => ['index','data']]);
         $this->middleware('permission:customerAdd', ['only' => ['store']]);
         $this->middleware('permission:customerUpdate', ['only' => ['show', 'update']]);
         $this->middleware('permission:customerDelete', ['only' => ['destroy']]);
    }

    public function index(): View
    {
        return view('admin.customer.index');
    }

   public function data(Request $request): JsonResponse
    {
        try {
            // Filter only customers (user_type = 1)
            $query = User::where('user_type', 1);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Sort defaults
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $allowedSorts = ['id', 'name', 'email', 'phone', 'created_at'];
            
            if (in_array($sortColumn, $allowedSorts)) {
                $query->orderBy($sortColumn, $sortDirection);
            } else {
                $query->orderBy('id', 'desc');
            }

            $paginated = $query->paginate(10);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch customers: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve customers.'], 500);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'required|string|min:8', // Password required on create
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'password' => Hash::make($request->password),
                'user_type' => 1, // Force Customer Type
                'status' => 1,    // Default Active
            ]);

            return redirect()->route('customer.index')->with('success','Customer account created successfully!');

        } catch (Exception $e) {
            Log::error('Failed to create customer: ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create customer.']);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            // Ensure we only fetch customers
            $customer = User::where('user_type', 1)->findOrFail($id);
            return response()->json($customer);
        } catch (Exception $e) {
             return response()->json(['error' => 'Customer not found.'], 404);
        }
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            // Password is NOT validated here as it is hidden/not updated in this form
        ]);

        try {
            $customer = User::where('user_type', 1)->findOrFail($id);
            
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                // user_type remains 1
            ]);

            return redirect()->route('customer.index')->with('success', 'Customer details updated successfully');

        } catch (Exception $e) {
             return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update customer.']);
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $customer = User::where('user_type', 1)->findOrFail($id);
            $customer->delete();
            return redirect()->route('customer.index')->with('success', 'Customer account deleted successfully.');
        } catch (Exception $e) {
            return redirect()->route('customer.index')->with('error', 'Failed to delete customer.');
        }
    }
}