@extends('admin.master.master')

@section('title')
View Project: {{ $project->title }} | {{ $ins_name }}
@endsection

@section('css')
{{-- NEW: Lightbox CSS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" integrity="sha512-ZKX+BvQihOozcJGTqsRXbBfR/fbVPFnmhitNPqdF/PQDZeJSKZRktk3ptPGmLjKFYUulPBhwdSNoCJQGjPYsiA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<style>
    /* Gallery Styles (from original) */
    .project-gallery-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    .project-gallery-item img {
        width: 130px; 
        height: 130px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #dee2e6;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .project-gallery-item img:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .lightbox-trigger { cursor: pointer; }

    /* Project Summary Card */
    .summary-list .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    }
    .summary-list .list-group-item strong {
        text-align: right;
        color: var(--bs-dark);
    }
    .summary-list .list-group-item .text-muted {
        color: #6c757d !important;
    }

    /* Project Description Content */
    .project-description-content {
        line-height: 1.7;
    }
    .project-description-content img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    /* NEW: Lightbox Style Tweaks (Optional) */
    .lb-data .lb-caption { font-size: 1rem; }
    .lb-data .lb-number { font-size: 0.9rem; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header: Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Details</li>
        </ol>
    </nav>

    {{-- Header: Title, Status, and Actions --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        {{-- Title and Status --}}
        <div class="d-flex align-items-center gap-3">
            <h2 class="mb-0 h3">{{ $project->title }}</h2>
            {{-- Status Badge --}}
            @php
                $statusBadgeClass = '';
                switch ($project->status) {
                    case 'pending': $statusBadgeClass = 'bg-warning-soft text-warning'; break;
                    case 'ongoing': $statusBadgeClass = 'bg-info-soft text-info'; break;
                    case 'complete': $statusBadgeClass = 'bg-success-soft text-success'; break;
                    default: $statusBadgeClass = 'bg-secondary-soft text-secondary';
                }
            @endphp
            <span class="badge {{ $statusBadgeClass }} py-2 px-3 fs-6">{{ ucfirst($project->status) }}</span>
        </div>
        
        {{-- Action Buttons --}}
        <div>
            @if (Auth::user()->can('projectUpdate'))
            <a href="{{ route('project.edit', $project->id) }}" class="btn btn-primary">
                <i data-feather="edit-2" class="me-1" style="width:16px;"></i> Edit Project
            </a>
            @endif
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="row g-4">
        
        {{-- Left Column (Main Content) --}}
        <div class="col-lg-8">
            
            {{-- Card: Project Description --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title">Project Description</h5>
                </div>
                <div class="card-body project-description-content">
                    @if($project->description)
                        {!! $project->description !!}
                    @else
                        <span class="text-muted">No description provided.</span>
                    @endif
                </div>
            </div>

            {{-- Card: Project Gallery --}}
            @if($project->galleryImages->isNotEmpty())
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title">Project Gallery</h5>
                </div>
                <div class="card-body">
                    <div class="project-gallery-container">
                        @foreach($project->galleryImages as $image)
                            <div class="project-gallery-item">
                                {{-- This HTML structure is what Lightbox looks for --}}
                                <a href="{{ asset($image->image_path) }}" data-lightbox="project-gallery" data-title="{{ $project->title }} - Image {{ $loop->iteration }}">
                                    <img src="{{ asset($image->image_path) }}" alt="Project Gallery Image {{ $loop->iteration }}" class="lightbox-trigger">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Right Column (Summary) --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title">Project Summary</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush summary-list">
                        <li class="list-group-item">
                            <span class="text-muted">Category</span>
                            <strong>{{ $project->category->name ?? 'N/A' }}</strong>
                        </li>
                        {{-- ADD THIS NEW BLOCK --}}
                        <li class="list-group-item">
                            <span class="text-muted">Service</span>
                            <strong>{{ $project->service ?? 'N/A' }}</strong>
                        </li>
                        {{-- END OF NEW BLOCK --}}
                        <li class="list-group-item">
                            <span class="text-muted">Client</span>
                            <strong>
                                @if($project->client->name)
                                    {{ $project->client->name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </strong>
                        </li>
                        <li class="list-group-item">
                            <span class="text-muted">Country</span>
                            <strong>
                                @if($project->country->name)
                                    {{ $project->country->name }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </strong>
                        </li>
                        <li class="list-group-item">
                            <span class="text-muted">Agreement Date</span>
                            <strong>
                                @if($project->agreement_signing_date)
                                    {{ date('d M, Y', strtotime($project->agreement_signing_date)) }}
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </strong>
                        </li>
                    </ul>
                </div>
                {{-- Meta Info Footer --}}
                <div class="card-footer bg-light text-center small text-muted">
                    <p class="mb-1"><strong>Created:</strong> {{ $project->created_at->format('d M, Y h:i A') }}</p>
                    <p class="mb-0"><strong>Last Updated:</strong> {{ $project->updated_at->format('d M, Y h:i A') }}</p>
                </div>
            </div>
        </div>

    </div> {{-- End .row --}}

</div>
@endsection

@section('script')
{{-- NEW: Lightbox JS (Note: This library requires jQuery, which you already have) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js" integrity="sha512-k2GFCTbp9rQU412BStrcD/rlwv1PYec9SNrkbQlo6RZCf75l6KcC3Uw98n1fNIXnTfSNvNFlbZAigQLGaOMmyw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {
         // NEW: Initialize Lightbox
         lightbox.option({
           'resizeDuration': 200,
           'fadeDuration': 250,
           'wrapAround': true,
           'alwaysShowNavOnTouchDevices': true
         });
    });

     try { feather.replace() } catch (e) {} // For icons
</script>
@endsection