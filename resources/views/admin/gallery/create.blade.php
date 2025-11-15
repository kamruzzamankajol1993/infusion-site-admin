@extends('admin.master.master')

@section('title')
Add New Gallery Item | {{ $ins_name }}
@endsection

@section('css')
<style>
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
    /* Image Preview Box */
    .image-preview-box {
        position: relative; width: 100%; max-width: 450px; /* 1500/3.33 */ height: 297px; /* 990/3.33 */
        border: 2px dashed #ced4da; border-radius: .375rem; display: flex;
        align-items: center; justify-content: center; background-color: #f8f9fa;
        color: #6c757d; overflow: hidden; margin-top: 1rem;
    }
    .image-preview-box img { width: 100%; height: 100%; object-fit: contain; }
    .image-preview-box .placeholder-text { font-size: 0.9rem; text-align: center; }
    /* Hide/Show conditional fields */
    .conditional-field { display: none; } /* Hidden by default */
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gallery.index') }}">Gallery</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New Item</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Gallery Item</h5>
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

            <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createGalleryForm">
                @csrf
                <div class="row g-3">
                    {{-- Type Selection --}}
                    <div class="col-md-6">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="image" {{ old('type', 'image') == 'image' ? 'selected' : '' }}>Image</option>
                            <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video (YouTube)</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                     {{-- Short Description --}}
                     <div class="col-md-6">
                        <label for="short_description" class="form-label">Short Description (Optional)</label>
                        <input type="text" class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" value="{{ old('short_description') }}" maxlength="500">
                         @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Conditional Image Field --}}
                    <div class="col-12 conditional-field" id="imageField">
                         <hr>
                        <label for="image_file" class="form-label">Upload Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('image_file') is-invalid @enderror" id="image_file" name="image_file" accept="image/*">
                        <small class="form-text text-muted">Size: 1500px (Width) x 990px (Height), Max: 2MB</small>
                        @error('image_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        {{-- Image Preview --}}
                        <div class="image-preview-box">
                            <img id="imagePreviewG" src="#" alt="Image Preview" style="display:none;">
                            <span class="placeholder-text">Image Preview<br>(1500 x 990)</span>
                        </div>
                    </div>

                    {{-- Conditional Video Field --}}
                    <div class="col-12 conditional-field" id="videoField">
                         <hr>
                        <label for="youtube_link" class="form-label">YouTube Video Link <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('youtube_link') is-invalid @enderror" id="youtube_link" name="youtube_link" value="{{ old('youtube_link') }}" placeholder="e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                        <small class="form-text text-muted">Paste the full YouTube video URL here.</small>
                         @error('youtube_link') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                         {{-- Video Preview (Optional but helpful) --}}
                         <div id="videoPreviewContainer" class="mt-2" style="display: none;">
                             <iframe width="100%" height="315" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                         </div>
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Item</button>
                    <a href="{{ route('gallery.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- SweetAlert --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

<script>
    $(document).ready(function() {

        // --- Function to toggle conditional fields based on Type selection ---
        function toggleFields() {
            const selectedType = $('#type').val();
            const $imageField = $('#imageField');
            const $videoField = $('#videoField');
            const $imageInput = $('#image_file');
            const $youtubeInput = $('#youtube_link');
            const $videoPreview = $('#videoPreviewContainer');

            // Hide all conditional fields first
            $('.conditional-field').hide();
            // Remove 'required' and clear values from hidden fields
            $imageInput.prop('required', false).val('');
            $youtubeInput.prop('required', false).val('');
            $('#imagePreviewG').attr('src','#').hide(); // Reset image preview
            $('.image-preview-box .placeholder-text').show();
            $videoPreview.hide().find('iframe').attr('src', ''); // Reset video preview

             // Remove validation classes from hidden fields
             $imageInput.removeClass('is-invalid');
             $youtubeInput.removeClass('is-invalid');


            // Show the relevant field and set 'required' attribute
            if (selectedType === 'image') {
                $imageField.slideDown(); // Use slideDown for smooth effect
                $imageInput.prop('required', true);
            } else if (selectedType === 'video') {
                $videoField.slideDown();
                $youtubeInput.prop('required', true);
            }
        }

        // --- Initial call to set the correct fields on page load ---
        toggleFields();

        // --- Event listener for Type dropdown change ---
        $('#type').on('change', function() {
            toggleFields();
        });

        // --- Image Preview Logic ---
        $("#image_file").change(function() {
            const input = this; const preview = $('#imagePreviewG'); const placeholder = $('.image-preview-box .placeholder-text');
            if (input.files && input.files[0]) {
                // Client-side size check (Example: 2MB)
                 if(input.files[0].size > 2 * 1024 * 1024) {
                     Swal.fire('File Too Large', 'Image size should not exceed 2MB.', 'warning');
                     $(this).val(''); preview.attr('src','#').hide(); placeholder.show(); return;
                 }
                const reader = new FileReader();
                reader.onload = function(e) { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                reader.readAsDataURL(input.files[0]);
            } else { preview.attr('src','#').hide(); placeholder.show(); }
        });

         // --- YouTube Video Preview Logic ---
         $('#youtube_link').on('input', function() {
            const url = $(this).val();
            const $previewContainer = $('#videoPreviewContainer');
            const $iframe = $previewContainer.find('iframe');
            let videoId = null;

            // Regex to extract video ID from various YouTube URL formats
            const regex = /(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
            const match = url.match(regex);

            if (match && match[1]) {
                videoId = match[1];
                const embedUrl = `https://www.youtube.com/embed/${videoId}`;
                $iframe.attr('src', embedUrl);
                $previewContainer.slideDown(); // Show preview
            } else {
                $iframe.attr('src', '');
                $previewContainer.slideUp(); // Hide preview if URL is invalid
            }
        });


        // --- Form Submission Validation Trigger ---
        $('form#createGalleryForm').submit(function(e) {
            let isValid = true; let $form = $(this);
            $('.form-error').empty(); $('.is-invalid').removeClass('is-invalid');

            // 1. Standard HTML5 Validation (Handles visible required fields)
            if ($form[0].checkValidity() === false) {
                isValid = false;
                 $form.find(':invalid').first().focus();
            }

            // 2. Custom check for conditionally required fields (redundant with HTML5 if fields are visible, but good practice)
            const selectedType = $('#type').val();
            if (selectedType === 'image' && $('#image_file').prop('required') && !$('#image_file').val()) {
                // This case should be caught by checkValidity if field is visible
                isValid = false;
                $('#image_file').addClass('is-invalid'); // Manually add class if needed
                 console.log("Image file required but missing.");
            } else if (selectedType === 'video' && $('#youtube_link').prop('required') && !$('#youtube_link').val()) {
                isValid = false;
                 $('#youtube_link').addClass('is-invalid');
                  console.log("YouTube link required but missing.");
            }

            if (!isValid) {
                e.preventDefault(); e.stopPropagation();
                 const firstError = $form.find('.is-invalid').first();
                 if (firstError.length) { $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); }
            }
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on Input/Change ---
         $('form#createGalleryForm input, form#createGalleryForm select').on('input change', function() {
             // Only remove 'is-invalid' if required and now has a value, OR not required
             if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) || ($(this).attr('type') === 'file' && this.files.length > 0) ) {
                 $(this).removeClass('is-invalid');
             } else if ($(this).prop('required')) {
                 // Re-add if required and cleared (except for type selector which handles fields)
                 if($(this).attr('id') !== 'type') {
                     $(this).addClass('is-invalid');
                 }
             }
         });

    }); // End $(document).ready
</script>
@endsection