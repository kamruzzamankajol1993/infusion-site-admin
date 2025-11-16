@extends('admin.master.master')
@section('title') Web Solution Page Content | {{ $ins_name }} @endsection
@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .image-preview-box { position: relative; width: 100%; border: 2px dashed #ced4da; border-radius: .375rem;
        display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;
        color: #6c757d; overflow: hidden; margin-top: 1rem; }
    .image-preview-box img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; }
    .image-preview-box .placeholder-text { padding: 1rem; text-align: center; }
    .preview-600x450 { padding-top: 75%; } /* 450 / 600 * 100% */
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Web Solution</a></li>
            <li class="breadcrumb-item active" aria-current="page">Page Content</li>
        </ol>
    </nav>
    @include('flash_message')

    <form action="{{ route('webSolution.page.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" novalidate id="contentForm">
        @csrf
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Hero Section</h5></div>
            <div class="card-body">
                <div class="mb-3"><label for="hero_title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $content->hero_title ?? '') }}" required></div>
                <div class="mb-3"><label for="hero_description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control summernote" id="hero_description" name="hero_description" required>{{ old('hero_description', $content->hero_description ?? '') }}</textarea></div>
                <div class="mb-3"><label for="hero_button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="hero_button_text" name="hero_button_text" value="{{ old('hero_button_text', $content->hero_button_text ?? '') }}" required></div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Intro Section ("Build A Website")</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3"><label for="intro_title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="intro_title" name="intro_title" value="{{ old('intro_title', $content->intro_title ?? '') }}" required></div>
                        <div class="mb-3"><label for="intro_description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control summernote" id="intro_description" name="intro_description" required>{{ old('intro_description', $content->intro_description ?? '') }}</textarea></div>
                        <div class="mb-3"><label for="intro_button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="intro_button_text" name="intro_button_text" value="{{ old('intro_button_text', $content->intro_button_text ?? '') }}" required></div>
                    </div>
                    <div class="col-md-6">
                        <label for="intro_image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="intro_image" name="intro_image" accept="image/*">
                        <small class="form-text text-muted">Recommended: 600x450 px. Leave blank to keep current.</small>
                        <div class="image-preview-box preview-600x450 mt-2">
                            <img id="introImagePreview" src="{{ $content->intro_image ? asset($content->intro_image) : '#' }}" alt="Preview" style="{{ $content->intro_image ? '' : 'display:none;' }}">
                            <span class="placeholder-text" style="{{ $content->intro_image ? 'display:none;' : '' }}">Image Preview (600x450)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Pro Website Section ("Why Your Business Needs")</h5></div>
            <div class="card-body">
                <div class="mb-3"><label for="pro_title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pro_title" name="pro_title" value="{{ old('pro_title', $content->pro_title ?? '') }}" required></div>
                <div class="mb-3"><label for="pro_description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control summernote" id="pro_description" name="pro_description" required>{{ old('pro_description', $content->pro_description ?? '') }}</textarea></div>
                <div class="mb-3"><label for="pro_button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="pro_button_text" name="pro_button_text" value="{{ old('pro_button_text', $content->pro_button_text ?? '') }}" required></div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Checklist Section ("Why Choose Us")</h5></div>
            <div class="card-body">
                <div class="mb-3"><label for="checklist_title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="checklist_title" name="checklist_title" value="{{ old('checklist_title', $content->checklist_title ?? '') }}" required></div>
                <div class="mb-3"><label for="checklist_description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="checklist_description" name="checklist_description" rows="3" required>{{ old('checklist_description', $content->checklist_description ?? '') }}</textarea></div>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Service Includes Section</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="includes_subtitle" class="form-label">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="includes_subtitle" name="includes_subtitle" value="{{ old('includes_subtitle', $content->includes_subtitle ?? '') }}" required></div>
                    <div class="col-md-6 mb-3"><label for="includes_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="includes_title" name="includes_title" value="{{ old('includes_title', $content->includes_title ?? '') }}" required></div>
                    <div class="col-md-12 mb-3"><label for="includes_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="includes_description" name="includes_description" rows="3" required>{{ old('includes_description', $content->includes_description ?? '') }}</textarea></div>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Service Providing Section</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="providing_subtitle" class="form-label">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="providing_subtitle" name="providing_subtitle" value="{{ old('providing_subtitle', $content->providing_subtitle ?? '') }}" required></div>
                    <div class="col-md-6 mb-3"><label for="providing_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="providing_title" name="providing_title" value="{{ old('providing_title', $content->providing_title ?? '') }}" required></div>
                    <div class="col-md-12 mb-3"><label for="providing_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="providing_description" name="providing_description" rows="3" required>{{ old('providing_description', $content->providing_description ?? '') }}</textarea></div>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Previous Work Section</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="work_subtitle" class="form-label">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="work_subtitle" name="work_subtitle" value="{{ old('work_subtitle', $content->work_subtitle ?? '') }}" required></div>
                    <div class="col-md-6 mb-3"><label for="work_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="work_title" name="work_title" value="{{ old('work_title', $content->work_title ?? '') }}" required></div>
                    <div class="col-md-12 mb-3"><label for="work_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="work_description" name="work_description" rows="3" required>{{ old('work_description', $content->work_description ?? '') }}</textarea></div>
                </div>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">CTA Banner Section</h5></div>
            <div class="card-body">
                <div class="mb-3"><label for="cta_title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cta_title" name="cta_title" value="{{ old('cta_title', $content->cta_title ?? '') }}" required></div>
                <div class="mb-3"><label for="cta_description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="cta_description" name="cta_description" rows="3" required>{{ old('cta_description', $content->cta_description ?? '') }}</textarea></div>
                <div class="mb-3"><label for="cta_button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cta_button_text" name="cta_button_text" value="{{ old('cta_button_text', $content->cta_button_text ?? '') }}" required></div>
            </div>
        </div>
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Website Care Section</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><label for="care_subtitle" class="form-label">Subtitle <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="care_subtitle" name="care_subtitle" value="{{ old('care_subtitle', $content->care_subtitle ?? '') }}" required></div>
                    <div class="col-md-6 mb-3"><label for="care_title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="care_title" name="care_title" value="{{ old('care_title', $content->care_title ?? '') }}" required></div>
                    <div class="col-md-12 mb-3"><label for="care_description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="care_description" name="care_description" rows="3" required>{{ old('care_description', $content->care_description ?? '') }}</textarea></div>
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
        $('.summernote').summernote({ height: 120, toolbar: [
            ['style', ['bold', 'italic', 'underline']], ['para', ['ul', 'ol']]
        ]});
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
        $('form').submit(function(e) {
            if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
            $(this).addClass('was-validated');
        });
    });
</script>
@endsection