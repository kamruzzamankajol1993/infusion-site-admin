@extends('admin.master.master')

@section('title')
About Us Content | {{ $ins_name }}
@endsection

@section('css')
{{-- Include Summernote CSS if you use it for descriptions --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* Professional Image Preview Box */
    .image-preview-box {
        position: relative;
        width: 100%;
        /* Maintain aspect ratio 1280:800 -> 16:10 */
        padding-top: 62.5%; /* 800 / 1280 * 100% */
        border: 2px dashed #ced4da;
        border-radius: .375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        color: #6c757d;
        overflow: hidden;
        margin-top: 1rem;
    }
    .image-preview-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain; /* Use contain to see the whole image */
    }
    .image-preview-box .placeholder-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 0.9rem;
        padding: 1rem;
        text-align: center;
    }
    .note-editor.note-frame.is-invalid {
        border-color: var(--danger-color, #F51825) !important;
    }
     .form-error {
        font-size: 0.875em;
        margin-top: 0.25rem;
        color: var(--danger-color, #F51825);
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">About Us Content</li>
        </ol>
    </nav>

    @include('flash_message')

    {{-- Determine which form to show. Assumes you pass an $aboutUs variable from the controller. --}}
    {{-- If $aboutUs exists, show Edit form; otherwise, show Create form. --}}
    @php
        $hasData = isset($aboutUs) && $aboutUs->id; // Check if data exists
    @endphp

    {{-- EDIT FORM CARD --}}
    @if($hasData)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Edit About Us Content </h5>

            {{-- Optional: Add a link back or other actions --}}
        </div>
        <div class="card-body">
            <form action="{{ route('aboutUs.update', $aboutUs->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editForm">
                @csrf
                @method('PUT')

                {{-- Mission Section --}}
                <h6 class="mt-2 text-primary">Mission</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="edit_mission_title" class="form-label">Mission Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_mission_title" name="mission_title" value="{{ old('mission_title', $aboutUs->mission_title ?? '') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="edit_mission_description" class="form-label">Mission Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="edit_mission_description" name="mission_description" required>{{ old('mission_description', $aboutUs->mission_description ?? '') }}</textarea>
                        <div id="edit_mission_description-error" class="form-error"></div>
                                      <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                    </div>
                </div>
                <hr>

                {{-- Vision Section --}}
                <h6 class="mt-3 text-primary">Vision</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="edit_vision_title" class="form-label">Vision Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_vision_title" name="vision_title" value="{{ old('vision_title', $aboutUs->vision_title ?? '') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="edit_vision_description" class="form-label">Vision Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="edit_vision_description" name="vision_description" required>{{ old('vision_description', $aboutUs->vision_description ?? '') }}</textarea>
                        <div id="edit_vision_description-error" class="form-error"></div>
                                      <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                    </div>
                </div>
                <hr>

                {{-- Objectives Section --}}
                <h6 class="mt-3 text-primary">Objectives</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="edit_objectives_title" class="form-label">Objectives Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_objectives_title" name="objectives_title" value="{{ old('objectives_title', $aboutUs->objectives_title ?? '') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="edit_objectives_description" class="form-label">Objectives Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="edit_objectives_description" name="objectives_description" required>{{ old('objectives_description', $aboutUs->objectives_description ?? '') }}</textarea>
                        <div id="edit_objectives_description-error" class="form-error"></div>
                                      <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                    </div>
                </div>
                <hr>

                {{-- Brief Description --}}
                <h6 class="mt-3 text-primary">Brief Description</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                         <label for="edit_brief_description" class="form-label">Brief Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="edit_brief_description" name="brief_description" required>{{ old('brief_description', $aboutUs->brief_description ?? '') }}</textarea>
                        <div id="edit_brief_description-error" class="form-error"></div>
                                      <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                    </div>
                </div>
                <hr>

                 {{-- IIFC Organogram --}}
                <h6 class="mt-3 text-primary">IIFC Organogram</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="edit_organogram_image" class="form-label">Upload Organogram Image</label>
                        <input type="file" class="form-control" id="edit_organogram_image" name="organogram_image" accept="image/*">
                        <small class="form-text text-muted">Recommended size: 1280px (Width) x 800px (Height). Leave blank to keep current image.</small>
                    </div>
                     <div class="col-md-12">
                         <div class="image-preview-box mt-2">
                             @if($aboutUs->organogram_image)
                                <img id="editImagePreview" src="{{ asset($aboutUs->organogram_image) }}" alt="Current Organogram">
                                <span class="placeholder-text" style="display:none;">Organogram Preview<br>(1280 x 800)</span>
                             @else
                                <img id="editImagePreview" src="#" alt="Organogram Preview" style="display:none;">
                                <span class="placeholder-text">Organogram Preview<br>(1280 x 800)</span>
                             @endif
                         </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Content</button>
                </div>
            </form>
        </div>
    </div>
    @endif


    {{-- CREATE FORM CARD --}}
    @if(!$hasData)
     <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Create About Us Content</h5>
            <small class="text-muted">Fill in the details below to add the initial "About Us" content.</small>
        </div>
        <div class="card-body">
             <form action="{{ route('aboutUs.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createForm">
                @csrf

                 {{-- Mission Section --}}
                <h6 class="mt-2 text-primary">Mission</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="create_mission_title" class="form-label">Mission Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create_mission_title" name="mission_title" value="{{ old('mission_title') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="create_mission_description" class="form-label">Mission Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="create_mission_description" name="mission_description" required>{{ old('mission_description') }}</textarea>
                        <div id="create_mission_description-error" class="form-error"></div>
                    </div>
                </div>
                <hr>

                {{-- Vision Section --}}
                <h6 class="mt-3 text-primary">Vision</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="create_vision_title" class="form-label">Vision Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create_vision_title" name="vision_title" value="{{ old('vision_title') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="create_vision_description" class="form-label">Vision Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="create_vision_description" name="vision_description" required>{{ old('vision_description') }}</textarea>
                         <div id="create_vision_description-error" class="form-error"></div>
                    </div>
                </div>
                <hr>

                {{-- Objectives Section --}}
                <h6 class="mt-3 text-primary">Objectives</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="create_objectives_title" class="form-label">Objectives Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create_objectives_title" name="objectives_title" value="{{ old('objectives_title') }}" required>
                    </div>
                    <div class="col-md-12 mt-2">
                        <label for="create_objectives_description" class="form-label">Objectives Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="create_objectives_description" name="objectives_description" required>{{ old('objectives_description') }}</textarea>
                        <div id="create_objectives_description-error" class="form-error"></div>
                    </div>
                </div>
                <hr>

                {{-- Brief Description --}}
                <h6 class="mt-3 text-primary">Brief Description</h6>
                 <div class="row mb-3">
                    <div class="col-md-12">
                         <label for="create_brief_description" class="form-label">Brief Description <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="create_brief_description" name="brief_description" required>{{ old('brief_description') }}</textarea>
                        <div id="create_brief_description-error" class="form-error"></div>
                    </div>
                </div>
                <hr>

                {{-- IIFC Organogram --}}
                 <h6 class="mt-3 text-primary">IIFC Organogram</h6>
                 <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="create_organogram_image" class="form-label">Upload Organogram Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="create_organogram_image" name="organogram_image" accept="image/*" required>
                        <small class="form-text text-muted">Recommended size: 1280px (Width) x 800px (Height).</small>
                    </div>
                     <div class="col-md-12">
                        <div class="image-preview-box mt-2">
                             <img id="createImagePreview" src="#" alt="Organogram Preview" style="display:none;">
                             <span class="placeholder-text">Organogram Preview<br>(1280 x 800)</span>
                        </div>
                    </div>
                 </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Content</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection

@section('script')
{{-- Include Summernote JS if you use it --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {

        // --- Initialize Summernote ---
        function initSummernote(selector) {
             try {
                $(selector).summernote({
                    height: 150, // Adjust height as needed
                    toolbar: [
                        ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['codeview']]
                    ],
                     callbacks: {
                        onChange: function(contents, $editable) {
                           // Trigger validation on change
                           validateSummernote($(this));
                           // If using Bootstrap validation, remove invalid state if content exists
                           if (!$(this).summernote('isEmpty')) {
                               $(this).removeClass('is-invalid');
                               $(this).siblings('.note-editor').removeClass('is-invalid'); // Target the generated editor
                               $(this).siblings('.form-error').empty();
                           }
                        }
                     }
                });
            } catch(e) { console.warn("Summernote not loaded or failed to initialize."); }
        }

        // Initialize for both forms if they exist
        if ($('#createForm').length) {
            initSummernote('#create_mission_description');
            initSummernote('#create_vision_description');
            initSummernote('#create_objectives_description');
            initSummernote('#create_brief_description');
        }
        if ($('#editForm').length) {
            initSummernote('#edit_mission_description');
            initSummernote('#edit_vision_description');
            initSummernote('#edit_objectives_description');
            initSummernote('#edit_brief_description');
        }


        // --- Image Preview Logic ---
        function handleImagePreview(inputId, previewId, placeholderClass) {
            $("#" + inputId).change(function() {
                const input = this;
                const preview = $('#' + previewId);
                const placeholder = preview.siblings(placeholderClass);

                if (input.files && input.files[0]) {
                    // Basic client-side size check (optional but good UX)
                    const fileSize = input.files[0].size / 1024; // Size in KB
                    // Add a max size check if needed, e.g., 2MB = 2048KB
                    // if (fileSize > 2048) {
                    //     alert('File size exceeds the maximum limit of 2MB.');
                    //     $(this).val(''); // Clear the input
                    //     preview.attr('src', '#').hide();
                    //     placeholder.show();
                    //     return;
                    // }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result);
                        preview.css('display', 'block');
                        placeholder.css('display', 'none'); // Hide placeholder
                    };
                    reader.readAsDataURL(input.files[0]);
                } else {
                     // Handle case where user deselects the file (might be needed in edit)
                     // If it's the edit form, show the original image? Or just the placeholder?
                     preview.attr('src', '#').hide();
                     placeholder.show();
                }
            });
        }

        // Apply preview logic to both forms
        handleImagePreview('create_organogram_image', 'createImagePreview', '.placeholder-text');
        handleImagePreview('edit_organogram_image', 'editImagePreview', '.placeholder-text');


       // --- Custom Client-Side Validation for Summernote ---
       function validateSummernote($element) {
           const $editor = $element.siblings('.note-editor'); // Target the editor wrapper
           const $errorDiv = $element.siblings('.form-error');
           $errorDiv.empty(); // Clear previous error
           $editor.removeClass('is-invalid'); // Remove error class

           if ($element.prop('required') && $element.summernote('isEmpty')) {
               $errorDiv.text('This field is required.');
               $editor.addClass('is-invalid'); // Add error class to the editor frame
               return false; // Invalid
           }
           return true; // Valid
       }

        // --- Form Submission Validation ---
        $('form').submit(function(e) {
            let isValid = true;
            let $form = $(this);

            // 1. Standard HTML5 Validation
            if ($form[0].checkValidity() === false) {
                isValid = false;
            }

            // 2. Validate all Summernote fields within this form
            $form.find('.summernote').each(function() {
                if (!validateSummernote($(this))) {
                    isValid = false;
                }
            });

            // If form is invalid, prevent submission and trigger Bootstrap styles
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
            }

            $form.addClass('was-validated'); // Show Bootstrap feedback styles
        });

    });
</script>
@endsection