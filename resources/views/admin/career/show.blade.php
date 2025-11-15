@extends('admin.master.master')

@section('title')
View Career: {{ $career->title }} | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* NEW: Sidebar Details List */
    .details-list-group {
        font-size: 0.95rem;
    }
    .details-list-group .list-group-item {
        padding-top: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(0,0,0,0.05) !important;
    }
    .details-list-group .list-group-item .item-label {
        color: #6c757d; /* Muted color for the label */
        display: block;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .details-list-group .list-group-item .item-value {
        color: var(--bs-dark); /* Darker color for the value */
        font-weight: 500;
        word-break: break-all; /* For long emails */
    }

    /* Styles for content added via a text editor */
    .rich-content-wrapper {
        line-height: 1.7;
    }
    .rich-content-wrapper img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin: 0.5rem 0;
    }
    .rich-content-wrapper ul,
    .rich-content-wrapper ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header: Breadcrumb & Actions --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('career.index') }}">Careers</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Details</li>
            </ol>
        </nav>
        <div>
            <a href="{{ route('career.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-1" style="width:16px;"></i> Back
            </a>
            @if (Auth::user()->can('careerUpdate'))
            <a href="{{ route('career.edit', $career->id) }}" class="btn btn-primary ms-2">
                <i data-feather="edit-2" class="me-1" style="width:16px;"></i> Edit
            </a>
            @endif
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="row g-4">
        
        {{-- Left Column (Main Content) --}}
        <div class="col-lg-8">
            
            {{-- Title Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4 mb-1">{{ $career->title }}</h2>
                    <p class="text-muted mb-0">
                        {{ $career->position }} at {{ $career->company_name }}
                    </p>
                </div>
            </div>

            {{-- Card 1: Job Description --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title">Job Description / Responsibilities</h5>
                </div>
                <div class="card-body rich-content-wrapper">
                    @if($career->description)
                        {!! $career->description !!}
                    @else
                        <p class="text-muted">No description provided.</p>
                    @endif
                </div>
            </div>

            {{-- Card 2: Qualifications --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title">Qualifications</h5>
                </div>
                <div class="card-body rich-content-wrapper">
                    @if($career->qualification)
                         {!! $career->qualification !!}
                    @else
                        <p class="text-muted">No qualifications provided.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column (Sidebar) --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title">Job Details</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush details-list-group">
                        <li class="list-group-item">
                            <span class="item-label">Company</span>
                            <span class="item-value">{{ $career->company_name }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="item-label">Position</span>
                            <span class="item-value">{{ $career->position }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="item-label">Location</span>
                            <span class="item-value">{{ $career->job_location }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="item-label">Experience</span>
                            <span class="item-value">{{ $career->experience }}</span>
                        </li>
                        {{-- === ADD THIS NEW BLOCK === --}}
                        <li class="list-group-item">
                            <span class="item-label">Salary</span>
                            <span class="item-value">{{ $career->salary ?? 'N/A' }}</span>
                        </li>
                        {{-- === END NEW BLOCK === --}}
                        <li class="list-group-item">
                            <span class="item-label">Age</span>
                            <span class="item-value">{{ $career->age }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="item-label">Deadline</span>
                            <span class="item-value">
                                {{ $career->application_deadline ? date('d M, Y', strtotime($career->application_deadline)) : 'N/A' }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span class="item-label">Apply Email</span>
                            <span class="item-value">
                                <a href="mailto:{{ $career->email }}">{{ $career->email }}</a>
                            </span>
                        </li>
                    </ul>
                </div>
                 {{-- Meta Info Footer --}}
                <div class="card-footer bg-light text-center small text-muted">
                    <p class="mb-1"><strong>Posted:</strong> {{ $career->created_at->format('d M, Y H:i A') }}</p>
                    <p class="mb-0"><strong>Last Updated:</strong> {{ $career->updated_at->format('d M, Y H:i A') }}</p>
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