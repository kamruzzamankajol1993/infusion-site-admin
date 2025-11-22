@extends('admin.master.master')

@section('title')
About Us Content | {{ $ins_name }}
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* Base Image Preview Box */
    .image-preview-box {
        position: relative;
        width: 100%;
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
        object-fit: contain;
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
    
    /* 600x400 Aspect Ratio (600:400 -> 3:2 -> 66.66%) */
    .preview-600x400 {
        padding-top: 66.66%; 
    }
    
    /* 400x500 Aspect Ratio (400:500 -> 4:5 -> 125%) */
    /* We'll cap the width to make it look good on the form */
    .preview-400x500 {
        max-width: 400px; /* Max width */
        padding-top: 125%; /* 500 / 400 * 100% */
        margin-left: auto;
        margin-right: auto;
    }

    /* Summernote Validation */
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

    @php
        $hasData = isset($aboutUs) && $aboutUs->id; // Check if data exists
    @endphp

    {{-- EDIT FORM CARD --}}
    @if($hasData)
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit About Us Content </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('aboutUs.update', $aboutUs->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editForm">
                @csrf
                @method('PUT')

                {{-- Our Story Section --}}
                <h6 class="mt-2 text-primary">Our Story</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="edit_our_story" class="form-label">Our Story <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="edit_our_story" name="our_story" required>{{ old('our_story', $aboutUs->our_story ?? '') }}</textarea>
                        <div id="edit_our_story-error" class="form-error"></div>
                    </div>
                </div>
                <hr>

                {{-- Team Image Section --}}
                <h6 class="mt-3 text-primary">Team Image</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="edit_team_image" class="form-label">Upload Team Image</label>
                        <input type="file" class="form-control" id="edit_team_image" name="team_image" accept="image/*">
                        <small class="form-text text-muted">Recommended size: 600px (Width) x 400px (Height). Leave blank to keep current image.</small>
                    </div>
                     <div class="col-md-12">
                         <div class="image-preview-box preview-600x400 mt-2">
                             @if($aboutUs->team_image)
                                <img id="editTeamImagePreview" src="{{ asset($aboutUs->team_image) }}" alt="Current Team Image">
                                <span class="placeholder-text" style="display:none;">Team Image Preview<br>(600 x 400)</span>
                             @else
                                <img id="editTeamImagePreview" src="#" alt="Team Image Preview" style="display:none;">
                                <span class="placeholder-text">Team Image Preview<br>(600 x 400)</span>
                             @endif
                         </div>
                    </div>
                </div>
                <hr>

                {{-- Mission & Vision Section --}}
                <h6 class="mt-3 text-primary">Mission & Vision</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="edit_mission" class="form-label">Mission <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="edit_mission" name="mission" required>{{ old('mission', $aboutUs->mission ?? '') }}</textarea>
                        <div id="edit_mission-error" class="form-error"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="edit_vision" class="form-label">Vision <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="edit_vision" name="vision" required>{{ old('vision', $aboutUs->vision ?? '') }}</textarea>
                        <div id="edit_vision-error" class="form-error"></div>
                    </div>
                </div>
                <div class="row mb-3">
                     <div class="col-md-12">
                        <label for="edit_mission_vision_image" class="form-label">Upload Mission/Vision Image</label>
                        <input type="file" class="form-control" id="edit_mission_vision_image" name="mission_vision_image" accept="image/*">
                        <small class="form-text text-muted">Recommended size: 600px (Width) x 400px (Height). Leave blank to keep current image.</small>
                    </div>
                     <div class="col-md-12">
                         <div class="image-preview-box preview-600x400 mt-2">
                             @if($aboutUs->mission_vision_image)
                                <img id="editMvImagePreview" src="{{ asset($aboutUs->mission_vision_image) }}" alt="Current M/V Image">
                                <span class="placeholder-text" style="display:none;">Mission/Vision Image Preview<br>(600 x 400)</span>
                             @else
                                <img id="editMvImagePreview" src="#" alt="M/V Image Preview" style="display:none;">
                                <span class="placeholder-text">Mission/Vision Image Preview<br>(600 x 400)</span>
                             @endif
                         </div>
                    </div>
                </div>
                <hr>

                {{-- Founder Section --}}
                <h6 class="mt-3 text-primary">Founder's Message</h6>
                <div class="row mb-3">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label for="edit_founder_name" class="form-label">Founder Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_founder_name" name="founder_name" value="{{ old('founder_name', $aboutUs->founder_name ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                             <label for="edit_founder_designation" class="form-label">Founder Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_founder_designation" name="founder_designation" value="{{ old('founder_designation', $aboutUs->founder_designation ?? '') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_founder_quote" class="form-label">Founder Quote <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_founder_quote" name="founder_quote" rows="8" required>{{ old('founder_quote', $aboutUs->founder_quote ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="edit_founder_image" class="form-label">Upload Founder Image</label>
                        <input type="file" class="form-control" id="edit_founder_image" name="founder_image" accept="image/*">
                        <small class="form-text text-muted">Recommended size: 400px (Width) x 500px (Height). Leave blank to keep current.</small>
                        <div class="image-preview-box preview-400x500 mt-2">
                             @if($aboutUs->founder_image)
                                <img id="editFounderImagePreview" src="{{ asset($aboutUs->founder_image) }}" alt="Current Founder Image">
                                <span class="placeholder-text" style="display:none;">Founder Image Preview<br>(400 x 500)</span>
                             @else
                                <img id="editFounderImagePreview" src="#" alt="Founder Image Preview" style="display:none;">
                                <span class="placeholder-text">Founder Image Preview<br>(400 x 500)</span>
                             @endif
                         </div>
                    </div>
                </div>
                <hr>

                {{-- Legal Info Section --}}
                <h6 class="mt-3 text-primary">Legal Information</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="edit_trade_license" class="form-label">Trade License No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_trade_license" name="trade_license" value="{{ old('trade_license', $aboutUs->trade_license ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_bin" class="form-label">BIN No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_bin" name="bin" value="{{ old('bin', $aboutUs->bin ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="edit_tin" class="form-label">TIN No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_tin" name="tin" value="{{ old('tin', $aboutUs->tin ?? '') }}" required>
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

                {{-- Our Story Section --}}
                <h6 class="mt-2 text-primary">Our Story</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="create_our_story" class="form-label">Our Story <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="create_our_story" name="our_story" required>{{ old('our_story') }}</textarea>
                        <div id="create_our_story-error" class="form-error"></div>
                    </div>
                </div>
                <hr>

                {{-- Team Image Section --}}
                <h6 class="mt-3 text-primary">Team Image</h6>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="create_team_image" class="form-label">Upload Team Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="create_team_image" name="team_image" accept="image/*" required>
                        <small class="form-text text-muted">Recommended size: 600px (Width) x 400px (Height).</small>
                    </div>
                     <div class="col-md-12">
                        <div class="image-preview-box preview-600x400 mt-2">
                             <img id="createTeamImagePreview" src="#" alt="Team Image Preview" style="display:none;">
                             <span class="placeholder-text">Team Image Preview<br>(600 x 400)</span>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Mission & Vision Section --}}
                <h6 class="mt-3 text-primary">Mission & Vision</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="create_mission" class="form-label">Mission <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="create_mission" name="mission" required>{{ old('mission') }}</textarea>
                        <div id="create_mission-error" class="form-error"></div>
                    </div>
                    <div class="col-md-6">
                        <label for="create_vision" class="form-label">Vision <span class="text-danger">*</span></label>
                        <textarea class="form-control summernote" id="create_vision" name="vision" required>{{ old('vision') }}</textarea>
                        <div id="create_vision-error" class="form-error"></div>
                    </div>
                </div>
                <div class="row mb-3">
                     <div class="col-md-12">
                        <label for="create_mission_vision_image" class="form-label">Upload Mission/Vision Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="create_mission_vision_image" name="mission_vision_image" accept="image/*" required>
                        <small class="form-text text-muted">Recommended size: 600px (Width) x 400px (Height).</small>
                    </div>
                     <div class="col-md-12">
                        <div class="image-preview-box preview-600x400 mt-2">
                             <img id="createMvImagePreview" src="#" alt="M/V Image Preview" style="display:none;">
                             <span class="placeholder-text">Mission/Vision Image Preview<br>(600 x 400)</span>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Founder Section --}}
                <h6 class="mt-3 text-primary">Founder's Message</h6>
                <div class="row mb-3">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label for="create_founder_name" class="form-label">Founder Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_founder_name" name="founder_name" value="{{ old('founder_name') }}" required>
                        </div>
                        <div class="mb-3">
                             <label for="create_founder_designation" class="form-label">Founder Designation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_founder_designation" name="founder_designation" value="{{ old('founder_designation') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="create_founder_quote" class="form-label">Founder Quote <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="create_founder_quote" name="founder_quote" rows="8" required>{{ old('founder_quote') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="create_founder_image" class="form-label">Upload Founder Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="create_founder_image" name="founder_image" accept="image/*" required>
                        <small class="form-text text-muted">Recommended size: 400px (Width) x 500px (Height).</small>
                        <div class="image-preview-box preview-400x500 mt-2">
                             <img id="createFounderImagePreview" src="#" alt="Founder Image Preview" style="display:none;">
                             <span class="placeholder-text">Founder Image Preview<br>(400 x 500)</span>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Legal Info Section --}}
                <h6 class="mt-3 text-primary">Legal Information</h6>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="create_trade_license" class="form-label">Trade License No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create_trade_license" name="trade_license" value="{{ old('trade_license') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="create_bin" class="form-label">BIN No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create_bin" name="bin" value="{{ old('bin') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label for="create_tin" class="form-label">TIN No. <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="create_tin" name="tin" value="{{ old('tin') }}" required>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {

        // --- Initialize Summernote ---
        function initSummernote(selector) {
             try {
                $(selector).summernote({
                    height: 150,
                    toolbar: [
                        ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['codeview']]
                    ],
                     callbacks: {
                        onChange: function(contents, $editable) {
                           validateSummernote($(this));
                           if (!$(this).summernote('isEmpty')) {
                               $(this).removeClass('is-invalid');
                               $(this).siblings('.note-editor').removeClass('is-invalid');
                               $(this).siblings('.form-error').empty();
                           }
                        }
                     }
                });
            } catch(e) { console.warn("Summernote not loaded or failed to initialize."); }
        }

        // Initialize for both forms if they exist
        if ($('#createForm').length) {
            initSummernote('#create_our_story');
            initSummernote('#create_mission');
            initSummernote('#create_vision');
        }
        if ($('#editForm').length) {
            initSummernote('#edit_our_story');
            initSummernote('#edit_mission');
            initSummernote('#edit_vision');
        }


        // --- Image Preview Logic (CORRECTED) ---
        function handleImagePreview(inputId, previewId, placeholderClass) {
            $("#" + inputId).change(function() {
                const input = this;
                // Find the preview image tag using the ID passed
                const preview = $('#' + previewId);
                // Find the placeholder span (sibling of the image)
                const placeholder = preview.siblings(placeholderClass);

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // Update the src attribute with the new file data
                        preview.attr('src', e.target.result);
                        // Make sure the image is visible
                        preview.show(); 
                        // Hide the "Preview Text" placeholder
                        placeholder.hide(); 
                    };
                    
                    reader.readAsDataURL(input.files[0]);
                } else {
                    // If the user cancels the input or selects nothing
                     // Note: In edit mode, this might hide the existing DB image. 
                     // You might want to add logic here to check if it had an original src if strictly needed.
                     preview.attr('src', '#').hide();
                     placeholder.show();
                }
            });
        }

        // Apply preview logic to all 6 image inputs
        handleImagePreview('create_team_image', 'createTeamImagePreview', '.placeholder-text');
        handleImagePreview('edit_team_image', 'editTeamImagePreview', '.placeholder-text');
        
        handleImagePreview('create_mission_vision_image', 'createMvImagePreview', '.placeholder-text');
        handleImagePreview('edit_mission_vision_image', 'editMvImagePreview', '.placeholder-text');

        handleImagePreview('create_founder_image', 'createFounderImagePreview', '.placeholder-text');
        handleImagePreview('edit_founder_image', 'editFounderImagePreview', '.placeholder-text');


       // --- Custom Client-Side Validation for Summernote ---
       function validateSummernote($element) {
           const $editor = $element.siblings('.note-editor');
           const $errorDiv = $element.siblings('.form-error');
           $errorDiv.empty(); 
           $editor.removeClass('is-invalid'); 

           if ($element.prop('required') && $element.summernote('isEmpty')) {
               $errorDiv.text('This field is required.');
               $editor.addClass('is-invalid'); 
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

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
            }

            $form.addClass('was-validated'); 
        });

    });
</script>
@endsection