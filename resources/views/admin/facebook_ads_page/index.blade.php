@extends('admin.master.master')

@section('title')
Facebook Ads Page Content | {{ $ins_name }}
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
    /* 500x400 Aspect Ratio (5:4 -> 80%) */
    .preview-500x400 { padding-top: 80%; } 
    /* 150x50 Aspect Ratio */
    .preview-150x50 { padding-top: 33.33%; max-width: 150px; } 
    /* 500x500 Aspect Ratio (1:1 -> 100%) */
    .preview-500x500 { padding-top: 100%; } 
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Facebook Ads</a></li>
            <li class="breadcrumb-item active" aria-current="page">Page Content</li>
        </ol>
    </nav>
    @include('flash_message')

    <form action="{{ route('facebookAds.page.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" novalidate id="contentForm">
        @csrf
        
        {{-- Hero Section --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Hero Section</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="mb-3"><label for="hero_title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="hero_title" name="hero_title" value="{{ old('hero_title', $content->hero_title ?? '') }}" required></div>
                        <div class="mb-3"><label for="hero_description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="hero_description" name="hero_description" rows="5" required>{{ old('hero_description', $content->hero_description ?? '') }}</textarea></div>
                        <div class="mb-3"><label for="hero_button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="hero_button_text" name="hero_button_text" value="{{ old('hero_button_text', $content->hero_button_text ?? '') }}" required></div>
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

        {{-- Stats Bar Section --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Stats Bar Section</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="stats_partner_logo" class="form-label">Partner Logo</label>
                        <input type="file" class="form-control" id="stats_partner_logo" name="stats_partner_logo" accept="image/*">
                        <small class="form-text text-muted">Recommended: 150x50 px. Leave blank to keep current.</small>
                        <div class="image-preview-box preview-150x50 mt-2">
                            <img id="statsLogoPreview" src="{{ $content->stats_partner_logo ? asset($content->stats_partner_logo) : '#' }}" alt="Preview" style="{{ $content->stats_partner_logo ? '' : 'display:none;' }}">
                            <span class="placeholder-text" style="{{ $content->stats_partner_logo ? 'display:none;' : '' }}">Logo (150x50)</span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <label for="stats_partner_title" class="form-label">Partner Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="stats_partner_title" name="stats_partner_title" value="{{ old('stats_partner_title', $content->stats_partner_title ?? '') }}" required>
                    </div>
                </div>
                <hr>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <label for="stats_exp_number" class="form-label">Stat 1: Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="stats_exp_number" name="stats_exp_number" value="{{ old('stats_exp_number', $content->stats_exp_number ?? '') }}" required>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="stats_exp_title" class="form-label">Stat 1: Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="stats_exp_title" name="stats_exp_title" value="{{ old('stats_exp_title', $content->stats_exp_title ?? '') }}" required>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="stats_client_number" class="form-label">Stat 2: Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="stats_client_number" name="stats_client_number" value="{{ old('stats_client_number', $content->stats_client_number ?? '') }}" required>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="stats_client_title" class="form-label">Stat 2: Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="stats_client_title" name="stats_client_title" value="{{ old('stats_client_title', $content->stats_client_title ?? '') }}" required>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="stats_revenue_number" class="form-label">Stat 3: Number <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="stats_revenue_number" name="stats_revenue_number" value="{{ old('stats_revenue_number', $content->stats_revenue_number ?? '') }}" required>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label for="stats_revenue_title" class="form-label">Stat 3: Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="stats_revenue_title" name="stats_revenue_title" value="{{ old('stats_revenue_title', $content->stats_revenue_title ?? '') }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Campaign Section --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Campaign Section</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label for="campaign_section_title" class="form-label">Section Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="campaign_section_title" name="campaign_section_title" value="{{ old('campaign_section_title', $content->campaign_section_title ?? '') }}" required>
                    </div>
                    <div class="col-md-5">
                        <label for="campaign_image" class="form-label">Campaign Image</label>
                        <input type="file" class="form-control" id="campaign_image" name="campaign_image" accept="image/*">
                        <small class="form-text text-muted">Recommended: 500x500 px. Leave blank to keep current.</small>
                        <div class="image-preview-box preview-500x500 mt-2">
                            <img id="campaignImagePreview" src="{{ $content->campaign_image ? asset($content->campaign_image) : '#' }}" alt="Preview" style="{{ $content->campaign_image ? '' : 'display:none;' }}">
                            <span class="placeholder-text" style="{{ $content->campaign_image ? 'display:none;' : '' }}">Image Preview (500x500)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Other Section Titles --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Other Section Titles</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="pricing_section_title" class="form-label">Pricing Section Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="pricing_section_title" name="pricing_section_title" value="{{ old('pricing_section_title', $content->pricing_section_title ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="faq_section_title" class="form-label">FAQ Section Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="faq_section_title" name="faq_section_title" value="{{ old('faq_section_title', $content->faq_section_title ?? '') }}" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- CTA Bar Section --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white"><h5 class="card-title mb-0">Final CTA Bar</h5></div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="cta_title" class="form-label">CTA Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="cta_title" name="cta_title" value="{{ old('cta_title', $content->cta_title ?? '') }}" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="cta_button_text" class="form-label">Button Text <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="cta_button_text" name="cta_button_text" value="{{ old('cta_button_text', $content->cta_button_text ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="cta_button_link" class="form-label">Button Link <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="cta_button_link" name="cta_button_link" value="{{ old('cta_button_link', $content->cta_button_link ?? '#') }}" required>
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
        // Only Hero Description uses Summernote
        $('#hero_description').summernote({ height: 120, toolbar: [
            ['style', ['bold', 'italic', 'underline']], ['para', ['ul', 'ol']]
        ]});

        // Image Preview Handler
        function handleImagePreview(inputId, previewId, placeholderClass) {
            $("#" + inputId).change(function() {
                const input = this, preview = $('#' + previewId), placeholder = preview.siblings(placeholderClass);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                    reader.readAsDataURL(input.files[0]);
                } else { 
                    // Reset to original if it exists, otherwise hide
                    const originalSrc = preview.data('original-src');
                    if (originalSrc) {
                        preview.attr('src', originalSrc).show();
                        placeholder.hide();
                    } else {
                        preview.attr('src', '#').hide(); 
                        placeholder.show();
                    }
                }
            });
        }
        
        // Store original sources
        $('#heroImagePreview').data('original-src', $('#heroImagePreview').attr('src'));
        $('#statsLogoPreview').data('original-src', $('#statsLogoPreview').attr('src'));
        $('#campaignImagePreview').data('original-src', $('#campaignImagePreview').attr('src'));

        // Init previews
        handleImagePreview('hero_image', 'heroImagePreview', '.placeholder-text');
        handleImagePreview('stats_partner_logo', 'statsLogoPreview', '.placeholder-text');
        handleImagePreview('campaign_image', 'campaignImagePreview', '.placeholder-text');

        // Bootstrap validation
        $('form').submit(function(e) {
            if (!this.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
            $(this).addClass('was-validated');
        });
    });
</script>
@endsection