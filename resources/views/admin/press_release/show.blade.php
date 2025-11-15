@extends('admin.master.master')

@section('title')
View Press Release: {{ $pressRelease->title }} | {{ $ins_name }}
@endsection

@section('css')
<style>
    .pr-details dt { color: #6c757d; font-weight: 500; }
    .pr-details dd { margin-bottom: 1rem; }
    .pr-image-show { max-width: 100%; height: auto; max-height: 400px; /* Adjust */ object-fit: contain; border-radius: .375rem; border: 1px solid #dee2e6; margin-bottom: 1.5rem; background-color: #f8f9fa;}
    .pr-details dd div, .pr-details dd p { line-height: 1.6; }
    .external-link { word-break: break-all; } /* Prevent long links from breaking layout */
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header & Breadcrumb --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('pressRelease.index') }}">Press Releases</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($pressRelease->title, 50) }}</li>
            </ol>
        </nav>
        <div>
            <a href="{{ route('pressRelease.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-1" style="width:16px;"></i> Back to List
            </a>
            @if (Auth::user()->can('pressReleaseUpdate'))
            <a href="{{ route('pressRelease.edit', $pressRelease->id) }}" class="btn btn-info text-white ms-2">
                <i data-feather="edit-2" class="me-1" style="width:16px;"></i> Edit
            </a>
            @endif
        </div>
    </div>

    {{-- Details Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
             <h4 class="mb-0">{{ $pressRelease->title }}</h4>
             {{-- Removed the Type Badge --}}
        </div>
        <div class="card-body pr-details">

            {{-- Image Display Section --}}
            @if($pressRelease->image)
            <div class="text-center mb-4 pb-3 border-bottom">
                 <img src="{{asset($pressRelease->image)}}" alt="{{ $pressRelease->title }}" class="pr-image-show">
            </div>
            @endif

             <dl class="row">
                {{-- === ADDED RELEASE DATE === --}}
                <dt class="col-sm-3 col-lg-2">Release Date</dt>
                <dd class="col-sm-9 col-lg-10">
                    @if($pressRelease->release_date)
                        {{ $pressRelease->release_date->format('d M, Y') }}
                    @else
                        <span class="text-muted">N/A</span>
                    @endif
                </dd>
                {{-- === END ADD === --}}

                {{-- Show Link (if it exists) --}}
                @if($pressRelease->link)
                    <dt class="col-sm-3 col-lg-2">Link</dt>
                    <dd class="col-sm-9 col-lg-10">
                        <a href="{{ $pressRelease->link }}" target="_blank" rel="noopener noreferrer" class="external-link">
                            {{ $pressRelease->link }} <i data-feather="external-link" style="width:14px; height:14px; margin-left: 4px;"></i>
                        </a>
                    </dd>
                 @endif
                 
                {{-- Show Description (if it exists) --}}
                 @if($pressRelease->description)
                    <dt class="col-sm-3 col-lg-2">Description</dt>
                    <dd class="col-sm-9 col-lg-10">
                        {!! $pressRelease->description !!}
                    </dd>
                 @endif
                 
                 {{-- Handle case where NEITHER exists --}}
                 @if(!$pressRelease->link && !$pressRelease->description)
                    <dt class="col-sm-3 col-lg-2">Content</dt>
                    <dd class="col-sm-9 col-lg-10">
                        <span class="text-muted">No link or description provided.</span>
                    </dd>
                 @endif

            </dl>
            {{-- Timestamps Footer --}}
             <hr class="mt-4">
             <p class="text-muted small mb-0 text-end">
                Created: {{ $pressRelease->created_at->format('d M, Y H:i A') }} |
                Last Updated: {{ $pressRelease->updated_at->format('d M, Y H:i A') }}
            </p>
        </div>
    </div>

</div>
@endsection

@section('script')
    <script>
         try { feather.replace() } catch (e) {} // For icons if needed
    </script>
@endsection