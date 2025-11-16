@extends('admin.master.master')
@section('title') Facebook Page Content | {{ $ins_name }} @endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .image-preview-box { position: relative; width: 100%; border: 2px dashed #ced4da; border-radius: .375rem;
        display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;
        color: #6c757d; overflow: hidden; margin-top: 1rem; }
    .image-preview-box img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; }
    .image-preview-box .placeholder-text { padding: 1rem; text-align: center; }
    .preview-500x300 { padding-top: 60%; } /* 300 / 500 * 100% */
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Facebook Page Setup</a></li>
            <li class="breadcrumb-item active" aria-current="page">Page Content</li>
        </ol>
    </nav>
    @include('flash_message')

    <form action="{{ route('facebookPage.page.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" novalidate id="contentForm">
        @csrf
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Header & Intro Section</h5></div>
            <div class="card-body">
                <div class="mb-3"><label for="header_title" class="form-label">Header Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="header_title" name="header_title" value="{{ old('header_title', $content->header_title ?? '') }}" required></div>
                <hr>
                <div class="row">
                    <div class="col-md-7">
                        <div class="mb-3"><label for="intro_title" class="form-label">Intro Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="intro_title" name="intro_title" value="{{ old('intro_title', $content->intro_title ?? '') }}" required></div>
                        <div class="mb-3"><label for="intro_description" class="form-label">Intro Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="intro_description" name="intro_description" rows="6" required>{{ old('intro_description', $content->intro_description ?? '') }}</textarea></div>
                    </div>
                    <div class="col-md-5">
                        <label for="intro_image" class="form-label">Intro Image</label>
                        <input type="file" class="form-control" id="intro_image" name="intro_image" accept="image/*">
                        <small class="form-text text-muted">Recommended: 500x300 px. Leave blank to keep current.</small>
                        <div class="image-preview-box preview-500x300 mt-2">
                            <img id="introImagePreview" src="{{ $content->intro_image ? asset($content->intro_image) : '#' }}" alt="Preview" style="{{ $content->intro_image ? '' : 'display:none;' }}">
                            <span class="placeholder-text" style="{{ $content->intro_image ? 'display:none;' : '' }}">Image Preview (500x300)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Pricing Table Section</h5></div>
            <div class="card-body">
                <div class="mb-3"><label for="pricing_title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pricing_title" name="pricing_title" value="{{ old('pricing_title', $content->pricing_title ?? '') }}" required></div>
                <div class="mb-3"><label for="pricing_description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="pricing_description" name="pricing_description" rows="3" required>{{ old('pricing_description', $content->pricing_description ?? '') }}</textarea></div>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">More Services Section</h5></div>
            <div class="card-body">
                <div class="mb-3"><label for="more_services_title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="more_services_title" name="more_services_title" value="{{ old('more_services_title', $content->more_services_title ?? '') }}" required></div>
                <div class="mb-3"><label for="more_services_description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="more_services_description" name="more_services_description" rows="3" required>{{ old('more_services_description', $content->more_services_description ?? '') }}</textarea></div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Save All Content</button>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // No Summernote needed for this page as per HTML structure
        
        function handleImagePreview(inputId, previewId, placeholderClass) {
            $("#" + inputId).change(function() {
                const input = this, preview = $('#' + previewId), placeholder = preview.siblings(placeholderClass);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                    reader.readAsDataURL(input.files[0]);
                } else { preview.attr('src', '#').hide(); placeholder.show(); }
            });
        }
        handleImagePreview('intro_image', 'introImagePreview', '.placeholder-text');

        // Bootstrap validation
        $('form').submit(function(e) {
            if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
            $(this).addClass('was-validated');
        });
    });
</script>
@endsection