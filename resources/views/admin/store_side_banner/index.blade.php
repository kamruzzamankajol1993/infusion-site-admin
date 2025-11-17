@extends('admin.master.master')

@section('title')
Store Side Banners | {{ $ins_name }}
@endsection

@section('css')
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
    /* 400x200 Aspect Ratio (2:1 -> 50%) */
    .preview-400x200 { padding-top: 50%; max-width: 400px; } 
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Store Management</a></li>
            <li class="breadcrumb-item active" aria-current="page">Side Banners</li>
        </ol>
    </nav>
    @include('flash_message')

    <form action="{{ route('storeSideBanner.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" novalidate id="contentForm">
        @csrf
        
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Manage Side Banners</h5></div>
            <div class="card-body">
                <div class="row g-4">
                    {{-- Top Banner --}}
                    <div class="col-lg-6">
                        <h6 class="text-primary">Top-Right Banner</h6>
                        <div class="mb-3">
                            <label for="top_link" class="form-label">Link <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" id="top_link" name="top_link" value="{{ old('top_link', $content->top_link ?? '#') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="top_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="top_image" name="top_image" accept="image/*">
                            <small class="form-text text-muted">Recommended: 400x200 px. Leave blank to keep current.</small>
                            <div class="image-preview-box preview-400x200 mt-2">
                                <img id="topImagePreview" src="{{ $content->top_image ? asset($content->top_image) : '#' }}" alt="Preview" style="{{ $content->top_image ? '' : 'display:none;' }}">
                                <span class="placeholder-text" style="{{ $content->top_image ? 'display:none;' : '' }}">Top Image (400x200)</span>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Banner --}}
                    <div class="col-lg-6">
                        <h6 class="text-primary">Bottom-Right Banner</h6>
                        <div class="mb-3">
                            <label for="bottom_link" class="form-label">Link <span class="text-danger">*</span></label>
                            <input type="url" class="form-control" id="bottom_link" name="bottom_link" value="{{ old('bottom_link', $content->bottom_link ?? '#') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="bottom_image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="bottom_image" name="bottom_image" accept="image/*">
                            <small class="form-text text-muted">Recommended: 400x200 px. Leave blank to keep current.</small>
                            <div class="image-preview-box preview-400x200 mt-2">
                                <img id="bottomImagePreview" src="{{ $content->bottom_image ? asset($content->bottom_image) : '#' }}" alt="Preview" style="{{ $content->bottom_image ? '' : 'display:none;' }}">
                                <span class="placeholder-text" style="{{ $content->bottom_image ? 'display:none;' : '' }}">Bottom Image (400x200)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mt-4">
            <button type="submit" class="btn btn-primary">Save Banners</button>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        
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
        
        $('#topImagePreview').data('original-src', $('#topImagePreview').attr('src'));
        $('#bottomImagePreview').data('original-src', $('#bottomImagePreview').attr('src'));

        handleImagePreview('top_image', 'topImagePreview', '.placeholder-text');
        handleImagePreview('bottom_image', 'bottomImagePreview', '.placeholder-text');

        // Bootstrap validation
        $('form').submit(function(e) {
            if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
            $(this).addClass('was-validated');
        });
    });
</script>
@endsection