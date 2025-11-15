@extends('admin.master.master')

@section('title')
Add New Slider | {{ $ins_name }}
@endsection

@section('css')
{{-- Summernote CSS (Optional, if using for description) --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet"> --}}
<style>
    /* Styles for Validation Error, Image Preview */
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
    .image-preview-box {
        position: relative; width: 100%; max-width: 512px; /* 2560/5 */ height: 384px; /* 1920/5 */
        border: 2px dashed #ced4da; border-radius: .375rem; display: flex;
        align-items: center; justify-content: center; background-color: #f8f9fa;
        color: #6c757d; overflow: hidden; margin-top: 1rem;
    }
    .image-preview-box img { width: 100%; height: 100%; object-fit: contain; }
    .image-preview-box .placeholder-text { font-size: 0.9rem; text-align: center; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('slider.index') }}">Sliders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Slider</h5>
        </div>
        <div class="card-body">
            @include('flash_message')
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     Please fix the following errors:
                    <ul class="mb-0 mt-2">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createSliderForm">
                @csrf
                <div class="row g-3">
                    {{-- Title --}}
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title (Optional)</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Subtitle --}}
                     <div class="col-md-6">
                        <label for="subtitle" class="form-label">Subtitle (Optional)</label>
                        <input type="text" class="form-control @error('subtitle') is-invalid @enderror" id="subtitle" name="subtitle" value="{{ old('subtitle') }}">
                        @error('subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Short Description --}}
                    <div class="col-12">
                         <label for="short_description" class="form-label">Short Description (Optional)</label>
                         <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="3">{{ old('short_description') }}</textarea>
                         @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                     {{-- Image Upload --}}
                    <div class="col-12">
                         <hr>
                        <label for="image" class="form-label">Slider Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                        <small class="form-text text-muted">Required Size: 2560px (Width) x 1920px (Height), Max: 5MB</small>
                        @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        {{-- Image Preview --}}
                        <div class="image-preview-box">
    {{-- Add style="display:none;" to hide the broken image icon on load --}}
    <img id="imagePreviewS" src="#" alt="Image Preview" style="display:none;"> 
    <span class="placeholder-text">Image Preview<br>(2560 x 1920)</span>
</div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Slider</button>
                    <a href="{{ route('slider.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Summernote JS (Optional) --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script> --}}
{{-- SweetAlert --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

<script>
    $(document).ready(function() {
        // --- Initialize Plugins (if any needed, e.g., Summernote for description) ---
        // try { $('#short_description').summernote({ height: 100, /* simple toolbar */ }); } catch (e) {}

        // --- Image Preview Logic ---
        $("#image").change(function() {
            const input = this; const preview = $('#imagePreviewS'); const placeholder = $('.placeholder-text');
            if (input.files && input.files[0]) {
                const fileSize = input.files[0].size / 1024 / 1024; // MB
                if (fileSize > 5) { // Max 5MB Check
                     Swal.fire({ icon: 'warning', title: 'File Too Large', text: 'Image size should not exceed 5MB.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                     $(this).val(''); preview.attr('src','#').hide(); placeholder.show(); return;
                 }
                const reader = new FileReader();
                reader.onload = function(e) { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                reader.readAsDataURL(input.files[0]);
            } else { preview.attr('src','#').hide(); placeholder.show(); }
        });

        // --- Form Submission Validation Trigger ---
        $('form#createSliderForm').submit(function(e) {
            let isValid = true; let $form = $(this);
            $('.form-error').empty(); $('.is-invalid').removeClass('is-invalid');

            // Standard HTML5 Validation
            if ($form[0].checkValidity() === false) {
                isValid = false; $form.find(':invalid').first().focus();
            }

            // No custom validation needed here unless Summernote/Select2 used

            if (!isValid) {
                e.preventDefault(); e.stopPropagation();
                 const firstError = $form.find('.is-invalid').first();
                 if (firstError.length) { $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); }
            }
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on Input/Change ---
         $('form#createSliderForm input, form#createSliderForm textarea').on('input change', function() {
             // Basic clearing for standard fields
              if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) || ($(this).attr('type') === 'file' && this.files.length > 0) ) {
                 $(this).removeClass('is-invalid');
             } else if ($(this).prop('required')) {
                  $(this).addClass('is-invalid');
             }
         });

    });
</script>
@endsection