<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Project;       // Added
use App\Models\Officer;       // Added
use App\Models\Training;      // Added
use App\Models\Notice;        // Added
use Illuminate\Support\Facades\Log;
use Exception;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard with dynamic data.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        try {
            // --- Data for Summary Cards ---
            // Card 1: Active Projects (Ongoing)
            $ongoingProjectsCount = Project::where('status', 'ongoing')->count();

            // Card 2: Completed Projects
            $completedProjectsCount = Project::where('status', 'complete')->count();

            // Card 3: Team Experts (Total Officers)
            $officerCount = Officer::count();

            // Card 4: Total Trainings (Replaced Countries)
            $trainingCount = Training::count();


            // --- Data for Recent Projects Table ---
            $recentProjects = Project::with(['client', 'category']) // Eager load client and category
                ->latest() // Order by created_at desc
                ->limit(5)
                ->get();

            // --- Data for Training Calendar ---
            $upcomingTrainings = Training::where('start_date', '>=', Carbon::today())
                ->orderBy('start_date', 'asc')
                ->limit(3)
                ->get();

            // --- Data for Notice Board ---
            $recentNotices = Notice::with('category') // Eager load category
                ->latest() // Order by created_at desc
                ->limit(3)
                ->get();

            // --- Data for Projects Chart (Projects by Status) ---
            $projectsByStatus = Project::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status'); // Returns a collection like ['pending' => 5, 'ongoing' => 10, ...]

            $projectsByStatusChart = [
                'labels' => ['Pending', 'Ongoing', 'Complete'],
                'data' => [
                    $projectsByStatus->get('pending', 0),   // Get count for 'pending', default to 0
                    $projectsByStatus->get('ongoing', 0),   // Get count for 'ongoing', default to 0
                    $projectsByStatus->get('complete', 0),  // Get count for 'complete', default to 0
                ],
            ];

            // Pass all variables to the dashboard view
            return view('admin.dashboard.index', [
                'ongoingProjectsCount' => $ongoingProjectsCount,
                'completedProjectsCount' => $completedProjectsCount,
                'officerCount' => $officerCount,
                'trainingCount' => $trainingCount,
                'recentProjects' => $recentProjects,
                'upcomingTrainings' => $upcomingTrainings,
                'recentNotices' => $recentNotices,
                'projectsByStatusChart' => $projectsByStatusChart,
                // 'activeFilter' => 'month', // This filter from your old controller doesn't seem to be used
            ]);

        } catch (Exception $e) {
            Log::error('Failed to load dashboard data: ' . $e->getMessage());
            // Pass empty data on failure to prevent view from breaking
            return view('admin.dashboard.index', [
                'ongoingProjectsCount' => 0,
                'completedProjectsCount' => 0,
                'officerCount' => 0,
                'trainingCount' => 0,
                'recentProjects' => collect(),
                'upcomingTrainings' => collect(),
                'recentNotices' => collect(),
                'projectsByStatusChart' => [ 'labels' => ['Pending', 'Ongoing', 'Complete'], 'data' => [0, 0, 0] ],
            ])->with('error', 'Could not load all dashboard data.');
        }
    }
}