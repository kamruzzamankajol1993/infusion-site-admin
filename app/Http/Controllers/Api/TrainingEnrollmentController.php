<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingEnrollment;
use App\Models\Training; // Needed for validation
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // Use Validator facade for API
use Illuminate\Validation\Rule;

class TrainingEnrollmentController extends Controller
{
    /**
     * Store a newly submitted training enrollment from the API.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'training_id' => [
                'required',
                'integer',
                // Ensure the training exists and is not 'complete'
                Rule::exists('trainings', 'id')->where(function ($query) {
                    $query->where('status', '!=', 'complete');
                }),
            ],
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
            'payment_method' => ['required', Rule::in(['cheque', 'cash'])], // Frontend should send 'cheque' or 'cash'
        ]);

        // Return validation errors if they occur
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // Get validated data
            $validatedData = $validator->validated();
            
            // Set default status for new API submissions
            $validatedData['status'] = 'pending'; 

            // Create the enrollment record
            $enrollment = TrainingEnrollment::create($validatedData);

            // Return success response
            return response()->json([
                'message' => 'Enrollment submitted successfully!',
                'data' => [ // Optionally return the created enrollment ID
                    'enrollment_id' => $enrollment->id
                ]
            ], 201); // 201 Created

        } catch (Exception $e) {
            Log::error('API: Failed to store training enrollment: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while processing your enrollment. Please try again later.'
            ], 500); // 500 Internal Server Error
        }
    }

    // Add other API methods here if needed (e.g., fetching user's enrollments)
}