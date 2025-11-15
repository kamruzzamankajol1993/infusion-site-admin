<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobApplicant;
use App\Models\Career; // Needed for validation
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File; // Use File facade for direct public folder operations
use Exception;

class JobApplicantController extends Controller
{
    /**
     * Store a newly submitted job application from the API, saving CV to public folder.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'job_id' => [
                'required',
                'integer',
                // Ensure the job exists in the careers table
                Rule::exists('careers', 'id'),
            ],
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:50',
            // --- NEW/UPDATED FIELDS ---
            'date_of_birth' => 'nullable|date_format:Y-m-d', // Expect YYYY-MM-DD
            'educational_background' => 'nullable|string|max:65535', // Max text length
            'working_experience' => 'nullable|string|max:65535',
            'address' => 'nullable|string|max:65535',
            'total_year_of_experience' => 'nullable|string|max:100',
            'qualification' => 'required|string|max:255',
            'cv' => [
                'required',
                'file',
                'mimes:pdf,doc,docx', // Allowed file types
                'max:2048', // Max file size in kilobytes (2MB)
            ],
            'additional_information' => 'nullable|string',
        ]);

        // Return validation errors if they occur
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        $cvPathForDb = null; // Variable to hold the path to store in DB

        try {
            // Get validated data (excluding the file initially)
            $validatedData = $validator->validated();
            unset($validatedData['cv']); // Remove file from array before creating model

            // Handle CV file upload directly to public folder
            if ($request->hasFile('cv')) {
                $file = $request->file('cv');
                $directory = 'uploads/cvs'; // Path relative to the public directory
                $publicPath = public_path($directory); // Get the full server path to the public subdirectory

                // Ensure the directory exists
                if (!File::isDirectory($publicPath)) {
                    File::makeDirectory($publicPath, 0755, true, true);
                }

                // Generate a unique name
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Move the file directly to the public path
                $file->move($publicPath, $fileName);

                // Store the path relative to the public folder for the database
                // IMPORTANT: Ensure your model's accessor expects this format
                $cvPathForDb = $directory . '/' . $fileName;
                $validatedData['cv'] = $cvPathForDb;

            } else {
                 // Fallback, though validation should prevent this
                 return response()->json(['message' => 'CV file is required.'], 422);
            }

            // Create the job applicant record
            $applicant = JobApplicant::create($validatedData);

            // Return success response
            return response()->json([
                'message' => 'Application submitted successfully!',
                'data' => [
                    'applicant_id' => $applicant->id
                ]
            ], 201); // 201 Created

        } catch (Exception $e) {
            Log::error('API: Failed to store job application: ' . $e->getMessage());

            // Optionally attempt to delete the uploaded file if DB insertion fails
            if ($cvPathForDb && File::exists(public_path($cvPathForDb))) {
                 try {
                     File::delete(public_path($cvPathForDb));
                 } catch (Exception $deleteEx) {
                     Log::error('API: Failed to delete orphaned CV file after DB error: ' . $deleteEx->getMessage());
                 }
            }

            return response()->json([
                'message' => 'An error occurred while processing your application. Please try again later.'
            ], 500); // 500 Internal Server Error
        }
    }

    // Add other API methods here if needed (e.g., fetching user's applications)
}