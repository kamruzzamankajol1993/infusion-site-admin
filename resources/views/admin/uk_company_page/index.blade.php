@extends('admin.master.master')

@section('title')
UK Company Page Content | {{ $ins_name }}
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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
    /* 400x450 Aspect Ratio (40:45 -> 112.5%) */
    .preview-400x450 { padding-top: 112.5%; max-width: 400px; margin-inline: auto; } 
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">UK Company Setup</a></li>
            <li class="breadcrumb-item active" aria-current="page">Page Content</li>
        </ol>
    </nav>
    @include('flash_message')

    <form action="{{ route('ukCompany.page.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" novalidate id="contentForm">
        @csrf
        
        {{-- Hero Section --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Hero Section</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label for="hero_subtitle_top" class="form-label">Top Subtitle <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="hero_subtitle_top" name="hero_subtitle_top" value="{{ old('hero_subtitle_top', $content->hero_subtitle_top ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="hero_title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $content->hero_title ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="hero_description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="hero_description" name="hero_description" rows="4" required>{{ old('hero_description', $content->hero_description ?? '') }}</textarea>
                        </div>
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
                        <small class="form-text text-muted">Recommended: 400x450 px. Leave blank to keep current.</small>
                        <div class="image-preview-box preview-400x450 mt-2">
                            <img id="heroImagePreview" src="{{ $content->hero_image ? asset($content->hero_image) : '#' }}" alt="Preview" style="{{ $content->hero_image ? '' : 'display:none;' }}">
                            <span class="placeholder-text" style="{{ $content->hero_image ? 'display:none;' : '' }}">Image Preview (400x450)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Other Section Titles --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Section Titles & Text</h5></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="carbon_badge_text" class="form-label">Carbon Badge Text <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="carbon_badge_text" name="carbon_badge_text" value="{{ old('carbon_badge_text', $content->carbon_badge_text ?? '') }}" required>
                </div>
                <hr>
                <div class="mb-3">
                    <label for="pricing_title" class="form-label">Pricing Section Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pricing_title" name="pricing_title" value="{{ old('pricing_title', $content->pricing_title ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="pricing_description" class="form-label">Pricing Section Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="pricing_description" name="pricing_description" rows="3" required>{{ old('pricing_description', $content->pricing_description ?? '') }}</textarea>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="testimonial_title" class="form-label">Testimonial Section Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="testimonial_title" name="testimonial_title" value="{{ old('testimonial_title', $content->testimonial_title ?? '') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="review_title" class="form-label">Review Platform Section Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="review_title" name="review_title" value="{{ old('review_title', $content->review_title ?? '') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Save All Content</button>
        </div>
    </form>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        // No Summernote needed for this page
        
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

        // Bootstrap validation
        $('form').submit(function(e) {
            if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
            $(this).addClass('was-validated');
        });
    });
</script>
@endsection