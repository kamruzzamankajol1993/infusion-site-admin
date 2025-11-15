@extends('admin.master.master')

@section('title')
View Gallery Item #{{ $gallery->id }} | {{ $ins_name }}
@endsection

@section('css')
<style>
    .gallery-details dt { color: #6c757d; font-weight: 500; }
    .gallery-details dd { margin-bottom: 0.75rem; }
    .gallery-image-show { max-width: 100%; height: auto; max-height: 500px; object-fit: contain; border-radius: .375rem; border: 1px solid #dee2e6; margin-bottom: 1rem;}
    .video-container { position: relative; padding-bottom: 56.25%; /* 16:9 */ height: 0; overflow: hidden; max-width: 100%; background: #000; margin-bottom: 1rem; border-radius: .375rem; }
    .video-container iframe { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border:0; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header & Breadcrumb --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('gallery.index') }}">Gallery</a></li>
                <li class="breadcrumb-item active" aria-current="page">View Item #{{ $gallery->id }}</li>
            </ol>
        </nav>
        <div>
            <a href="{{ route('gallery.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-1" style="width:16px;"></i> Back to List
            </a>
             @if (Auth::user()->can('galleryUpdate'))
            <a href="{{ route('gallery.edit', $gallery->id) }}" class="btn btn-info text-white ms-2">
                <i data-feather="edit-2" class="me-1" style="width:16px;"></i> Edit
            </a>
            @endif
        </div>
    </div>

    {{-- Details Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
             <h5 class="mb-0">Gallery Item Details</h5>
             <span class="badge {{ $gallery->type === 'image' ? 'bg-info-soft text-info' : 'bg-danger-soft text-danger' }}">{{ ucfirst($gallery->type) }}</span>
        </div>
        <div class="card-body gallery-details">

             {{-- Display Image or Video Embed --}}
             @if($gallery->type === 'image' && $gallery->image_url)
                <div class="text-center">
                    <img src="{{ $gallery->image_url }}" alt="Gallery Image" class="gallery-image-show">
                </div>
             @elseif($gallery->type === 'video' && $gallery->youtube_embed_url)
                 <div class="video-container">
                    <iframe src="{{ $gallery->youtube_embed_url }}" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                 </div>
             @else
                 <div class="alert alert-light text-center" role="alert">No media available for this item.</div>
             @endif

             <dl class="row mt-4">
                {{-- --- FIX 3: Corrected Blade Syntax --- --}}
                <dt class="col-sm-3 col-lg-2">Description</dt>
                <dd class="col-sm-9 col-lg-10">
                    @if($gallery->short_description)
                        {{ $gallery->short_description }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </dd>
                {{-- --- END FIX 3 --- --}}

                @if($gallery->type === 'video')
                <dt class="col-sm-3 col-lg-2">YouTube Link</dt>
                <dd class="col-sm-9 col-lg-10">
                    @if($gallery->youtube_link)
                    <a href="{{ $gallery->youtube_link }}" target="_blank" rel="noopener noreferrer">{{ $gallery->youtube_link }}</a>
                    @else
                    <span class="text-muted">N/A</span>
                    @endif
                </dd>
                @endif

                 <dt class="col-sm-3 col-lg-2">Uploaded On</dt>
                 <dd class="col-sm-9 col-lg-10">{{ $gallery->created_at->format('d M, Y H:i A') }}</dd>

                 <dt class="col-sm-3 col-lg-2">Last Updated</dt>
                 <dd class="col-sm-9 col-lg-10">{{ $gallery->updated_at->format('d M, Y H:i A') }}</dd>
            </dl>
        </div>
    </div>

</div>
@endsection

@section('script')
    <script>
         try { feather.replace() } catch (e) {} // For icons
    </script>
@endsection