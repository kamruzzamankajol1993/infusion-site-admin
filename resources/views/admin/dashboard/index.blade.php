@extends('admin.master.master')

@section('title')
Dashboard
@endsection

@section('css')
<style>
    .card-icon {
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        color: white;
    }
    .card-header {
        background-color: #fff;
        border-bottom: 1px solid #e9ecef;
    }
    .filter-btn.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .badge-status {
        padding: 0.4em 0.7em;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 0.25rem;
        text-transform: capitalize; /* Make status text capitalized */
    }
    /* Badge colors for project status */
    .badge-status.bg-warning { color: #664d03 !important; background-color: #fff3cd !important; }
    .badge-status.bg-info { color: #055160 !important; background-color: #cff4fc !important; }
    .badge-status.bg-success { color: #0a3622 !important; background-color: #d1e7dd !important; }

    /* Calendar Date Styles */
    .calendar-date {
        width: 50px;
        height: 50px;
        background-color: var(--secondary-color, #e9f5f0); /* Use fallback */
        color: var(--primary-color, #175A3A);
        flex-shrink: 0; /* Prevent shrinking */
    }
    .text-xs {
        font-size: 0.75rem; /* Small text for date */
    }
</style>
@endsection

@section('body')
   <div class="container-fluid px-4">
                {{-- Flash Message for Dashboard Errors --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Summary Cards Row --}}
                <div class="row g-4 my-3">
                    {{-- Active Projects --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-custom border-left-primary shadow-sm h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Active Projects</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $ongoingProjectsCount }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-kanban fs-2 text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Completed Projects --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-custom border-left-success shadow-sm h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Completed Projects</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $completedProjectsCount }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-check2-circle fs-2 text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Team Experts --}}
                    <div class="col-xl-3 col-md-6">
                        <div class="card card-custom border-left-info shadow-sm h-100">
                           <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Team Experts</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $officerCount }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-people fs-2 text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total Trainings (Replaced Countries) --}}
                    <div class="col-xl-3 col-md-6">
                         <div class="card card-custom border-left-warning shadow-sm h-100">
                           <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-warning text-uppercase mb-1">Total Trainings</div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">{{ $trainingCount }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="bi bi-journals fs-2 text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 my-5">
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0">Projects by Status</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="projectsChart"></canvas> {{-- ID matches script --}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Upcoming Trainings</h5>
                                {{-- Link to view all trainings --}}
                                @if(Auth::user()->can('trainingView'))
                                    <a href="{{ route('training.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                                @endif
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    {{-- Loop through upcoming trainings --}}
                                    @forelse($upcomingTrainings as $training)
                                    <li class="list-group-item d-flex align-items-center border-0 px-0">
                                        <div class="flex-shrink-0 text-center rounded-circle calendar-date d-flex flex-column justify-content-center me-3">
                                            <span class="fs-5 fw-bold lh-1">{{ date('d', strtotime($training->start_date)) }}</span>
                                            <span class="text-xs">{{ date('M', strtotime($training->start_date)) }}</span>
                                        </div>
                                        <div class="flex-grow-1">
                                            {{-- Link to the training's show page --}}
                                            <h6 class="mb-0 fw-bold">
                                                <a href="{{ route('training.show', $training->id) }}" class="text-decoration-none text-dark" title="{{ $training->title }}">
                                                    {{ Str::limit($training->title, 35) }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">{{ $training->time ?? 'All Day' }}</small>
                                        </div>
                                    </li>
                                    @empty
                                    <li class="list-group-item border-0 px-0 text-muted text-center">
                                        No upcoming trainings found.
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recent Projects Table --}}
                <div class="row my-5">
                    <div class="col">
                         <div class="card shadow-sm">
                             <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Recent Projects</h5>
                                 @if(Auth::user()->can('projectView'))
                                <a href="{{ route('project.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                                @endif
                            </div>
                            <div class="table-responsive">
                                <table class="table bg-white rounded table-hover mb-0">
                                    <thead class="table-light"> {{-- Use light header --}}
                                        <tr>
                                            <th scope="col" width="50">#</th>
                                            <th scope="col">Project Name</th>
                                            <th scope="col">Client</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Loop through recent projects --}}
                                        @forelse($recentProjects as $index => $project)
                                        <tr>
                                            <th scope="row">{{ $index + 1 }}</th>
                                            <td>
                                                <a href="{{ route('project.show', $project->id) }}" class="text-decoration-none text-dark fw-medium" title="{{ $project->title }}">
                                                    {{ Str::limit($project->title, 45) }}
                                                </a>
                                            </td>
                                            <td>{{ $project->client->name ?? 'N/A' }}</td>
                                            <td>{{ $project->category->name ?? 'N/A' }}</td>
                                            <td>
                                                {{-- Dynamic status badges --}}
                                                @if($project->status == 'complete')
                                                    <span class="badge-status bg-success">Complete</span>
                                                @elseif($project->status == 'ongoing')
                                                    <span class="badge-status bg-info">Ongoing</span>
                                                @else
                                                    <span class="badge-status bg-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">No recent projects found.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row my-5">
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Notice Board</h5>
                                 @if(Auth::user()->can('noticeView'))
                                <a href="{{ route('notice.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                                @endif
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    {{-- Loop through recent notices --}}
                                    @forelse($recentNotices as $notice)
                                    <a href="{{ $notice->pdf_file ? asset($notice->pdf_file) : route('notice.show', $notice->id) }}" target="_blank" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1 fw-bold">{{ $notice->title }}</h6>
                                            <small class="text-muted">{{ $notice->date ? date('d M, Y', strtotime($notice->date)) : $notice->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1 text-secondary">Category: {{ $notice->category->name ?? 'N/A' }}</p>
                                    </a>
                                    @empty
                                    <div class="list-group-item text-muted text-center py-3">
                                        No recent notices published.
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
@endsection

@section('script')
{{-- Script for initializing the Projects by Status Chart --}}
<script>
    // --- Chart.js Script for Projects by Status ---
    document.addEventListener("DOMContentLoaded", function() {
        const chartCanvas = document.getElementById('projectsChart');
        if (chartCanvas) {
            const ctx = chartCanvas.getContext('2d');
            
            // Get data passed from HomeController
            const chartData = @json($projectsByStatusChart); // Encodes PHP array to JSON

            const projectsChart = new Chart(ctx, {
                type: 'bar', // Bar chart
                data: {
                    labels: chartData.labels, // ['Pending', 'Ongoing', 'Complete']
                    datasets: [{
                        label: 'Projects',
                        data: chartData.data, // [count_pending, count_ongoing, count_complete]
                        backgroundColor: [
                            'rgba(246, 194, 62, 0.6)', // Warning (Pending)
                            'rgba(54, 185, 204, 0.6)', // Info (Ongoing)
                            'rgba(28, 200, 138, 0.6)'  // Success (Complete)
                        ],
                        borderColor: [
                            'rgba(246, 194, 62, 1)',
                            'rgba(54, 185, 204, 1)',
                            'rgba(28, 200, 138, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Allows chart to fit container height
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Ensure only whole numbers are shown on Y-axis (for counts)
                                stepSize: 1, // Suggest step size
                                precision: 0 // No decimal places
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false // Hide the legend
                        },
                        tooltip: {
                             callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        // Pluralize "Project" if count is not 1
                                        label += context.parsed.y + (context.parsed.y === 1 ? ' Project' : ' Projects');
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection