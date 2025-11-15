@extends('admin.master.master')

@section('title')
View Event: {{ $event->title }} | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* NEW: Main Event Image */
    .event-image-cover {
        width: 100%;
        max-height: 450px;
        object-fit: cover;
        border-bottom: 1px solid #dee2e6;
    }

    /* NEW: Summary Card Styles */
    .summary-list .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .summary-list .list-group-item strong {
        text-align: right;
        color: var(--bs-dark);
    }
    .summary-list .list-group-item .text-muted {
        color: #6c757d !important;
    }
    
    /* NEW: Description Content Styles */
    .rich-content-wrapper {
        line-height: 1.7;
    }
    .rich-content-wrapper img {
        max-width: 100%;
        height: auto;
        border-radius: 5px;
        margin: 0.5rem 0;
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
                <li class="breadcrumb-item"><a href="{{ route('event.index') }}">Events</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Details</li>
            </ol>
        </nav>
        <div>
            <a href="{{ route('event.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-1" style="width:16px;"></i> Back to List
            </a>
            @if (Auth::user()->can('eventUpdate'))
            <a href="{{ route('event.edit', $event->id) }}" class="btn btn-info text-white ms-2">
                <i data-feather="edit-2" class="me-1" style="width:16px;"></i> Edit
            </a>
            @endif
        </div>
    </div>

    {{-- Header: Title --}}
    <h2 class="h3 mb-4">{{ $event->title }}</h2>

    {{-- Main Content Grid --}}
    <div class="row g-4">
        
        {{-- Left Column (Main Content) --}}
        <div class="col-lg-8">
            <div class="card shadow-sm">
                {{-- Event Image --}}
                @if($event->image_url)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="event-image-cover">
                @endif
                
                {{-- Description --}}
                <div class="card-body">
                    <h5 class="card-title mb-3">Description</h5>
                    <div class="rich-content-wrapper">
                        @if($event->description)
                            {!! $event->description !!}
                        @else
                            <p class="text-muted">No description provided.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column (Summary) --}}
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 card-title">Event Details</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush summary-list">
                        <li class="list-group-item">
                            <span class="text-muted">Status</span>
                            <span class="badge {{ $event->status ? 'bg-success-soft text-success' : 'bg-secondary-soft text-secondary' }} py-2 px-3">
                                {{ $event->status ? 'Published' : 'Draft' }}
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span class="text-muted">Start Date</span>
                            <strong>
                                {{ $event->start_date ? date('d M, Y', strtotime($event->start_date)) : 'N/A' }}
                            </strong>
                        </li>
                        @if($event->end_date && $event->end_date !== $event->start_date)
                        <li class="list-group-item">
                            <span class="text-muted">End Date</span>
                            <strong>
                                {{ date('d M, Y', strtotime($event->end_date)) }}
                            </strong>
                        </li>
                        @endif
                        @if($event->time)
                        <li class="list-group-item">
                            <span class="text-muted">Time</span>
                            <strong>{{ $event->time }}</strong>
                        </li>
                        @endif
                    </ul>
                </div>
                {{-- Meta Info Footer --}}
                <div class="card-footer bg-light text-center small text-muted">
                    <p class="mb-1"><strong>Created:</strong> {{ $event->created_at->format('d M, Y H:i A') }}</p>
                    <p class="mb-0"><strong>Last Updated:</strong> {{ $event->updated_at->format('d M, Y H:i A') }}</p>
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