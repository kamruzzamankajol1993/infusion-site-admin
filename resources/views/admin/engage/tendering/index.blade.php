@extends('admin.master.master')
@php
    // Set a fallback title in case the entry is new and has no title yet
    $pageTitle = $entry->title ?? 'Tendering';
@endphp
@section('title')
Manage: {{ $pageTitle }}| {{ $ins_name }}
@endsection

@section('css')
<style>
    .image-preview-box {
        position: relative; width: 100%;
        padding-top: 100.8%; 
        border: 2px dashed #ced4da; border-radius: .375rem;
        display: flex; align-items: center; justify-content: center;
        background-color: #f8f9fa; color: #6c757d; overflow: hidden;
    }
    .image-preview-box img,
    .image-preview-box .placeholder-text {
        position: absolute; top: 0; left: 0;
        width: 100%; height: 100%; object-fit: contain;
    }
    .image-preview-box .placeholder-text {
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; text-align: center; padding: 1rem;
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">How IIFC Can Be Engaged</li>
            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Manage: {{ $pageTitle }} (Entry 2)</h5>
                </div>
                <div class="card-body">
                    @include('flash_message')
                    
                    <form action="{{ route('tendering.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" novalidate id="imageForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $entry->title) }}" required>
                             @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ADDED THIS BLOCK --}}
                        <div class="mb-3">
                            <label for="sort_description" class="form-label">Short Description</label>
                            <textarea class="form-control @error('sort_description') is-invalid @enderror" id="sort_description" name="sort_description" rows="4">{{ old('sort_description', $entry->sort_description) }}</textarea>
                             @error('sort_description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- END OF ADDED BLOCK --}}

                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" {{ $entry->image ? '' : 'required' }}>
                            <small class="form-text text-muted">
                                Required Size: <strong>500px (Width) x 504px (Height)</strong>. Max: 2MB.
                                @if($entry->image)
                                <br><span class="text-info">Leave blank to keep the current image.</span>
                                @endif
                            </small>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save" class="me-1" style="width:16px;"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-5 col-lg-6">
            <h6 class="text-muted">Current Image Preview</h6>
            <div class="image-preview-box shadow-sm">
                @if($entry->image)
                    <img id="imagePreview" src="{{ asset($entry->image) }}?v={{ time() }}" alt="Current Image">
                    <span class="placeholder-text" style="display:none;">Preview<br>(500 x 504)</span>
                @else
                    <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                    <span class="placeholder-text">No image uploaded.<br>(500 x 504)</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@include('admin.engage._partial.script') {{-- Reusing the same script --}}
@endsection