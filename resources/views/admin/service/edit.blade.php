@extends('admin.master.master')

@section('title')
Edit Service | {{ $ins_name }}
@endsection

@section('css')
{{-- Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* Image Preview Box */
    .image-preview-box {
        position: relative; width: 100%; max-width: 370px; height: 261px;
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
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $service->title }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Service</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            <form action="{{ route('service.update', $service->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editServiceForm">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- Left Column: Title, Description, Keypoints --}}
                    <div class="col-md-8">
                        {{-- Title --}}
                        <div class="mb-3">
                            <label for="title" class="form-label">Service Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $service->title) }}" required>
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control summernote" id="description" name="description" required>{{ old('description', $service->description) }}</textarea>
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
                            {{-- Load existing or old keypoints --}}
                            @php
                                $keypoints = old('keypoints', $service->keypoints->pluck('keypoint')->toArray());
                            @endphp
                            @if(!empty($keypoints))
                                @foreach($keypoints as $index => $keypointText)
                                <div class="input-group mb-2 keypoint-row">
                                    <input type="text" class="form-control" name="keypoints[]" placeholder="Enter keypoint" value="{{ $keypointText }}" required>
                                    <button class="btn btn-outline-danger removeKeypointRow" type="button"><i class="fa fa-trash"></i></button>
                                </div>
                                @endforeach
                            @else
                                {{-- Default first row if none exist --}}
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
                            <label for="image" class="form-label">Service Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="form-text text-muted">Size: 740x522 px, Max: 1MB. Leave blank to keep current.</small>
                        </div>
                        <div class="image-preview-box">
                             @if($service->image)
                                <img id="imagePreview" src="{{ asset($service->image) }}" alt="Current Image">
                                <span class="placeholder-text" style="display:none;">Image Preview<br>(740 x 522)</span>
                             @else
                                <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                                <span class="placeholder-text">Image Preview<br>(740 x 522)</span>
                             @endif
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Service</button>
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
                toolbar: [ /* ... toolbar buttons ... */ ],
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
            } else {
                 // If user clears selection, potentially show the original image again if it exists
                 const originalSrc = '{{ $service->image ? asset($service->image) : "" }}';
                 if(originalSrc) {
                     preview.attr('src', originalSrc).show(); placeholder.hide();
                 } else {
                    preview.hide(); placeholder.show();
                 }
            }
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

        // Remove Keypoint Row
        $(document).on('click', '.removeKeypointRow', function() {
            if ($('#keypointsRepeaterContainer .keypoint-row').length > 1) {
                 $(this).closest('.keypoint-row').remove();
            } else {
                 $(this).closest('.keypoint-row').find('input').val('');
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
        $('form#editServiceForm').submit(function(e) {
            let isValid = true; let $form = $(this);
            if ($form[0].checkValidity() === false) isValid = false;
            $form.find('.summernote').each(function() { if (!validateSummernote($(this))) isValid = false; });
            // Add keypoint validation if needed (e.g., at least one must be filled)

            if (!isValid) { e.preventDefault(); e.stopPropagation(); }
            $form.addClass('was-validated');
        });

    });
</script>
@endsection