@extends('admin.master.master')

@section('title')
Bread Crumb Images | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* New styles for professional image uploader */
    .image-upload-wrapper {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        height: 150px; /* Set a fixed height */
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f9f9f9;
        transition: background-color 0.3s, border-color 0.3s;
    }
    .image-upload-wrapper:hover {
        background-color: #f1f1f1;
        border-color: #aaa;
    }
    .image-upload-wrapper .upload-text {
        color: #777;
    }
    .image-upload-wrapper .upload-text i {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 5px;
    }
    .image-upload-wrapper .upload-text span {
        font-size: 0.9rem;
    }
    /* Style for the preview image */
    .image-upload-wrapper .img-preview {
        max-width: 100%;
        max-height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover; /* Use 'cover' for a nice fill */
        padding: 5px;
        display: none; /* Hidden by default, shown by JS */
        background-color: #fff;
    }
    /* Hide the default input */
    .image-upload-wrapper input[type="file"] {
        display: none;
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Bread Crumb Images</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Update Breadcrumb Images</h5>
            <small class="text-muted">All images will be resized to 764x430 pixels.</small>
        </div>
        <div class="card-body">
            @include('flash_message')

            <form method="post" action="{{ route('breadCrumbImage.storeOrUpdate') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                <div class="row">

                    {{-- Loop through all defined types from the controller --}}
                    @foreach($types as $type => $title)
                        @php
                            // Get the existing image data for this type, if it exists
                            $image = $images->get($type);
                            $imageUrl = $image && $image->logo ? asset('public/' . $image->logo) : null;
                        @endphp

                        <div class="col-md-3">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">{{ $title }}</h5>
                                </div>
                                <div class="card-body">
                                    {{-- The 'name' field, populated with existing data --}}
                                    <div class="mb-3">
                                        <label class="form-label">Title<span class="text-danger">*</span></label>
                                        <input type="text" name="data[{{ $type }}][name]" class="form-control" 
                                               value="{{ old('data.'.$type.'.name', $image->name ?? $title) }}" required>
                                        @error('data.'.$type.'.name')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- The Image Uploader --}}
                                    <div class="mb-3">
                                        <label class="form-label">Image<span class="text-danger">*</span></label>
                                        
                                        {{-- Clicking this div triggers the hidden file input --}}
                                        <div class="image-upload-wrapper" onclick="document.getElementById('logo_{{ $type }}').click();">
                                            <input type="file" name="data[{{ $type }}][logo]" id="logo_{{ $type }}" 
                                                   class="image-upload-input" 
                                                   accept="image/*" 
                                                   onchange="previewImage(event, 'preview_{{ $type }}')">
                                            
                                            {{-- This text shows by default --}}
                                            <div class="upload-text">
                                                <i class="fa fa-cloud-upload"></i>
                                                <span>Click to upload (764x430)</span>
                                            </div>
                                            
                                            {{-- The preview image --}}
                                            <img id="preview_{{ $type }}" class="img-preview" 
                                                 src="{{ $imageUrl }}" 
                                                 style="{{ $imageUrl ? 'display: block;' : 'display: none;' }}">
                                        </div>
                                        @error('data.'.$type.'.logo')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-save me-1"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // --- JavaScript for Image Preview ---
    function previewImage(event, previewId) {
        var reader = new FileReader();
        var preview = document.getElementById(previewId);
        
        reader.onload = function(){
            if (reader.readyState === 2) {
                preview.src = reader.result;
                preview.style.display = 'block';
                // Hide the upload text
                preview.closest('.image-upload-wrapper').querySelector('.upload-text').style.display = 'none';
            }
        };
        
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection