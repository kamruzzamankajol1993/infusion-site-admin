@extends('admin.master.master')

@section('title')
VPS/RDP Page Content | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Base Image Preview Box */
    .image-preview-box {
        position: relative; width: 100%;
        border: 2px dashed #ced4da; border-radius: .375rem;
        display: flex; align-items: center; justify-content: center;
        background-color: #f8f9fa; color: #6c757d;
        overflow: hidden; margin-top: 1rem;
    }
    .image-preview-box img {
        position: absolute; top: 0; left: 0; width: 100%;
        height: 100%; object-fit: contain;
    }
    .image-preview-box .placeholder-text {
        padding: 1rem; text-align: center;
    }
    /* 500x400 Aspect Ratio (5:4 -> 80%) */
    .preview-500x400 { padding-top: 80%; max-width: 500px; margin-inline: auto; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">VPS / RDP Page</a></li>
            <li class="breadcrumb-item active" aria-current="page">Page Content</li>
        </ol>
    </nav>
    @include('flash_message')

    <form action="{{ route('vpsPage.page.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" novalidate id="contentForm">
        @csrf
        
        {{-- Hero Section --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Hero Section</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="mb-3"><label for="hero_title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $content->hero_title ?? '') }}" required></div>
                        
                        {{-- Hero Features (Dynamic) --}}
                        <div id="hero-features-container">
                            <label class="form-label">Hero Feature List <span class="text-danger">*</span></label>
                            @if(!empty(old('hero_features', $content->hero_features ?? [])))
                                @foreach(old('hero_features', $content->hero_features) as $feature)
                                <div class="input-group mb-2 feature-row">
                                    <input type="text" name="hero_features[]" class="form-control" placeholder="Feature text" value="{{ $feature }}" required>
                                    <button class="btn btn-outline-danger" type="button" onclick="removeFeatureInput(this)">&times;</button>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addHeroFeatureBtn">
                            <i data-feather="plus" style="width:16px;"></i> Add Feature
                        </button>
                        <hr>
                        {{-- End Hero Features --}}
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hero_button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="hero_button_text" name="hero_button_text" value="{{ old('hero_button_text', $content->hero_button_text ?? '') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="hero_button_link" class="form-label">Button Link <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" id="hero_button_link" name="hero_button_link" value="{{ old('hero_button_link', $content->hero_button_link ?? '#') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="hero_image" class="form-label">Hero Image</label>
                        <input type="file" class="form-control" id="hero_image" name="hero_image" accept="image/*">
                        <small class="form-text text-muted">Recommended: 500x400 px. Leave blank to keep current.</small>
                        <div class="image-preview-box preview-500x400 mt-2">
                            <img id="heroImagePreview" src="{{ $content->hero_image ? asset($content->hero_image) : '#' }}" alt="Preview" style="{{ $content->hero_image ? '' : 'display:none;' }}">
                            <span class="placeholder-text" style="{{ $content->hero_image ? 'display:none;' : '' }}">Image Preview (500x400)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Titles --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Pricing Section Titles</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="category_1_title" class="form-label">Category 1 Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category_1_title" name="category_1_title" value="{{ old('category_1_title', $content->category_1_title ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="category_2_title" class="form-label">Category 2 Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category_2_title" name="category_2_title" value="{{ old('category_2_title', $content->category_2_title ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="category_3_title" class="form-label">Category 3 Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="category_3_title" name="category_3_title" value="{{ old('category_3_title', $content->category_3_title ?? '') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Save All Content</button>
        </div>
    </form>
</div>

{{-- Template for new hero feature --}}
<template id="hero-feature-template">
    <div class="input-group mb-2 feature-row">
        <input type="text" name="hero_features[]" class="form-control" placeholder="Feature text" required>
        <button class="btn btn-outline-danger" type="button" onclick="removeFeatureInput(this)">&times;</button>
    </div>
</template>

@endsection

@section('script')
<script>
    // Global function to remove a feature row
    function removeFeatureInput(button) {
        button.closest('.feature-row').remove();
    }
        
    $(document).ready(function() {
        
        // --- Hero Feature List JS ---
        $('#addHeroFeatureBtn').on('click', function() {
            const container = document.getElementById('hero-features-container');
            const template = document.getElementById('hero-feature-template');
            container.appendChild(template.content.cloneNode(true));
        });

        // Add one feature row by default if none exist
        if ($('#hero-features-container').find('.feature-row').length === 0) {
            $('#addHeroFeatureBtn').click();
        }

        // --- Image Preview JS ---
        function handleImagePreview(inputId, previewId, placeholderClass) {
            $("#" + inputId).change(function() {
                const input = this, preview = $('#' + previewId), placeholder = preview.siblings(placeholderClass);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                    reader.readAsDataURL(input.files[0]);
                } else { 
                    const originalSrc = preview.data('original-src');
                    if (originalSrc) {
                        preview.attr('src', originalSrc).show(); placeholder.hide();
                    } else {
                        preview.attr('src', '#').hide(); placeholder.show();
                    }
                }
            });
        }
        
        $('#heroImagePreview').data('original-src', $('#heroImagePreview').attr('src'));
        handleImagePreview('hero_image', 'heroImagePreview', '.placeholder-text');

        // --- Bootstrap Validation ---
        $('form').submit(function(e) {
            if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
            $(this).addClass('was-validated');
        });
    });
</script>
@endsection