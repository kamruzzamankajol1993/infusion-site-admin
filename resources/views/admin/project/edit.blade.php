@extends('admin.master.master')

@section('title')
Edit Project | {{ $ins_name }}
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
    /* Gallery Display & Delete */
    .gallery-current-container { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 10px; padding-top: 10px; border-top: 1px solid #eee; }
    .gallery-current-item { position: relative; width: 180px; height: 120px; border-radius: 5px; overflow: hidden; border: 1px solid #ddd; }
    .gallery-current-item img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-delete-btn {
        position: absolute; top: 2px; right: 2px; width: 25px; height: 25px;
        background-color: rgba(211, 47, 47, 0.9); color: white; border: none;
        border-radius: 50%; font-size: 12px; line-height: 25px; text-align: center;
        cursor: pointer; transition: background-color 0.2s ease; padding: 0;
        display: flex; align-items: center; justify-content: center;
    }
    .gallery-delete-btn:hover { background-color: #d32f2f; }
    /* Gallery Preview for NEW uploads */
    .gallery-preview-container { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px; }
    .gallery-preview-item {
        position: relative;
        width: 150px; height: 100px;
        border-radius: 5px; overflow: hidden; border: 1px solid #ddd;
    }
    .gallery-preview-item img { width: 100%; height: 100%; object-fit: cover; }
    /* --- NEW: Remove Button Style (Copied from Create) --- */
    .remove-preview-btn {
        position: absolute; top: 2px; right: 2px; width: 20px; height: 20px;
        background-color: rgba(255, 0, 0, 0.7); color: white; border: none;
        border-radius: 50%; font-size: 12px; line-height: 20px; text-align: center;
        cursor: pointer; font-weight: bold; transition: background-color 0.2s ease; padding: 0;
        display: flex; align-items: center; justify-content: center;
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
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $project->title }}</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Project</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            {{-- Display Validation Errors --}}
            @if ($errors->any())
                {{-- ... error display ... --}}
            @endif

            <form action="{{ route('project.update', $project->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editProjectForm">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-4">
                    {{-- Project Details --}}
                    <div class="col-md-6">
                        <label for="title" class="form-label">Project Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $project->title) }}" required>
                         @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- ADD THIS NEW BLOCK --}}
                    <div class="col-md-6">
                        <label for="service" class="form-label">Service (Optional)</label>
                        <input type="text" class="form-control @error('service') is-invalid @enderror" id="service" name="service" value="{{ old('service', $project->service) }}" placeholder="e.g., Advisory, Transaction Support">
                        @error('service') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- END OF NEW BLOCK --}}
                    <div class="col-md-4">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select select2-basic @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="" disabled>Select Category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $project->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="category_id-error" class="form-error"></div>
                        @error('category_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                        <select class="form-select select2-basic @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                            <option value="" disabled>Select Client...</option>
                             @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="client_id-error" class="form-error"></div>
                         @error('client_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="country_id" class="form-label">Country <span class="text-danger">*</span></label>
                        <select class="form-select select2-basic @error('country_id') is-invalid @enderror" id="country_id" name="country_id" required>
                            <option value="" disabled>Select Country...</option>
                             @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $project->country_id) == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="country_id-error" class="form-error"></div>
                         @error('country_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ old('status', $project->status) == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                         @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="agreement_signing_date" class="form-label">Agreement Signing Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('agreement_signing_date') is-invalid @enderror" id="agreement_signing_date" name="agreement_signing_date" value="{{ old('agreement_signing_date', $project->agreement_signing_date) }}" autocomplete="off" required>
                        @error('agreement_signing_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label d-block">Flagship Project? <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input @error('is_flagship') is-invalid @enderror" type="radio" name="is_flagship" id="is_flagship_no" value="0" {{ old('is_flagship', $project->is_flagship) == 0 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="is_flagship_no">No</label>
                        </div>
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input @error('is_flagship') is-invalid @enderror" type="radio" name="is_flagship" id="is_flagship_yes" value="1" {{ old('is_flagship', $project->is_flagship) == 1 ? 'checked' : '' }} required>
                            <label class="form-check-label" for="is_flagship_yes">Yes</label>
                        </div>
                        @error('is_flagship') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                         <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                         <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description" required>{{ old('description', $project->description) }}</textarea>
                         <div id="description-error" class="form-error"></div>
                                       <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                         @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                 </div>

                 <hr class="my-4">

                 {{-- Gallery Images --}}
                 <div class="mb-4">
                    <h6 class="text-primary mb-3">Project Gallery Images</h6>

                    {{-- Display Current Images with Delete Button --}}
                    @if($project->galleryImages->isNotEmpty())
                        <label class="form-label d-block mb-2">Current Images (Click 'x' to remove):</label>
                        <div class="gallery-current-container mb-3" id="currentGalleryContainer">
                            @foreach($project->galleryImages as $image)
                            <div class="gallery-current-item" id="gallery-item-{{ $image->id }}">
                                <img src="{{ asset($image->image_path) }}" alt="Gallery Image {{ $image->id }}">
                                @if(Auth::user()->can('projectDelete'))
                                <button type="button" class="gallery-delete-btn" data-image-id="{{ $image->id }}" title="Delete Saved Image">
                                    <i class="fa fa-times"></i>
                                </button>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                       <p class="text-muted small" id="no-images-placeholder">No gallery images currently uploaded.</p>
                    @endif

                    {{-- Upload New Images --}}
                    <label for="gallery_images" class="form-label mt-3">Upload New Images (Optional)</label>
                    <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror @error('gallery_images') is-invalid @enderror" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                    <small class="form-text text-muted">Recommended size: 600px (Width) x 400px (Height), Max file size: 2MB each.</small>
                     @error('gallery_images') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    @error('gallery_images.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    {{-- Preview Container for New Images --}}
                    <div class="gallery-preview-container" id="galleryPreviewContainer">
                        {{-- New previews will appear here --}}
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update Project</button>
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
{{-- SweetAlert (ensure this is loaded in master.blade.php) --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

<script>
    $(document).ready(function() {
        // --- Initialize Plugins ---
        try {
             $('.select2-basic').select2({
                 placeholder: "Select...",
                 allowClear: true,
                 width: '100%' // Ensure proper width
             });
         } catch (e) { console.warn("Select2 failed to initialize.")}
        try {
             flatpickr(".datepicker", {
                 dateFormat: "Y-m-d", // Standard SQL date format
                 allowInput: true      // Allow manual typing
             });
         } catch (e) { console.warn("Flatpickr failed to initialize.")}
        try {
             $('.summernote').summernote({
                 height: 250, // Or your preferred height
                 toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']], // Keep picture if needed inside description
                    ['view', ['fullscreen', 'codeview', 'help']]
                 ],
                callbacks: { // For validation feedback
                    onChange: function(contents, $editable) { validateSummernote($(this)); }
                }
             });
        } catch (e) { console.warn("Summernote failed to initialize.")}

        // --- Gallery Image Preview (for NEW uploads in Edit form) ---
        $('#gallery_images').on('change', function(event) {
            const previewContainer = $('#galleryPreviewContainer');
            previewContainer.empty(); // Clear previous NEW previews
            const files = event.target.files;
            if (files && files.length > 0) {
                // Optional: Limit number of new files user can select
                // if (files.length > 10) {
                //     Swal.fire('Limit Exceeded', 'You can upload a maximum of 10 new images at a time.', 'warning');
                //     $(this).val(''); // Clear the input
                //     return;
                // }

                $.each(files, function(i, file) {
                    // Optional: Client-side size check before reading
                    // if(file.size > 2 * 1024 * 1024) { // 2MB
                    //     previewContainer.append('<div class="text-danger small p-2">Image "' + file.name + '" exceeds 2MB limit and will not be uploaded.</div>');
                    //     return true; // continue to next file, but don't add preview
                    // }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgHtml = `
                        <div class="gallery-preview-item">
                            <img src="${e.target.result}" alt="New image preview ${i+1}">
                            <button type="button" class="remove-preview-btn" data-file-index="${i}" title="Remove New Image">&times;</button>
                        </div>`;
                        previewContainer.append(imgHtml);
                    }
                    reader.readAsDataURL(file); // Read the file content as Data URL
                 });
            }
        });

        // --- Remove NEW Preview Button Click Handler ---
        $(document).on('click', '.remove-preview-btn', function() {
            // Warn user that removing one clears all newly selected files
             Swal.fire({
                title: 'Clear New Selection?',
                text: "Removing a newly selected image requires clearing all new selections. You'll need to re-select the new images you want to upload. Previously saved images are unaffected. Continue?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, clear selection!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#gallery_images').val(''); // Clear the file input element
                    $('#galleryPreviewContainer').empty(); // Remove all NEW previews from the DOM
                    // Optionally trigger 'change' if other scripts rely on it
                    // $('#gallery_images').trigger('change');
                }
            });
        });


        // --- Delete EXISTING Gallery Image (AJAX) ---
        $(document).on('click', '.gallery-delete-btn', function() {
            const button = $(this);
            const imageId = button.data('image-id');
            const deleteUrl = `{{ route('project.gallery.delete', ':id') }}`.replace(':id', imageId); // Use named route

            Swal.fire({
                title: 'Delete this saved image?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Disable button and show spinner temporarily
                    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                     $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" // Ensure CSRF token is sent
                        },
                        success: function(response) {
                            Swal.fire({
                                toast: true, icon: 'success',
                                title: response.message || 'Image deleted successfully!',
                                position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true
                            });
                            // Remove the image container visually after success
                            $(`#gallery-item-${imageId}`).fadeOut(400, function() {
                                $(this).remove();
                                // Check if the container for current images is now empty
                                if ($('#currentGalleryContainer .gallery-current-item').length === 0) {
                                     // Add placeholder text if it doesn't already exist
                                     if(!$('#no-images-placeholder').length) {
                                         $('#currentGalleryContainer').before('<p class="text-muted small" id="no-images-placeholder">No gallery images currently uploaded.</p>');
                                     }
                                }
                            });
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Deletion Failed!',
                                xhr.responseJSON?.error || 'Could not delete the image. Please try again.',
                                'error'
                            );
                            // Re-enable the button on error
                            button.prop('disabled', false).html('<i class="fa fa-times"></i>');
                        }
                    });
                }
            });
        });


        // --- Custom Client-Side Validation Functions ---
        function validateSummernote($element) {
             const $editor = $element.siblings('.note-editor'); // Target the generated editor div
             const $errorDiv = $element.siblings('.form-error'); // Target the custom error div
             $errorDiv.empty(); // Clear previous errors
             $editor.removeClass('is-invalid'); // Clear border style from editor
             $element.removeClass('is-invalid'); // Clear style from original textarea (if any)

             // Check if the field is required and if Summernote content is empty
             if ($element.prop('required') && $element.summernote('isEmpty')) {
                 $errorDiv.text('This field is required.'); // Show error message
                 $editor.addClass('is-invalid'); // Add red border to editor
                 $element.addClass('is-invalid'); // Also mark original textarea (useful for some selectors)
                 return false; // Invalid
             }
             return true; // Valid
        }

         function validateSelect2($element) {
             const $container = $element.siblings('.select2-container').find('.select2-selection'); // Target Select2 container
             const $errorDiv = $element.siblings('.form-error'); // Target custom error div
             $errorDiv.empty(); // Clear previous errors
             $container.removeClass('is-invalid'); // Clear border style from Select2
             $element.removeClass('is-invalid'); // Clear style from original select

             // Check if required and has no value or only the placeholder value
             if ($element.prop('required') && (!$element.val() || $element.val() === '')) {
                 $errorDiv.text('This field is required.'); // Show error message
                 $container.addClass('is-invalid'); // Add red border to Select2 container
                 $element.addClass('is-invalid'); // Mark original select
                 return false; // Invalid
             }
             return true; // Valid
         }

        // --- Form Submission Validation Trigger ---
        $('form#editProjectForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);

            // Reset custom errors first
            $('.form-error').empty();
            $('.is-invalid').removeClass('is-invalid'); // Clear previous Bootstrap errors if any

            // 1. Trigger standard HTML5 validation and check validity
            if ($form[0].checkValidity() === false) {
                isValid = false;
                // Find first invalid standard field and focus (improves UX)
                 $form.find(':invalid').first().focus();
            }

            // 2. Validate all Summernote fields within this form
            $form.find('.summernote[required]').each(function() { // Only check required ones
                if (!validateSummernote($(this))) {
                    isValid = false;
                }
            });

            // 3. Validate all required Select2 fields within this form
            $form.find('.select2-basic[required]').each(function() {
                if (!validateSelect2($(this))) {
                    isValid = false;
                }
            });


            // If any validation failed, prevent submission
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();

                 // Scroll to the first visible error message or invalid field
                 const firstError = $('.is-invalid, .form-error:not(:empty)').first();
                 if (firstError.length) {
                     $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); // Adjust offset as needed
                 }
            }

            // Add 'was-validated' class AFTER checking to show Bootstrap feedback styles correctly
            // This needs to happen regardless of validity to show feedback for standard fields
            $form.addClass('was-validated');
        });

         // --- Clear Select2 error feedback on change ---
        $('.select2-basic').on('change', function() {
            // Re-validate only if it's a required field
            if ($(this).prop('required')) {
                 validateSelect2($(this));
            } else {
                 // If not required, just clear any previous error styles
                  $(this).removeClass('is-invalid');
                  $(this).siblings('.select2-container').find('.select2-selection').removeClass('is-invalid');
                  $(this).siblings('.form-error').empty();
            }
         });

    }); // End $(document).ready
</script>
@endsection