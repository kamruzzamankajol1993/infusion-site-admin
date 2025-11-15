@extends('admin.master.master')

@section('title')
View Training: {{ $training->title }} | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Summary Card Styles */
    .summary-card-image {
        width: 100%;
        max-height: 300px;
        object-fit: cover;
    }
    .summary-list .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start; /* Changed to flex-start for multi-line items */
        padding-top: 0.9rem;
        padding-bottom: 0.9rem;
    }
    .summary-list .list-group-item strong {
        text-align: right;
        color: var(--bs-dark);
        flex-shrink: 0; /* Prevents strong tag from shrinking */
        margin-left: 1rem; /* Add space */
    }
    .summary-list .list-group-item .text-muted {
        color: #6c757d !important;
        flex-shrink: 0;
    }
    .summary-list .list-group-item-docs {
        flex-direction: column; /* Stack label and links */
        align-items: flex-start;
    }
     .summary-list .list-group-item-docs .doc-links a {
        display: inline-block;
        margin-top: 0.5rem;
        margin-right: 0.5rem;
        padding: 0.25rem 0.5rem;
        border: 1px solid var(--primary-color);
        border-radius: 4px;
        color: var(--primary-color);
        text-decoration: none;
    }
     .summary-list .list-group-item-docs .doc-links a:hover {
        background-color: var(--primary-color);
        color: white;
    }

    /* Tab Content Styles */
    .tab-content .tab-pane {
        padding: 1.5rem 1rem;
    }
    .rich-content-wrapper {
        line-height: 1.7;
    }
    .rich-content-wrapper img { max-width: 100%; height: auto; border-radius: 5px; margin: 0.5rem 0; }
    .rich-content-wrapper ul, .rich-content-wrapper ol { padding-left: 1.5rem; margin-bottom: 1rem; }

    /* Skill List Styles */
    .skill-list li { padding: 0.4rem 0; border-bottom: 1px solid #f1f1f1; }
    .skill-list li:last-child { border-bottom: none; }
    .skill-list .icon { color: var(--primary-color); margin-right: 8px; width: 16px; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header: Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('training.index') }}">Trainings</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Details</li>
        </ol>
    </nav>

    {{-- Header: Title and Actions --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div class="d-flex align-items-center gap-3">
            <h2 class="mb-0 h3">{{ $training->title }}</h2>
            @php
                if ($training->status === 'complete') {
                    $statusBadgeClass = 'bg-success-soft text-success';
                } elseif ($training->status === 'running') {
                    $statusBadgeClass = 'bg-primary-soft text-primary';
                } elseif ($training->status === 'postponed') {
                    $statusBadgeClass = 'bg-danger-soft text-danger';
                } else { // upcoming
                    $statusBadgeClass = 'bg-warning-soft text-warning';
                }
            @endphp
            <span class="badge {{ $statusBadgeClass }} py-2 px-3 fs-6">{{ ucfirst($training->status) }}</span>
        </div>
        <div>
             @if (Auth::user()->can('trainingUpdate'))
            <a href="{{ route('training.edit', $training->id) }}" class="btn btn-primary">
                <i data-feather="edit-2" class="me-1" style="width:16px;"></i> Edit Training
            </a>
            @endif
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="row g-4">
        
        {{-- Left Column (Main Content Tabs) --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header p-0 bg-white border-bottom-0">
                    {{-- Navigation Tabs --}}
                    <ul class="nav nav-tabs nav-tabs-card" id="trainingTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab" aria-controls="description" aria-selected="true">
                                <i data-feather="align-left" class="me-1" style="width:16px;"></i> Description
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="learn-tab" data-bs-toggle="tab" data-bs-target="#learn" type="button" role="tab" aria-controls="learn" aria-selected="false">
                                <i data-feather="star" class="me-1" style="width:16px;"></i> What You'll Learn
                            </button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link" id="attend-tab" data-bs-toggle="tab" data-bs-target="#attend" type="button" role="tab" aria-controls="attend" aria-selected="false">
                                <i data-feather="users" class="me-1" style="width:16px;"></i> Who Should Attend
                            </button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link" id="methodology-tab" data-bs-toggle="tab" data-bs-target="#methodology" type="button" role="tab" aria-controls="methodology" aria-selected="false">
                                <i data-feather="clipboard" class="me-1" style="width:16px;"></i> Methodology
                            </button>
                        </li>
                        @if($training->skills->isNotEmpty())
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills" type="button" role="tab" aria-controls="skills" aria-selected="false">
                                <i data-feather="check-square" class="me-1" style="width:16px;"></i> Skills
                            </button>
                        </li>
                        @endif
                    </ul>
                </div>

                {{-- Tab Content --}}
                <div class="card-body p-0">
                    <div class="tab-content" id="trainingTabContent">
                        
                        {{-- Description Pane --}}
                        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                            <h5 class="mb-3">Training Description</h5>
                            <div class="rich-content-wrapper">
                                @if($training->description)
                                    {!! $training->description !!}
                                @else
                                    <p class="text-muted">No description provided.</p>
                                @endif
                            </div>
                        </div>

                        {{-- What You'll Learn Pane --}}
                        <div class="tab-pane fade" id="learn" role="tabpanel" aria-labelledby="learn-tab">
                             <h5 class="mb-3">What You'll Learn</h5>
                             <div class="rich-content-wrapper">
                                @if($training->learn_from_training)
                                     {!! $training->learn_from_training !!}
                                @else
                                    <p class="text-muted">No details provided.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Who Should Attend Pane --}}
                        <div class="tab-pane fade" id="attend" role="tabpanel" aria-labelledby="attend-tab">
                             <h5 class="mb-3">Who Should Attend</h5>
                             <div class="rich-content-wrapper">
                                @if($training->who_should_attend)
                                     {!! $training->who_should_attend !!}
                                @else
                                    <p class="text-muted">No details provided.</p>
                                @endif
                            </div>
                        </div>

                         {{-- Methodology Pane --}}
                        <div class="tab-pane fade" id="methodology" role="tabpanel" aria-labelledby="methodology-tab">
                             <h5 class="mb-3">Methodology</h5>
                             <div class="rich-content-wrapper">
                                @if($training->methodology)
                                     {!! $training->methodology !!}
                                @else
                                    <p class="text-muted">No details provided.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Skills Pane --}}
                        @if($training->skills->isNotEmpty())
                        <div class="tab-pane fade" id="skills" role="tabpanel" aria-labelledby="skills-tab">
                            <h5 class="mb-3">Skills Covered</h5>
                            <ul class="list-unstyled skill-list mb-0">
                                @foreach($training->skills as $skill)
                                    <li class="d-flex align-items-center">
                                        <i data-feather="check" class="icon flex-shrink-0"></i>
                                        <span>{{ $skill->skill_name }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column (Summary) --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                {{-- Image --}}
                @if($training->image)
                    <img src="{{ asset($training->image) }}" alt="{{ $training->title }}" class="summary-card-image">
                @else
                    <div class="card-header bg-light text-center p-5">
                        <span class="text-muted">No image available</span>
                    </div>
                @endif
                
                {{-- Key Details --}}
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush summary-list">
                        {{-- Category Removed --}}
                        
                        <li class="list-group-item">
                            <span class="text-muted">Start Date</span>
                            <strong>
                                @if($training->start_date)
                                    {{ $training->start_date->format('d M, Y') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </strong>
                        </li>
                        <li class="list-group-item">
                            <span class="text-muted">End Date</span>
                            <strong>
                                @if($training->end_date)
                                    {{ $training->end_date->format('d M, Y') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </strong>
                        </li>
                         <li class="list-group-item">
                            <span class="text-muted">Registration Deadline</span>
                            <strong>
                                @if($training->deadline_for_registration)
                                    {{ $training->deadline_for_registration->format('d M, Y') }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </strong>
                        </li>
                         <li class="list-group-item">
                            <span class="text-muted">Time</span>
                            <strong>{{ $training->training_time ?? 'N/A' }}</strong>
                        </li>
                         <li class="list-group-item">
                            <span class="text-muted">Venue</span>
                            <strong>{{ $training->training_venue ?? 'N/A' }}</strong>
                        </li>
                        <li class="list-group-item">
                            <span class="text-muted">Training Fee</span>
                            <strong>
                                @if($training->training_fee !== null)
                                    {{ number_format($training->training_fee, 2) }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </strong>
                        </li>

                       {{-- === MODIFIED DOCUMENTS SECTION === --}}
                        <li class="list-group-item list-group-item-docs">
                            <span class="text-muted">Documents</span>
                            @if($training->documents->isNotEmpty())
                                <div class="doc-links">
                                    @foreach($training->documents as $doc)
                                    <a href="{{ asset($doc->pdf_file) }}" target="_blank">
                                        <i data-feather="file-text" style="width:14px;"></i> {{ $doc->title }}
                                    </a> 
                                    @endforeach
                                </div>
                            @else
                                <strong><span class="text-muted">N/A</span></strong>
                            @endif
                        </li>
                        {{-- === END MODIFIED SECTION === --}}
                    </ul>
                </div>
                
                {{-- Meta Info Footer --}}
                <div class="card-footer bg-light text-center small text-muted">
                    <p class="mb-1"><strong>Created:</strong> {{ $training->created_at->format('d M, Y H:i A') }}</p>
                    <p class="mb-0"><strong>Last Updated:</strong> {{ $training->updated_at->format('d M, Y H:i A') }}</p>
                </div>
            </div>
        </div>

    </div> {{-- End .row --}}

</div>
@endsection

@section('script')
    <script>
         try { feather.replace() } catch (e) {} // For icons
    </script>
@endsection