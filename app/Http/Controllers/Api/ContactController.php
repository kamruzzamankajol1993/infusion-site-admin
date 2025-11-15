<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactUsMessage; // <-- 1. Import your model
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator; // <-- 2. Import Validator
use Exception;

class ContactController extends Controller
{
    /**
     * Store a newly submitted contact message.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // 3. Define Validation Rules
        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobilenumber' => 'required|string|max:20', // Adjust max length as needed
            'message' => 'required|string|max:5000', // Adjust max length as needed
        ]);

        // 4. Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // 5. If validation passes, create the message
        try {
            ContactUsMessage::create($validator->validated()); // Use validated data

            return response()->json([
                'message' => 'Your message has been sent successfully!'
            ], 201); // 201 Created

        } catch (Exception $e) {
            Log::error('API: Failed to save contact message: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while sending your message. Please try again later.'
            ], 500); // 500 Internal Server Error
        }
    }
}