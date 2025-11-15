@extends('admin.master.master')

@section('title')
View Slider | {{ $ins_name }}
@endsection

@section('css')
<style>
    .slider-details dt { color: #6c757d; font-weight: 500; }
    .slider-details dd { margin-bottom: 1rem; }
    .slider-image-show { max-width: 100%; height: auto; max-height: 500px; /* Adjust max height */ object-fit: contain; border-radius: .375rem; border: 1px solid #dee2e6; margin-bottom: 1.5rem; background-color: #f8f9fa;}
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header & Breadcrumb --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('slider.index') }}">Sliders</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Slider #{{ $slider->id }}</li>
            </ol>
        </nav>
        <div>
            <a href="{{ route('slider.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-1" style="width:16px;"></i> Back to List
            </a>
            @if (Auth::user()->can('sliderUpdate'))
            <a href="{{ route('slider.edit', $slider->id) }}" class="btn btn-info text-white ms-2">
                <i data-feather="edit-2" class="me-1" style="width:16px;"></i> Edit
            </a>
            @endif
        </div>
    </div>

    {{-- Details Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
             <h4 class="mb-0">{{ $slider->title ?: 'Slider #' . $slider->id }}</h4>
        </div>
        <div class="card-body slider-details">

            {{-- Image Display Section --}}
            @if($slider->image_url)
            <div class="text-center mb-4 pb-3 border-bottom">
                 <img src="{{ $slider->image_url }}" alt="{{ $slider->title ?: 'Slider Image' }}" class="slider-image-show">
            </div>
            @else
                <div class="alert alert-light text-center" role="alert">No image available for this slider.</div>
            @endif

             {{-- FIXED SECTION --}}
             <dl class="row">
                <dt class="col-sm-3 col-lg-2">Title</dt>
                <dd class="col-sm-9 col-lg-10">
                    @if($slider->title)
                        {{ $slider->title }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </dd>

                <dt class="col-sm-3 col-lg-2">Subtitle</dt>
                <dd class="col-sm-9 col-lg-10">
                    @if($slider->subtitle)
                        {{ $slider->subtitle }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </dd>

                <dt class="col-sm-3 col-lg-2">Description</dt>
                <dd class="col-sm-9 col-lg-10">
                    @if($slider->short_description)
                        {{ $slider->short_description }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </dd>
            </dl>
            {{-- END FIXED SECTION --}}

            {{-- Timestamps Footer --}}
             <hr class="mt-4">
             <p class="text-muted small mb-0 text-end">
                Created: {{ $slider->created_at->format('d M, Y H:i A') }} |
                Last Updated: {{ $slider->updated_at->format('d M, Y H:i A') }}
            </p>
        </div>
    </div>

</div>
@endsection

@section('script')
    <script>
         try { feather.replace() } catch (e) {} // For icons
    </script>
@endsection