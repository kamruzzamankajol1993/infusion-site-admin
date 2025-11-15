<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingEnrollment;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Exception;

class TrainingEnrollmentController extends Controller
{
    public function __construct()
    {
        // Add permissions as needed
        $this->middleware('permission:trainingEnrollmentView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:trainingEnrollmentAdd', ['only' => ['create', 'store']]);
        $this->middleware('permission:trainingEnrollmentUpdate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:trainingEnrollmentDelete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin.trainingEnrollment.index');
    }

    /**
     * Get data for the datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = TrainingEnrollment::with('training');

            // Search
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('mobile', 'like', '%' . $searchTerm . '%')
                      ->orWhere('status', 'like', '%' . $searchTerm . '%')
                      ->orWhereHas('training', function ($subq) use ($searchTerm) {
                          $subq->where('title', 'like', '%' . $searchTerm . '%');
                      });
                });
            }

            // Sorting
            $sortColumn = $request->input('sort', 'id');
            $sortDirection = $request->input('direction', 'desc');
            $query->orderBy($sortColumn, $sortDirection);

            $paginated = $query->paginate(10);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch enrollment data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve data.'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $trainings = Training::where('status', '!=', 'complete')->orderBy('title')->get();
        return view('admin.trainingEnrollment.create', compact('trainings'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'highest_degree' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:50',
            'telephone' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'payment_method' => ['required', Rule::in(['cheque', 'cash'])],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled'])],
        ]);

        try {
            TrainingEnrollment::create($validated);
            return redirect()->route('trainingEnrollment.index')->with('success', 'Enrollment created successfully.');
        } catch (Exception $e) {
            Log::error('Failed to create enrollment: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create enrollment.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TrainingEnrollment $trainingEnrollment): View
    {
        $trainingEnrollment->load('training');
        return view('admin.trainingEnrollment.show', compact('trainingEnrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrainingEnrollment $trainingEnrollment): View
    {
        $trainings = Training::orderBy('title')->get();
        return view('admin.trainingEnrollment.edit', compact('trainingEnrollment', 'trainings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TrainingEnrollment $trainingEnrollment): RedirectResponse
    {
        $validated = $request->validate([
            'training_id' => 'required|exists:trainings,id',
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'organization' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'highest_degree' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:50',
            'telephone' => 'nullable|string|max:50',
            'fax' => 'nullable|string|max:50',
            'payment_method' => ['required', Rule::in(['cheque', 'cash'])],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled'])],
        ]);

        try {
            $trainingEnrollment->update($validated);
            return redirect()->route('trainingEnrollment.index')->with('success', 'Enrollment updated successfully.');
        } catch (Exception $e) {
            Log::error('Failed to update enrollment: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update enrollment.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(TrainingEnrollment $trainingEnrollment): RedirectResponse
    {
        try {
            // No files associated with enrollment, so just delete the record
            $trainingEnrollment->delete();

            // --- MODIFIED RESPONSE ---
            return redirect()->route('trainingEnrollment.index')->with('success', 'Enrollment deleted successfully.');

        } catch (Exception $e) {
            Log::error("Failed to delete enrollment ID {$trainingEnrollment->id}: " . $e->getMessage());
            // --- MODIFIED RESPONSE ---
            return redirect()->route('trainingEnrollment.index')->with('error', 'Failed to delete enrollment.');
        }
    }
}