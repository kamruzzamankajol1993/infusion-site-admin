@extends('admin.master.master')

@section('title')
Add New Service | {{ $ins_name }}
@endsection

@section('css')
{{-- Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* Image Preview Box */
    .image-preview-box {
        position: relative; width: 100%; max-width: 370px; /* 740/2 */ height: 261px; /* 522/2 */
        border: 2px dashed #ced4da; border-radius: .375rem; display: flex;
        align-items: center; justify-content: center; background-color: #f8f9fa;
        color: #6c757d; overflow: hidden; margin-top: 1rem;
    }
    .image-preview-box img { width: 100%; height: 100%; object-fit: contain; }
    .image-preview-box .placeholder-text { font-size: 0.9rem; text-align: center; }
    .note-editor.note-frame.is-invalid { border-color: var(--danger-color, #F51825) !important; }
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #F51825); }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('service.index') }}">Services</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Service</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            <form action="{{ route('service.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createServiceForm">
                @csrf

                <div class="row">
                    {{-- Left Column: Title, Description, Keypoints --}}
                    <div class="col-md-8">
                        {{-- Title --}}
                        <div class="mb-3">
                            <label for="title" class="form-label">Service Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control summernote" id="description" name="description" required>{{ old('description') }}</textarea>
                            <div id="description-error" class="form-error"></div>
                                          <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                        </div>

                        {{-- Keypoints Repeater --}}
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                            <h6 class="mb-0 text-primary">Service Keypoints</h6>
                            <button type="button" class="btn btn-sm btn-success" id="addKeypointRow">
                                <i data-feather="plus" style="width:16px;"></i> Add Keypoint
                            </button>
                        </div>
                        <div id="keypointsRepeaterContainer">
                            {{-- Initial row if old input exists --}}
                            @if(old('keypoints'))
                                @foreach(old('keypoints') as $index => $oldKeypoint)
                                <div class="input-group mb-2 keypoint-row">
                                    <input type="text" class="form-control" name="keypoints[]" placeholder="Enter keypoint" value="{{ $oldKeypoint }}" required>
                                    <button class="btn btn-outline-danger removeKeypointRow" type="button"><i class="fa fa-trash"></i></button>
                                </div>
                                @endforeach
                            @else
                                {{-- Default first row --}}
                                <div class="input-group mb-2 keypoint-row">
                                    <input type="text" class="form-control" name="keypoints[]" placeholder="Enter keypoint" required>
                                    <button class="btn btn-outline-danger removeKeypointRow" type="button"><i class="fa fa-trash"></i></button>
                                </div>
                            @endif
                        </div>
                         <small class="form-text text-muted">Add concise points highlighting the service features or benefits.</small>

                    </div>

                    {{-- Right Column: Image Upload --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Service Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                            <small class="form-text text-muted">Size: 740px (Width) x 522px (Height), Max: 1MB</small>
                        </div>
                        <div class="image-preview-box">
                            <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                            <span class="placeholder-text">Image Preview<br>(740 x 522)</span>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Service</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Summernote JS --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
    $(document).ready(function() {
        // --- Initialize Summernote ---
        try {
            $('.summernote').summernote({
                height: 200, // Adjust height
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ],
                callbacks: { // Validation helper
                    onChange: function(contents, $editable) { validateSummernote($(this)); }
                }
            });
        } catch(e) { console.warn("Summernote failed to initialize."); }

        // --- Image Preview Logic ---
        $("#image").change(function() {
            const input = this; const preview = $('#imagePreview'); const placeholder = $('.placeholder-text');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                reader.readAsDataURL(input.files[0]);
            } else { preview.hide(); placeholder.show(); }
        });

        // --- Keypoints Repeater Logic ---
        $("#addKeypointRow").click(function() {
            let newRow = `
            <div class="input-group mb-2 keypoint-row">
                <input type="text" class="form-control" name="keypoints[]" placeholder="Enter keypoint" required>
                <button class="btn btn-outline-danger removeKeypointRow" type="button"><i class="fa fa-trash"></i></button>
            </div>`;
            $('#keypointsRepeaterContainer').append(newRow);
        });

        // Remove Keypoint Row (using event delegation)
        $(document).on('click', '.removeKeypointRow', function() {
            // Prevent removing the last row if you want at least one
            if ($('#keypointsRepeaterContainer .keypoint-row').length > 1) {
                 $(this).closest('.keypoint-row').remove();
            } else {
                 // Optionally show a message or just clear the input
                 $(this).closest('.keypoint-row').find('input').val(''); // Clear value instead of removing
                 // alert('At least one keypoint is required.');
            }
        });


       // --- Custom Client-Side Validation ---
       function validateSummernote($element) {
           const $editor = $element.siblings('.note-editor');
           const $errorDiv = $element.siblings('.form-error');
           $errorDiv.empty(); $editor.removeClass('is-invalid');
           if ($element.prop('required') && $element.summernote('isEmpty')) {
               $errorDiv.text('This field is required.'); $editor.addClass('is-invalid'); return false;
           } return true;
       }

        // --- Form Submission Validation ---
        $('form#createServiceForm').submit(function(e) {
            let isValid = true; let $form = $(this);
            // 1. Standard HTML5
            if ($form[0].checkValidity() === false) isValid = false;
            // 2. Summernote
            $form.find('.summernote').each(function() { if (!validateSummernote($(this))) isValid = false; });
            // 3. Check for at least one non-empty keypoint (if keypoints are mandatory overall)
            let keypointFilled = false;
            $('input[name="keypoints[]"]').each(function() {
                 if ($(this).val().trim() !== '') { keypointFilled = true; return false; /* exit loop */ }
            });
            // Example: Uncomment below if at least one keypoint must be filled
            // if (!keypointFilled && $('#keypointsRepeaterContainer .keypoint-row').length > 0) {
            //      isValid = false;
            //      alert('Please enter text for at least one keypoint, or remove the empty rows.');
                 // Optionally add error message display near the repeater
            // }

            if (!isValid) { e.preventDefault(); e.stopPropagation(); }
            $form.addClass('was-validated');
        });

    });
</script>
@endsection