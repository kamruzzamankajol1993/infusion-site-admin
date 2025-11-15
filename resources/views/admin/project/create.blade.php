@extends('admin.master.master')

@section('title')
Add New Project | {{ $ins_name }}
@endsection

@section('css')
{{-- Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Select2 Height Fix */
    .select2-container .select2-selection--single { height: 38px !important; border: 1px solid #ced4da; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
    .select2-container--default .select2-selection--multiple { border-color: #ced4da; }
    /* Summernote Validation Error */
    .note-editor.note-frame.is-invalid { border-color: var(--danger-color, #dc3545) !important; }
    /* General Validation Error */
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
    /* Gallery Preview */
    .gallery-preview-container { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee; }
    .gallery-preview-item {
        position: relative;
        width: 150px; /* Adjust based on 600x400 ratio */
        height: 100px;
        overflow: hidden; /* Ensure button stays within bounds */
        border-radius: 5px;
        border: 1px solid #ddd;
    }
    .gallery-preview-item img { width: 100%; height: 100%; object-fit: cover; }
    /* --- NEW: Remove Button Style --- */
    .remove-preview-btn {
        position: absolute;
        top: 2px;
        right: 2px;
        width: 20px;
        height: 20px;
        background-color: rgba(255, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 12px;
        line-height: 20px;
        text-align: center;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.2s ease;
        padding: 0;
        display: flex; /* Use flexbox to center content */
        align-items: center;
        justify-content: center;
    }
    .remove-preview-btn:hover { background-color: rgba(255, 0, 0, 1); }
    /* --- END NEW --- */
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Project</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            {{-- Display Validation Errors --}}
            @if ($errors->any())
                {{-- ... error display ... --}}
            @endif

            <form action="{{ route('project.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createProjectForm">
                @csrf

                <div class="row g-3 mb-4">
                    {{-- Project Details --}}
                    <div class="col-md-6">
                        <label for="title" class="form-label">Project Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- ADD THIS NEW BLOCK --}}
                    <div class="col-md-6">
                        <label for="service" class="form-label">Service (Optional)</label>
                        <input type="text" class="form-control @error('service') is-invalid @enderror" id="service" name="service" value="{{ old('service') }}" placeholder="e.g., Advisory, Transaction Support">
                        @error('service') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- END OF NEW BLOCK --}}
                    <div class="col-md-4">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select select2-basic @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="" disabled selected>Select Category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div id="category_id-error" class="form-error"></div>
                        @error('category_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                        <select class="form-select select2-basic @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                            <option value="" disabled selected>Select Client...</option>
                             @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                            @endforeach
                        </select>
                        <div id="client_id-error" class="form-error"></div>
                        @error('client_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="country_id" class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-select select2-basic @error('country_id') is-invalid @enderror" id="country_id" name="country_id" required>
                            <option value="" disabled selected>Select Country...</option>
                             @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        <div id="country_id-error" class="form-error"></div>
                        @error('country_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ old('status', 'pending') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                         @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="agreement_signing_date" class="form-label">Agreement Signing Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('agreement_signing_date') is-invalid @enderror" id="agreement_signing_date" name="agreement_signing_date" value="{{ old('agreement_signing_date') }}" autocomplete="off" required>
                         @error('agreement_signing_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label d-block">Flagship Project? <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input @error('is_flagship') is-invalid @enderror" type="radio" name="is_flagship" id="is_flagship_no" value="0" {{ old('is_flagship', 0) == 0 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="is_flagship_no">No</label>
                        </div>
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input @error('is_flagship') is-invalid @enderror" type="radio" name="is_flagship" id="is_flagship_yes" value="1" {{ old('is_flagship') == 1 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="is_flagship_yes">Yes</label>
                        </div>
                        @error('is_flagship') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                         <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                         <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description" required>{{ old('description') }}</textarea>
                         <div id="description-error" class="form-error"></div>
                                       <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                         @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <hr class="my-4">

                {{-- Gallery Images --}}
                <div class="mb-4">
                    <h6 class="text-primary mb-3">Project Gallery Images</h6>
                    <label for="gallery_images" class="form-label">Upload Images (Multiple Allowed)</label>
                    <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror @error('gallery_images') is-invalid @enderror" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                    <small class="form-text text-muted">Recommended size: 600px (Width) x 400px (Height), Max file size: 2MB each.</small>
                    @error('gallery_images') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    @error('gallery_images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    {{-- Preview Container --}}
                    <div class="gallery-preview-container" id="galleryPreviewContainer">
                        {{-- Previews will appear here --}}
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Save Project</button>
                    <a href="{{ route('project.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Summernote --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
{{-- Select2 --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function() {
        // --- Initialize Plugins ---
        try {
            $('.select2-basic').select2({
                placeholder: "Select...",
                allowClear: true,
                width: '100%' // Ensure full width
            });
        } catch (e) { console.warn("Select2 failed.")}
        try {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
         } catch (e) { console.warn("Flatpickr failed.")}
        try {
             $('.summernote').summernote({
                 height: 250,
                 toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']], // Allow inserting pictures
                    ['view', ['fullscreen', 'codeview', 'help']]
                 ],
                 callbacks: { // For validation
                     onChange: function(contents, $editable) { validateSummernote($(this)); }
                 }
             });
        } catch (e) { console.warn("Summernote failed.")}

        // --- Gallery Image Preview ---
       // --- Gallery Image Preview & Remove ---
        $('#gallery_images').on('change', function(event) {
            const previewContainer = $('#galleryPreviewContainer');
            previewContainer.empty(); // Clear existing previews
            const files = event.target.files;
            if (files && files.length > 0) {
                $.each(files, function(i, file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // --- MODIFIED: Added remove button ---
                        const imgHtml = `
                        <div class="gallery-preview-item">
                            <img src="${e.target.result}" alt="New image preview ${i+1}">
                            <button type="button" class="remove-preview-btn" data-file-index="${i}" title="Remove Image">&times;</button>
                        </div>`;
                        // --- END MODIFICATION ---
                        previewContainer.append(imgHtml);
                    }
                    reader.readAsDataURL(file);
                });
            }
        });

        // --- NEW: Remove Preview Button Click Handler ---
        $(document).on('click', '.remove-preview-btn', function() {
            // Because we cannot modify the FileList directly in a user-friendly way for standard forms,
            // the simplest approach is to clear the input and all previews.
             Swal.fire({
                title: 'Clear Selection?',
                text: "Removing an image requires clearing all selected files. You'll need to re-select the images you want to upload. Continue?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, clear selection!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#gallery_images').val(''); // Clear the file input
                    $('#galleryPreviewContainer').empty(); // Remove all previews
                    // Optionally, trigger change event if needed by other scripts
                    // $('#gallery_images').trigger('change');
                }
            });
        });
        // --- END NEW ---

        // --- Custom Client-Side Validation ---
        function validateSummernote($element) {
           const $editor = $element.siblings('.note-editor'); const $errorDiv = $element.siblings('.form-error');
           $errorDiv.empty(); $editor.removeClass('is-invalid'); $element.removeClass('is-invalid');
           if ($element.prop('required') && $element.summernote('isEmpty')) {
               $errorDiv.text('This field is required.'); $editor.addClass('is-invalid'); $element.addClass('is-invalid'); return false;
           } return true;
        }
        function validateSelect2($element) {
            const $container = $element.siblings('.select2-container').find('.select2-selection');
            const $errorDiv = $element.siblings('.form-error');
             $errorDiv.empty(); $container.removeClass('is-invalid'); $element.removeClass('is-invalid');
             if ($element.prop('required') && (!$element.val() || $element.val() === '')) {
                 $errorDiv.text('This field is required.'); $container.addClass('is-invalid'); $element.addClass('is-invalid'); return false;
             } return true;
        }

        $('form#createProjectForm').submit(function(e) {
            let isValid = true; let $form = $(this);
            // Reset custom errors
            $('.form-error').empty();
            $('.is-invalid').removeClass('is-invalid'); // Clear previous BS errors if any

            // 1. Standard HTML5 Validation
            if ($form[0].checkValidity() === false) {
                isValid = false;
                 // Find first invalid standard field and focus (optional)
                 $form.find(':invalid').first().focus();
            }

            // 2. Summernote Validation
            $form.find('.summernote').each(function() { if (!validateSummernote($(this))) isValid = false; });

            // 3. Select2 Validation (only required ones)
            if (!validateSelect2($('#category_id'))) isValid = false;


            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                 // Scroll to the first error (optional, enhances UX)
                 const firstError = $('.is-invalid, .form-error:not(:empty)').first();
                 if (firstError.length) {
                     $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                 }

            }
            // Add 'was-validated' class AFTER checking, to show Bootstrap styles only on failed submit attempt
            $form.addClass('was-validated');
        });

        // Clear Select2 error on change
        $('.select2-basic').on('change', function() {
            if ($(this).prop('required')) {
                 validateSelect2($(this));
            } else {
                 // Clear potential errors if not required and changed
                  $(this).removeClass('is-invalid');
                  $(this).siblings('.select2-container').find('.select2-selection').removeClass('is-invalid');
                  $(this).siblings('.form-error').empty();
            }
         });


    });
</script>
@endsection