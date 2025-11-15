@extends('admin.master.master')

@section('title')
Edit Publication | {{ $ins_name }}
@endsection

@section('css')
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{-- Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* Style for validation errors */
    .form-error {
        font-size: 0.875em;
        margin-top: 0.25rem;
        color: var(--danger-color, #dc3545); /* Use Bootstrap's danger color variable */
    }
    /* Style for Summernote error border */
    .note-editor.note-frame.is-invalid {
        border-color: var(--danger-color, #dc3545) !important;
    }
    /* Style for the image preview box */
    .image-preview-box {
        position: relative;
        width: 100%;
        max-width: 300px; /* Adjust max-width as needed (600/2) */
        height: 200px;    /* Adjust height based on aspect ratio (400/2) */
        border: 2px dashed #ced4da; /* Dashed border */
        border-radius: .375rem; /* Bootstrap border radius */
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa; /* Light background */
        color: #6c757d;      /* Muted text color */
        overflow: hidden;    /* Hide parts of image that don't fit */
        margin-top: 1rem;    /* Spacing above the box */
    }
    .image-preview-box img {
        width: 100%;       /* Image takes full width of box */
        height: 100%;      /* Image takes full height of box */
        object-fit: contain; /* Show whole image, pillar/letterbox if needed */
        /* display: none; */ /* Initially hidden only if no current image */
    }
    .image-preview-box .placeholder-text {
        font-size: 0.9rem;
        text-align: center;
        padding: 1rem;
    }
    /* Current PDF Link Style */
    .current-pdf-link {
        display: inline-block;
        margin-top: 5px;
        text-decoration: none; /* Optional: remove underline */
        color: #0d6efd; /* Bootstrap primary blue */
    }
    .current-pdf-link:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('publication.index') }}">Publications</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ Str::limit($publication->title, 30) }}</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Publication</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            {{-- Display Validation Errors from Backend --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     Please fix the following errors:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('publication.update', $publication->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editPublicationForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    {{-- Title --}}
                    <div class="col-md-8">
                        <label for="title" class="form-label">Publication Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $publication->title) }}" required>
                         @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Date --}}
                    <div class="col-md-4">
                        <label for="date" class="form-label">Publication Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $publication->date) }}" autocomplete="off" required placeholder="YYYY-MM-DD">
                         @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- PDF File --}}
                     <div class="col-md-6">
                        <label for="pdf_file" class="form-label">Upload New PDF (Optional)</label>
                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf,application/pdf">
                        <small class="form-text text-muted">Max file size: 5MB. Leave blank to keep current file.</small>
                         @error('pdf_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                         {{-- Display current file link --}}
                         @if($publication->pdf_file)
                            <a href="{{ asset('public/'.$publication->pdf_file) }}" target="_blank" class="current-pdf-link small" title="View Current PDF">
                                <i class="fa fa-file-pdf text-danger me-1"></i> {{ basename($publication->pdf_file) }}
                            </a>
                         @else
                            <span class="current-pdf-link small text-muted">No PDF currently uploaded.</span>
                         @endif
                    </div>
                    {{-- Image File --}}
                    <div class="col-md-6">
                        <label for="image" class="form-label">Cover Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Size: 600x400 px, Max: 1MB. Leave blank to keep current.</small>
                        @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        {{-- Image Preview Box --}}
                        <div class="image-preview-box">
                             @if($publication->image)
                                <img id="imagePreview" src="{{ asset($publication->image) }}" alt="Current Image"> {{-- Adjust path if trait saves differently --}}
                                <span class="placeholder-text" style="display:none;">Image Preview<br>(600 x 400)</span>
                             @else
                                <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                                <span class="placeholder-text">Image Preview<br>(600 x 400)</span>
                             @endif
                        </div>
                    </div>
                    {{-- Description --}}
                     <div class="col-12">
                         <label for="description" class="form-label">Description (Optional)</label>
                         <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $publication->description) }}</textarea>
                         <div id="description-error" class="form-error"></div> {{-- JS validation placeholder if needed --}}
                                                                       <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                         @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Publication</button>
                    <a href="{{ route('publication.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- Summernote JS --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
{{-- SweetAlert (ensure loaded in master) --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

<script>
    $(document).ready(function() {
        // --- Initialize Plugins ---
        try {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
         } catch (e) { console.warn("Flatpickr failed to initialize.")}
         try {
             $('.summernote').summernote({
                 height: 150, // Shorter height for optional field
                 toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                 ]
                 // No validation callback needed as it's optional
             });
        } catch (e) { console.warn("Summernote failed to initialize.")}


        // --- Image Preview Logic ---
        $("#image").change(function() {
            const input = this;
            const preview = $('#imagePreview');
            const placeholder = $('.placeholder-text');

            if (input.files && input.files[0]) {
                // Optional: Client-side file size check
                const fileSize = input.files[0].size / 1024; // in KB
                if (fileSize > 1024) { // Max 1MB
                    Swal.fire({ icon: 'warning', title: 'File Too Large', text: 'Image size should not exceed 1MB.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                    $(this).val(''); // Clear the input
                    // Reset preview to original or placeholder
                    const originalSrc = '{{ $publication->image ? asset("uploads/".$publication->image) : "" }}'; // Adjust path if needed
                    if(originalSrc) { preview.attr('src', originalSrc).show(); placeholder.hide(); }
                    else { preview.attr('src', '#').hide(); placeholder.show(); }
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show();
                    placeholder.hide();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                 // If user deselects file, show the original image or placeholder
                 const originalSrc = '{{ $publication->image ? asset("uploads/".$publication->image) : "" }}'; // Adjust path if needed
                 if(originalSrc) {
                     preview.attr('src', originalSrc).show();
                     placeholder.hide();
                 } else {
                    preview.attr('src', '#').hide(); // Hide preview img if no original
                    placeholder.show(); // Show placeholder text
                 }
            }
        });


        // --- Form Submission Validation Trigger ---
        $('form#editPublicationForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);

            // Reset previous custom errors and Bootstrap states
            $('.form-error').empty();
            // $form.find('.is-invalid').removeClass('is-invalid'); // Optionally reset all

            // 1. Trigger standard HTML5 validation first
            if ($form[0].checkValidity() === false) {
                isValid = false;
                 // Find first invalid standard field
                 $form.find(':invalid').first().focus();
            }

            // No custom validation needed for optional Summernote or Select2

            // If any validation failed, prevent submission
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();

                 // Scroll to the first visible error message or invalid field
                 const firstError = $form.find('.is-invalid').first(); // Find standard invalid
                 if (firstError.length) {
                     $('html, body').animate({
                         scrollTop: firstError.offset().top - 100 // Adjust offset
                     }, 500);
                 }
            }

            // Add 'was-validated' class AFTER checking to show Bootstrap feedback styles
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on Input/Change ---
         // For standard HTML5 fields handled by Bootstrap
         $('form#editPublicationForm input, form#editPublicationForm select').on('input change', function() {
             // Only remove 'is-invalid' if required and now has a value,
             // OR if it's not required at all.
             // Also handles file input check.
             if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) || ($(this).attr('type') === 'file' && this.files.length > 0) ) {
                 $(this).removeClass('is-invalid');
             } else if ($(this).prop('required')) {
                 // If it's required and becomes empty, re-add invalid class
                 // Bootstrap's 'was-validated' usually handles showing it on submit,
                 // but this provides slightly more immediate feedback if desired.
                 $(this).addClass('is-invalid');
             }
         });

    }); // End $(document).ready
</script>
@endsection