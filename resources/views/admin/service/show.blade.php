@extends('admin.master.master')

@section('title')
View Service: {{ $service->title }} | {{ $ins_name }}
@endsection

@section('css')
<style>
    .service-image {
        max-width: 100%;
        height: auto;
        max-height: 400px; /* Limit image height */
        object-fit: contain;
        border-radius: .375rem;
        border: 1px solid #dee2e6;
    }
    .keypoint-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f1f1f1;
    }
     .keypoint-list li:last-child {
        border-bottom: none;
    }
     .keypoint-list .icon {
         color: var(--primary-color);
         margin-right: 10px;
         width: 18px; /* Feather icon size */
     }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header & Breadcrumb --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('service.index') }}">Services</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $service->title }}</li>
            </ol>
        </nav>
        <div>
             @if (Auth::user()->can('serviceUpdate'))
            <a href="{{ route('service.edit', $service->id) }}" class="btn btn-info text-white">
                <i data-feather="edit-2" class="me-1" style="width:16px;"></i> Edit Service
            </a>
            @endif
        </div>
    </div>

    {{-- Service Details Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
             <h4 class="mb-0">{{ $service->title }}</h4>
        </div>
        <div class="card-body">
            <div class="row g-4">
                {{-- Image Column --}}
                <div class="col-md-5 text-center">
                     @if($service->image)
                        <img src="{{ asset($service->image) }}" alt="{{ $service->title }}" class="service-image mb-3">
                    @else
                        <div class="alert alert-light text-center" role="alert">No image available.</div>
                    @endif
                </div>

                {{-- Details Column --}}
                <div class="col-md-7">
                    <h5 class="text-primary border-bottom pb-2 mb-3">Description</h5>
                    @if($service->description)
                        <div class="service-description">
                            {!! $service->description !!}
                        </div>
                    @else
                        <p class="text-muted">No description provided.</p>
                    @endif

                    {{-- Keypoints --}}
                    @if($service->keypoints->isNotEmpty())
                    <h5 class="text-primary border-bottom pb-2 mt-4 mb-3">Keypoints</h5>
                    <ul class="list-unstyled keypoint-list">
                        @foreach($service->keypoints as $keypoint)
                            <li class="d-flex align-items-start">
                                <i data-feather="check-circle" class="icon flex-shrink-0"></i>
                                <span>{{ $keypoint->keypoint }}</span>
                            </li>
                        @endforeach
                    </ul>
                    @endif

                    <hr>
                    <p class="text-muted small mb-0">
                        Created: {{ $service->created_at->format('d M, Y H:i A') }} <br>
                        Last Updated: {{ $service->updated_at->format('d M, Y H:i A') }}
                    </p>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('script')
    {{-- Feather Icons Replacement (if not globally initialized in master) --}}
    <script>
         try { feather.replace() } catch (e) { console.warn("Feather icons not loaded or failed to replace.")}
    </script>
@endsection