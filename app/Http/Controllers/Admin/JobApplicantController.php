<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplicant; //
use App\Models\Career;       //
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File; //
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Http\Response;
// --- NEW IMPORTS for Export ---
use Maatwebsite\Excel\Facades\Excel; // Assuming Maatwebsite package
use App\Exports\JobApplicantsExport; // You must create this class
use Mpdf\Mpdf;                      // Assuming direct MPDF usage
use Carbon\Carbon;                   // For filename timestamp
// -----------------------------
class JobApplicantController extends Controller
{
    /**
     * Permissions middleware.
     */
    public function __construct()
    {
        // Adjust permission names as needed
        $this->middleware('permission:jobApplicantView', ['only' => ['index', 'data', 'show']]);
        $this->middleware('permission:jobApplicantDelete', ['only' => ['destroy']]);
        // No Add/Update permissions needed based on request
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Fetch job titles for the filtering dropdown
        $jobTitles = Career::orderBy('title')->pluck('title', 'id');
        return view('admin.job_applicant.index', compact('jobTitles')); //
    }

    /**
     * Helper method to get the query based on filter.
     */
    private function getApplicantQuery(Request $request)
    {
        $query = JobApplicant::with('career');

        // Filtering by Job ID
        $jobFilter = $request->input('job_filter', 'all');
        if ($jobFilter !== 'all' && $jobFilter) {
            $query->where('job_id', $jobFilter);
        }

        // Always order by newest first for reports
        return $query->latest('created_at');
    }

    /**
     * Export job applicants data to Excel.
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $applicants = $this->getApplicantQuery($request)->get();
        $fileName = 'job_applicants_' . Carbon::now()->format('Ymd_His') . '.xlsx';

        // Maatwebsite/Laravel Excel Usage
        return Excel::download(new JobApplicantsExport($applicants), $fileName);
    }

    /**
     * Export job applicants data to PDF using MPDF.
     * Note: This method's return type is updated to include Response to handle MPDF output.
     */
    public function exportPdf(Request $request): BinaryFileResponse|JsonResponse|Response
    {
        try {
            $applicants = $this->getApplicantQuery($request)->get();
            
            // Get filter title for PDF header
            $filterId = $request->input('job_filter', 'all');
            $filterName = ($filterId !== 'all' && $filterId) 
                            ? Career::find($filterId)?->title ?? 'Filtered Applicants' 
                            : 'All Applicants';

            // 1. Render the HTML view (Make sure 'admin.job_applicant.pdf_report' exists)
            $html = view('admin.job_applicant.pdf_report', compact('applicants', 'filterName'))->render();

            // 2. Initialize MPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'default_font_size' => 10,
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 30, // Space for header
                'margin_bottom' => 20,
                'margin_header' => 10,
                'margin_footer' => 10,
            ]);

            // Set Header
            $mpdf->SetHTMLHeader('
                <div style="text-align: center; font-size: 14px; font-weight: bold;">Job Applicant Report</div>
                <div style="text-align: center; font-size: 12px; margin-top: 5px;">Filter: ' . $filterName . '</div>
                <hr>
            ');

            // Write HTML content
            $mpdf->WriteHTML($html);

            $fileName = 'job_applicants_' . Carbon::now()->format('Ymd_His') . '.pdf';

            // 3. Output the PDF for download (Returns Illuminate\Http\Response)
            return response($mpdf->Output($fileName, 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        } catch (Exception $e) {
            Log::error('Job applicant PDF export failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to generate PDF report.'], 500);
        }
    }

    /**
     * Process AJAX request for datatable.
     */
    public function data(Request $request): JsonResponse
    {
        try {
            $query = JobApplicant::with('career'); // Eager load the related job posting

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('full_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('email', 'like', '%' . $searchTerm . '%')
                      ->orWhere('phone_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('total_year_of_experience', 'like', '%' . $searchTerm . '%') 
                      ->orWhereHas('career', fn($cq) => $cq->where('title', 'like', '%' . $searchTerm . '%')); // Search job title
                });
            }

            // Optional Filtering by Job ID (if you add a filter dropdown in index.blade.php)
            if ($request->filled('job_filter') && $request->job_filter !== 'all') {
                $query->where('job_id', $request->job_filter);
            }


            // Sorting functionality
            $sortColumn = $request->input('sort', 'created_at'); // Default sort: application date
            $sortDirection = $request->input('direction', 'desc'); // Newest first
            $allowedSorts = ['id', 'full_name', 'email', 'phone_number', 'created_at','total_year_of_experience'];
             // Add sorting by related 'careers.title' if needed (requires join)
            if (in_array($sortColumn, $allowedSorts)) {
                 $query->orderBy($sortColumn, $sortDirection);
            } else if ($sortColumn === 'job_title') {
                 // Example sorting by related model requires join
                 $query->join('careers', 'job_applicants.job_id', '=', 'careers.id')
                       ->orderBy('careers.title', $sortDirection)
                       ->select('job_applicants.*'); // Select only applicant columns after join
            }
             else {
                 $query->orderBy('created_at', 'desc'); // Fallback sort
            }

            $paginated = $query->paginate(10); // Adjust page size

            // Add cv_url using accessor
            $paginated->getCollection()->transform(fn($item) => $item);

            return response()->json([
                'data' => $paginated->items(),
                'total' => $paginated->total(),
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
            ]);

        } catch (Exception $e) {
            Log::error('Failed to fetch job applicants: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve applicants.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JobApplicant $jobApplicant): View
    {
         // Eager load the related career post
         $jobApplicant->load('career');
         return view('admin.job_applicant.show', compact('jobApplicant'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplicant $jobApplicant): JsonResponse
    {
        try {
            DB::beginTransaction();

            // Delete associated CV file from public path
            if ($jobApplicant->cv && File::exists(public_path($jobApplicant->cv))) {
                File::delete(public_path($jobApplicant->cv));
            }

            $jobApplicant->delete();
            DB::commit();

            return response()->json(['message' => 'Job applicant deleted successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete job applicant ID {$jobApplicant->id}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete applicant.'], 500);
        }
    }

     // No create, store, edit, update methods needed per request
}