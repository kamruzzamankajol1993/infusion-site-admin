<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\SystemInformation;
use App\Models\TrainingDocument; // Import new model
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Exception;
use DateTime;
use DatePeriod;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TrainingController extends Controller
{

    /**
     * Securely downloads a specific training document.
     * This relies on Route Model Binding.
     *
     * @param TrainingDocument $document
     * @return BinaryFileResponse|JsonResponse
     */
    public function downloadDocument(TrainingDocument $document): BinaryFileResponse|JsonResponse
    {
        try {
            // The $document is already fetched by route model binding (e.g., /trainings/document/{document}/download)

            // Get the stored path (e.g., 'public/uploads/trainings/documents/doc_file_69068d624315a.pdf')
            $storedPath = $document->pdf_file;

            // --- THIS IS THE FIX ---
            // Remove the 'public/' prefix from the stored path.
            $relativePath = $storedPath;
            if (Str::startsWith($storedPath, 'public/')) {
                $relativePath = Str::after($storedPath, 'public/');
            } elseif (Str::startsWith($storedPath, '/public/')) {
                 $relativePath = Str::after($storedPath, '/public/');
            }
            
            // public_path() will now correctly resolve to: .../public/uploads/trainings/documents/doc_file_69068d624315a.pdf
            $filePath = public_path($relativePath);
            // --- END FIX ---

            //dd($filePath);

            // Check if the file actually exists on the server
            if (!File::exists($filePath)) {
                Log::error("API: Document file not found at path: {$filePath} for TrainingDocument ID {$document->id} (StoredPath: {$storedPath})");
                return response()->json(['error' => 'File not found on server.'], 404);
            }

            // Create a user-friendly filename
            $trainingTitle = $document->training->slug ?? 'training';
            $fileName = $trainingTitle . '-' . Str::slug($document->title) . '.pdf';

            // Force download
            return response()->download($filePath, $fileName);

        } catch (Exception $e) {
            Log::error("API: Failed to download TrainingDocument ID {$document->id}: " . $e->getMessage());
            return response()->json(['error' => 'Could not process download request.'], 500);
        }
    }


    /**
     * Get a list of all trainings (no pagination).
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();
            
            $trainings = Training::latest()->get();

            $formattedTrainings = $trainings->map(function ($training) use ($baseUrl) {
                
                $imageUrl = $this->getImageUrl($baseUrl, $training->image);
                $shortDescription = Str::limit(strip_tags($training->description), 150);

                return [
                    'id' => $training->id,
                    'title' => $training->title,
                    'slug' => $training->slug,
                    'short_description' => $shortDescription,
                    'start_date' => $training->start_date,
                    'end_date' => $training->end_date,
                    'training_fee' => $training->training_fee,
                    'image_url' => $imageUrl,
                ];
            });
            
            return response()->json([
                'data' => $formattedTrainings
            ]);

        } catch (Exception $e) {
            Log::error('API: Failed to fetch training list: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve trainings.'], 500);
        }
    }

    /**
     * Get the detailed information for a single training.
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $baseUrl = $this->getBaseUrl();

            $training = Training::where('slug', $slug)->firstOrFail();
            $training->load('skills', 'documents');

            $imageUrl = $this->getImageUrl($baseUrl, $training->image);

            $formattedData = [
                'id' => $training->id,
                'title' => $training->title,
                'slug' => $training->slug,
                'description' => $training->description,
                'start_date' => $training->start_date,
                'end_date' => $training->end_date,
                'training_fee' => $training->training_fee,
                'image_url' => $imageUrl,
                'skills' => $training->skills->pluck('skill_name'),
                'learn_from_training' => $training->learn_from_training,
                'who_should_attend' => $training->who_should_attend,
                'methodology' => $training->methodology,
                'training_time' => $training->training_time,
                'training_venue' => $training->training_venue,
                'deadline_for_registration' => $training->deadline_for_registration,
                'status' => $training->status,
                
                // Use the helper to build the full, absolute URL
                'documents' => $training->documents->map(function ($doc) use ($baseUrl) {
                    return [
                        'id' => $doc->id,
                        'title' => $doc->title,
                        'download_url' => $this->getDocumentUrl($baseUrl, $doc->pdf_file)
                    ];
                }),
            ];

            return response()->json(['data' => $formattedData]);

        } catch (Exception $e) {
            Log::error("API: Failed to fetch training detail for slug '{$slug}': " . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve training details.'], 500);
        }
    }

    /**
     * Get paginated list of trainings (for table view, etc.)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allTrainings(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 10);
            $searchTerm = $request->input('search', '');

            $query = \App\Models\Training::query();

            if (!empty($searchTerm)) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('status', 'like', '%' . $searchTerm . '%');
                });
            }

            $query->latest('start_date');
            $paginatedTrainings = $query->paginate($perPage);

            $baseUrl = $this->getBaseUrl();
            $paginatedTrainings->getCollection()->transform(function ($training) use ($baseUrl) {
                return [
                    'id'         => $training->id,
                    'title'      => $training->title,
                    'slug'       => $training->slug,
                    'start_date' => $training->start_date,
                    'end_date'   => $training->end_date,
                    'status'     => $training->status,
                ];
            });

            return response()->json($paginatedTrainings);

        } catch (\Exception $e) {
            Log::error('API: Failed to fetch all_trainings list: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve trainings.'], 500);
        }
    }

    /**
     * Get data for the calendar view.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function calendar(): \Illuminate\Http\JsonResponse
    {
        try {
            $trainings = \App\Models\Training::whereNotNull('start_date')
                                  ->orderBy('start_date')
                                  ->get(['id','slug', 'title', 'start_date', 'end_date']);

            $eventsByDate = [];

            foreach ($trainings as $training) {
                try {
                    $start = new \DateTime($training->start_date);
                    $end = null;
                    if (!empty($training->end_date) && $training->end_date >= $training->start_date) {
                        $end = (new \DateTime($training->end_date))->modify('+1 day');
                    } else {
                         $end = (new \DateTime($training->start_date))->modify('+1 day');
                    }

                    $interval = new \DateInterval('P1D');
                    $period = new \DatePeriod($start, $interval, $end);

                    foreach ($period as $date) {
                        $dateString = $date->format('Y-m-d');
                        if (!isset($eventsByDate[$dateString])) {
                            $eventsByDate[$dateString] = [];
                        }
                        $eventsByDate[$dateString][] = [
                            'id' => $training->id,
                            'title' => $training->title,
                            'slug' => $training->slug,
                        ];
                    }
                } catch (\Exception $dateError) {
                    Log::error("API: Error processing date for training ID {$training->id}: " . $dateError->getMessage());
                    continue;
                }
            }

            return response()->json($eventsByDate);

        } catch (\Exception $e) {
            Log::error('API: Failed to fetch training calendar data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve calendar data.'], 500);
        }
    }


    // --- Helper Methods ---

    /**
     * Helper to get the cached base URL.
     */
    private function getBaseUrl(): string
    {
        $baseUrl = Cache::remember('system_main_url', 3600, function () {
            // Make sure your SystemInformation model is imported or use full namespace
            return \App\Models\SystemInformation::first()?->value('main_url');
        });

        if (empty($baseUrl)) {
            $baseUrl = config('app.url');
        }
        return rtrim($baseUrl, '/');
    }

    /**
     * Helper to build a full image URL.
     */
    private function getImageUrl(string $baseUrl, ?string $imagePath): string
    {
        if (empty($imagePath)) {
            return $baseUrl . '/public/No_Image_Available.jpg';
        }
        return $baseUrl . '/' . ltrim($imagePath, '/');
    }

    /**
     * Helper to build a full document URL.
     */
    private function getDocumentUrl(string $baseUrl, ?string $docPath): ?string
    {
        if (empty($docPath)) {
            return null; // Return null if no document
        }
        // This creates the full, absolute URL that the front end needs
        return $baseUrl . '/' . ltrim($docPath, '/');
    }
}