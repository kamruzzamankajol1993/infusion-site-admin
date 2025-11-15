@extends('admin.master.master')

@section('title')
View Officer: {{ $officer->name }} | {{ $ins_name ?? 'Admin Panel' }}
@endsection

@section('css')
<style>
    .profile-img {
        width: 180px;
        height: 180px;
        object-fit: cover;
        border: 4px solid #FFF;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); /* Added subtle shadow */
    }
    .card-header-action {
        text-decoration: none;
    }
    /* Styling for definition list items */
    .list-group-item .fw-bold {
        color: var(--primary-color, #0d6efd); /* Use Bootstrap primary or fallback */
        min-width: 110px; /* Adjust as needed for alignment */
        display: inline-block;
        margin-right: 0.5rem;
    }
    .list-group-item i[data-feather] { /* Style Feather icons */
        width: 16px;
        height: 16px;
        vertical-align: text-bottom;
        margin-right: 8px;
        color: #6c757d; /* Muted color */
    }
    /* Styling for social link icons */
    .social-link-icon {
        width: 18px; /* Slightly larger */
        margin-right: 8px;
        vertical-align: middle; /* Center vertically */
        color: #0d6efd; /* Use primary color */
    }
    /* Styling for description content (lists, paragraphs) */
    .description-content ul,
    .description-content ol {
        padding-left: 20px; /* Standard list indent */
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }
    .description-content li {
        margin-bottom: 0.3rem;
        line-height: 1.6;
    }
     .description-content p {
        margin-bottom: 1rem;
        line-height: 1.6;
    }
    /* Styling for badges */
    .badge { font-size: 0.85em; } /* Slightly smaller badges */
    .expert-area-badge { font-size: 0.9em; }

</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            {{-- REMOVED admin. prefix --}}
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('officer.index') }}">Officers</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $officer->name }}</li>
        </ol>
    </nav>

    {{-- Header Card --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2"> {{-- Added gap --}}
            <div>
                <h4 class="mb-0">{{ $officer->name }}</h4>
                <p class="mb-0 text-muted small">Officer Details</p>
            </div>
            @if (Auth::user()->can('officerUpdate'))
            {{-- REMOVED admin. prefix --}}
            <a href="{{ route('officer.edit', $officer->id) }}" class="btn btn-primary btn-sm"> {{-- Made button smaller --}}
                <i data-feather="edit-2" class="me-1" style="width:14px; height: 14px;"></i> Edit Officer
            </a>
            @endif
        </div>
    </div>

    <div class="row">
        {{-- Left Column: Profile Pic, Contact, Dates, Socials --}}
        <div class="col-lg-4">

            {{-- Profile Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    {{-- Profile Image --}}
                    @if($officer->image)
                        <img src="{{ asset($officer->image) }}" alt="{{ $officer->name }}" class="img-fluid rounded-circle mb-3 profile-img">
                    @else
                        <img src="{{ asset('public/admin/assets/img/demo-user.svg') }}" alt="Default" class="img-fluid rounded-circle mb-3 profile-img">
                    @endif

                    {{-- Name & Status --}}
                    <h5 class="mb-1">{{ $officer->name }}</h5>
                    <span class="badge {{ $officer->status ? 'bg-success-soft text-success' : 'bg-danger-soft text-danger' }} py-1 px-2 mb-3" style="font-size: 0.85rem;">
                        {{ $officer->status ? 'Active' : 'Inactive' }}
                    </span>
                    {{-- Show Button Badge --}}
                    <span class="badge {{ $officer->show_profile_details_button ? 'bg-primary-soft text-primary' : 'bg-secondary-soft text-secondary' }} py-1 px-2 mb-3" style="font-size: 0.85rem;">
                       Profile Btn: {{ $officer->show_profile_details_button ? 'Yes' : 'No' }}
                    </span>

                    <hr class="my-3">

                    {{-- Contact & Dates Info (Text Aligned Left) --}}
                    <div class="text-start">
                        <p class="mb-2 d-flex align-items-center">
                            <i data-feather="mail"></i>
                            <strong class="ms-2">Email:</strong> <span class="ms-2 text-break">{{ $officer->email ?? 'N/A' }}</span>
                        </p>
                         <p class="mb-2 d-flex align-items-center">
                             <i data-feather="phone"></i>
                            <strong class="ms-2">Phone:</strong> <span class="ms-2">{{ $officer->phone ?? 'N/A' }}</span>
                        </p>
                        {{-- ADD THIS NEW BLOCK --}}
                         <p class="mb-2 d-flex align-items-center">
                             <i data-feather="smartphone"></i>
                            <strong class="ms-2">Mobile:</strong> <span class="ms-2">{{ $officer->mobile_number ?? 'N/A' }}</span>
                        </p>
                        {{-- END OF NEW BLOCK --}}
                         <p class="mb-2 d-flex align-items-center">
                            <i data-feather="calendar"></i>
                            <strong class="ms-2">Start Date:</strong> <span class="ms-2">{{ $officer->start_date ? date('d M, Y', strtotime($officer->start_date)) : 'N/A' }}</span>
                        </p>
                        <p class="mb-0 d-flex align-items-center">
                            <i data-feather="log-out"></i>
                            <strong class="ms-2">End Date:</strong> <span class="ms-2">{{ $officer->end_date ? date('d M, Y', strtotime($officer->end_date)) : 'Current' }}</span>
                        </p>

                        {{-- Slug --}}
                        <p class="mb-0 d-flex align-items-center">
                            <i data-feather="link"></i>
                            <strong class="ms-2">Slug:</strong> <span class="ms-2 text-break">{{ $officer->slug ?? 'N/A' }}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Social Links Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-2"> {{-- Reduced padding --}}
                    <h6 class="mb-0 fw-semibold">Social Links</h6>
                </div>
                <div class="card-body pt-2 pb-2"> {{-- Reduced padding --}}
                    @if($officer->socialLinks->isNotEmpty())
                        <ul class="list-unstyled mb-0">
                            @foreach($officer->socialLinks as $link)
                            <li class="mb-1">
                                <a href="{{ $link->link }}" target="_blank" rel="noopener noreferrer" class="d-flex align-items-center text-decoration-none text-dark list-group-item-action p-1">
                                    {{-- Basic Icon Logic (can be improved with a helper or library) --}}
                                    @php $iconClass = 'bi-link-45deg'; $color = 'text-primary'; @endphp
                                    @if(stripos($link->title, 'linkedin') !== false) @php $iconClass = 'bi-linkedin'; $color='text-info'; @endphp
                                    @elseif(stripos($link->title, 'facebook') !== false) @php $iconClass = 'bi-facebook'; $color='text-primary'; @endphp
                                    @elseif(stripos($link->title, 'twitter') !== false) @php $iconClass = 'bi-twitter-x'; $color='text-dark'; @endphp
                                    @elseif(stripos($link->title, 'website') !== false) @php $iconClass = 'bi-globe'; $color='text-success'; @endphp
                                    @endif {{-- <-- THIS WAS THE MISSING LINE --}}

                                    <i class="bi {{ $iconClass }} {{ $color }} social-link-icon"></i>
                                    <span>{{ $link->title }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small mb-0">No social links provided.</p>
                    @endif
                </div>
            </div>

        </div> {{-- End Left Column --}}

        {{-- Right Column: Bio, Dept/Designation, Categories, Expert Areas --}}
        <div class="col-lg-8">

            {{-- Bio Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 fw-semibold">Bio / Description</h6>
                </div>
                <div class="card-body description-content">
                    @if($officer->description)
                        {!! $officer->description !!} {{-- Render HTML --}}
                    @else
                        <p class="text-muted mb-0">No description provided.</p>
                    @endif
                </div>
            </div>

            {{-- Department & Designations Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 fw-semibold">Department & Designations</h6>
                </div>
                <div class="card-body">
                    {{-- Use list group for better structure --}}
                    <ul class="list-group list-group-flush">
                        @forelse($officer->departmentInfos as $info)
                            <li class="list-group-item d-flex justify-content-between align-items-start px-0 py-2">
                                <div class="ms-1 me-auto"> {{-- Reduced start margin --}}
                                    <div class="fw-bold">{{ $info->designation->name ?? 'N/A Designation' }}</div>
                                    <small class="text-muted">{{ $info->department->name ?? 'N/A Department' }}</small>
                                </div>
                                @if($info->additional_text)
                                    <span class="badge bg-light text-dark border rounded-pill py-1 px-2 align-self-center">{{ $info->additional_text }}</span>
                                @endif
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">No department information assigned.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- Expert Areas Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 fw-semibold">Expert Areas</h6>
                </div>
                <div class="card-body">
                    @if($officer->expertAreas->isNotEmpty())
                        @foreach($officer->expertAreas as $area)
                            <span class="badge bg-info-soft text-info me-1 mb-2 py-2 px-3 expert-area-badge">{{ $area->expert_area }}</span>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No expert areas listed.</p>
                    @endif
                </div>
            </div>

            {{-- Assigned Categories Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 fw-semibold">Assigned Categories</h6>
                </div>
                <div class="card-body">
                    @if($officer->categories->isNotEmpty())
                        @foreach($officer->categories as $category)
                            <span class="badge bg-secondary-soft text-secondary me-1 mb-2 py-2 px-3">{{ $category->name }}</span>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No categories assigned.</p>
                    @endif
                </div>
            </div>

        </div> {{-- End Right Column --}}
    </div> {{-- End Row --}}
</div> {{-- End Container --}}
@endsection

@section('script')
{{-- Ensure Feather Icons are initialized --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
{{-- Add Bootstrap Icons CSS if not loaded globally --}}
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> --}}
@endsection