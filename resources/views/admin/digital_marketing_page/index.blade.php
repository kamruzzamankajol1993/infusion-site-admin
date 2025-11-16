@extends('admin.master.master')

@section('title')
Digital Marketing Page Content | {{ $ins_name }}
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
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
    .preview-600x450 { padding-top: 75%; } /* 450 / 600 * 100% */
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Digital Marketing</a></li>
            <li class="breadcrumb-item active" aria-current="page">Page Content</li>
        </ol>
    </nav>
    @include('flash_message')

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Manage Digital Marketing Page Content</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('digitalMarketingPage.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" novalidate id="contentForm">
                @csrf
                @if(isset($content) && $content->id)
                    @method('PUT')
                @endif

                {{-- Hero Section --}}
                <h6 class="mt-2 text-primary">Hero Section</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="hero_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $content->hero_title ?? '') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="hero_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="hero_description" name="hero_description" required>{{ old('hero_description', $content->hero_description ?? '') }}</textarea>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="hero_button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="hero_button_text" name="hero_button_text" value="{{ old('hero_button_text', $content->hero_button_text ?? '') }}" required>
                    </div>
                </div>
                <hr>

                {{-- 360 Intro Section --}}
                <h6 class="mt-3 text-primary">360° Intro Section</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="intro_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="intro_title" name="intro_title" value="{{ old('intro_title', $content->intro_title ?? '') }}" required>
                        
                        <label for="intro_description" class="form-label mt-2">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="intro_description" name="intro_description" required>{{ old('intro_description', $content->intro_description ?? '') }}</textarea>
                        
                        <label for="intro_button_text" class="form-label mt-2">Button Text <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="intro_button_text" name="intro_button_text" value="{{ old('intro_button_text', $content->intro_button_text ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="intro_image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="intro_image" name="intro_image" accept="image/*">
                        <small class="form-text text-muted">Recommended: 600x450 px. Leave blank to keep current.</small>
                        <div class="image-preview-box preview-600x450 mt-2">
                            <img id="introImagePreview" src="{{ $content->intro_image ? asset($content->intro_image) : '#' }}" alt="Preview" style="{{ $content->intro_image ? '' : 'display:none;' }}">
                            <span class="placeholder-text" style="{{ $content->intro_image ? 'display:none;' : '' }}">Image Preview<br>(600 x 450)</span>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Consultant Section --}}
                <h6 class="mt-3 text-primary">Consultant Section</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="consultant_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="consultant_title" name="consultant_title" value="{{ old('consultant_title', $content->consultant_title ?? '') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="consultant_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="consultant_description" name="consultant_description" required>{{ old('consultant_description', $content->consultant_description ?? '') }}</textarea>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="consultant_button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="consultant_button_text" name="consultant_button_text" value="{{ old('consultant_button_text', $content->consultant_button_text ?? '') }}" required>
                    </div>
                </div>
                <hr>

                {{-- Growth Checklist Section --}}
                <h6 class="mt-3 text-primary">"How We Help You Grow" Section</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="growth_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="growth_title" name="growth_title" value="{{ old('growth_title', $content->growth_title ?? '') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="growth_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="growth_description" name="growth_description" rows="3" required>{{ old('growth_description', $content->growth_description ?? '') }}</textarea>
                    </div>
                </div>
                <hr>

                {{-- Marketing Solutions Section --}}
                <h6 class="mt-3 text-primary">"Marketing Solutions" Section</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="solutions_subtitle" class="form-label">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="solutions_subtitle" name="solutions_subtitle" value="{{ old('solutions_subtitle', $content->solutions_subtitle ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="solutions_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="solutions_title" name="solutions_title" value="{{ old('solutions_title', $content->solutions_title ?? '') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="solutions_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="solutions_description" name="solutions_description" rows="3" required>{{ old('solutions_description', $content->solutions_description ?? '') }}</textarea>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Content</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({ height: 150, toolbar: [
            ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']], ['view', ['codeview']]
        ]});

        function handleImagePreview(inputId, previewId, placeholderClass) {
            $("#" + inputId).change(function() {
                const input = this;
                const preview = $('#' + previewId);
                const placeholder = preview.siblings(placeholderClass);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        preview.attr('src', e.target.result).show();
                        placeholder.hide();
                    };
                    reader.readAsDataURL(input.files[0]);
                } else { preview.attr('src', '#').hide(); placeholder.show(); }
            });
        }
        handleImagePreview('intro_image', 'introImagePreview', '.placeholder-text');

        // Bootstrap validation
        $('form').submit(function(e) {
            if (!this.checkValidity()) {
                e.preventDefault(); e.stopPropagation();
            }
            $(this).addClass('was-validated');
        });
    });
</script>
@endsection