@extends('admin.master.master')

@section('title')
Add New Publication | {{ $ins_name }}
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
        display: none;     /* Hidden initially */
    }
    .image-preview-box .placeholder-text {
        font-size: 0.9rem;
        text-align: center;
        padding: 1rem;
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb Navigation --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('publication.index') }}">Publications</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Publication</h5>
        </div>
        <div class="card-body">
            {{-- Flash Messages & Validation Errors --}}
            @include('flash_message')
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

            {{-- Create Form --}}
            <form action="{{ route('publication.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createPublicationForm">
                @csrf
                <div class="row g-3">
                    {{-- Title --}}
                    <div class="col-md-8">
                        <label for="title" class="form-label">Publication Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Date --}}
                    <div class="col-md-4">
                        <label for="date" class="form-label">Publication Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') }}" autocomplete="off" required placeholder="YYYY-MM-DD">
                         @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- PDF File --}}
                     <div class="col-md-6">
                        <label for="pdf_file" class="form-label">Upload PDF <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf,application/pdf" required>
                        <small class="form-text text-muted">Max file size: 5MB.</small>
                         @error('pdf_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    {{-- Image File --}}
                    <div class="col-md-6">
                        <label for="image" class="form-label">Cover Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                        <small class="form-text text-muted">Size: 600px (Width) x 400px (Height), Max: 1MB</small>
                        @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        {{-- Image Preview Box --}}
                        <div class="image-preview-box">
                            <img id="imagePreview" src="#" alt="Image Preview">
                            <span class="placeholder-text">Image Preview<br>(600 x 400)</span>
                        </div>
                    </div>
                    {{-- Description --}}
                     <div class="col-12">
                         <label for="description" class="form-label">Description (Optional)</label>
                         <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                         <div id="description-error" class="form-error"></div> {{-- JS validation message placeholder --}}
                                                                       <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                         @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Publication</button>
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
                dateFormat: "Y-m-d", // Standard SQL date format
                allowInput: true      // Allow manual typing
            });
         } catch (e) { console.warn("Flatpickr failed to initialize.")}
         try {
             $('.summernote').summernote({
                 height: 150, // Suitable height for optional field
                 toolbar: [
                    // Simple toolbar for optional description
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']] // Keep codeview for advanced users
                 ],
                 // No validation callback needed as it's optional
             });
        } catch (e) { console.warn("Summernote failed to initialize.")}

        // --- Image Preview Logic ---
        $("#image").change(function() {
            const input = this;
            const preview = $('#imagePreview');
            const placeholder = $('.placeholder-text'); // Get the placeholder text span

            if (input.files && input.files[0]) {
                // Optional: Client-side file size check
                const fileSize = input.files[0].size / 1024; // in KB
                if (fileSize > 1024) { // Max 1MB
                    // Use SweetAlert for a nicer message
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Too Large',
                        text: 'Image size should not exceed 1MB.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    $(this).val(''); // Clear the input
                    preview.attr('src', '#').hide(); // Reset preview
                    placeholder.show();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show(); // Show preview img
                    placeholder.hide(); // Hide placeholder text
                };
                reader.readAsDataURL(input.files[0]); // Read file as Data URL
            } else {
                 preview.attr('src', '#').hide(); // Hide preview img
                 placeholder.show(); // Show placeholder text
            }
        });

        // --- Form Submission Validation Trigger ---
        $('form#createPublicationForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);

            // Reset previous custom errors and Bootstrap states
            $('.form-error').empty();
            // $form.find('.is-invalid').removeClass('is-invalid'); // Optionally reset all fields

            // 1. Trigger standard HTML5 validation first
            if ($form[0].checkValidity() === false) {
                isValid = false;
                 // Find first invalid standard field for better UX
                 $form.find(':invalid').first().focus();
            }

            // No custom validation needed for optional Summernote or Select2 in this form

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
         $('form#createPublicationForm input, form#createPublicationForm select').on('input change', function() {
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