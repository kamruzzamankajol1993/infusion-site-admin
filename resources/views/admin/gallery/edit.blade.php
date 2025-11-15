@extends('admin.master.master')

@section('title')
Edit Gallery Item | {{ $ins_name }}
@endsection

@section('css')
<style>
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
    /* Image Preview Box */
    .image-preview-box {
        position: relative; width: 100%; max-width: 450px; height: 297px;
        border: 2px dashed #ced4da; border-radius: .375rem; display: flex;
        align-items: center; justify-content: center; background-color: #f8f9fa;
        color: #6c757d; overflow: hidden; margin-top: 1rem;
    }
    .image-preview-box img { width: 100%; height: 100%; object-fit: contain; }
    .image-preview-box .placeholder-text { font-size: 0.9rem; text-align: center; }
    /* Conditional Fields */
    .conditional-field { display: none; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('gallery.index') }}">Gallery</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Item #{{ $gallery->id }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Gallery Item</h5>
        </div>
        <div class="card-body">
            @include('flash_message')
            @if ($errors->any())
               {{-- ... error display ... --}}
            @endif

            <form action="{{ route('gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editGalleryForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    {{-- Type Selection --}}
                    <div class="col-md-6">
                        <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            {{-- Set selected based on current gallery item type --}}
                            <option value="image" {{ old('type', $gallery->type) == 'image' ? 'selected' : '' }}>Image</option>
                            <option value="video" {{ old('type', $gallery->type) == 'video' ? 'selected' : '' }}>Video (YouTube)</option>
                        </select>
                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                     {{-- Short Description --}}
                     <div class="col-md-6">
                        <label for="short_description" class="form-label">Short Description (Optional)</label>
                        <input type="text" class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" value="{{ old('short_description', $gallery->short_description) }}" maxlength="500">
                         @error('short_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Conditional Image Field --}}
                    <div class="col-12 conditional-field" id="imageField">
                         <hr>
                        <label for="image_file" class="form-label">Upload New Image</label>
                        <input type="file" class="form-control @error('image_file') is-invalid @enderror" id="image_file" name="image_file" accept="image/*">
                        <small class="form-text text-muted">Size: 1500x990 px, Max: 2MB. Leave blank to keep current image.</small>
                        @error('image_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        {{-- Image Preview --}}
                        <div class="image-preview-box">
                            @if($gallery->type === 'image' && $gallery->image_file)
                                <img id="imagePreviewG" src="{{ $gallery->image_url }}" alt="Current Image"> {{-- Use accessor --}}
                                <span class="placeholder-text" style="display:none;">Image Preview<br>(1500 x 990)</span>
                             @else
                                <img id="imagePreviewG" src="#" alt="Image Preview" style="display:none;">
                                <span class="placeholder-text">Image Preview<br>(1500 x 990)</span>
                             @endif
                        </div>
                    </div>

                    {{-- Conditional Video Field --}}
                    <div class="col-12 conditional-field" id="videoField">
                         <hr>
                        <label for="youtube_link" class="form-label">YouTube Video Link <span class="text-danger">*</span></label>
                        <input type="url" class="form-control @error('youtube_link') is-invalid @enderror" id="youtube_link" name="youtube_link" value="{{ old('youtube_link', $gallery->youtube_link) }}" placeholder="e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ">
                        <small class="form-text text-muted">Paste the full YouTube video URL here.</small>
                         @error('youtube_link') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                         {{-- Video Preview --}}
                         <div id="videoPreviewContainer" class="mt-2" style="display: none;"> {{-- Initially hidden --}}
                             <iframe width="100%" height="315" src="" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                         </div>
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Item</button>
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

        // --- Function to toggle conditional fields ---
        function toggleFields() {
            const selectedType = $('#type').val();
            const $imageField = $('#imageField');
            const $videoField = $('#videoField');
            const $imageInput = $('#image_file');
            const $youtubeInput = $('#youtube_link');
            const $videoPreview = $('#videoPreviewContainer');
            const $imagePreview = $('#imagePreviewG');
            const $imagePlaceholder = $('.image-preview-box .placeholder-text');

            // Hide fields initially
            $('.conditional-field').hide();
            // Important: Remove 'required' from hidden fields to allow form submission
            $imageInput.prop('required', false);
            $youtubeInput.prop('required', false);

            // Remove validation classes if they were visible before switching
             $imageInput.removeClass('is-invalid');
             $youtubeInput.removeClass('is-invalid');


            if (selectedType === 'image') {
                $imageField.slideDown();
                // Set required only if no image already exists (backend handles this too, but good UX)
                const hasCurrentImage = '{{ $gallery->type === "image" && $gallery->image_file }}';
                if (!hasCurrentImage) {
                     $imageInput.prop('required', true);
                }
                // No need to clear video input value here if switching TO image, user might switch back.
                // Clear video preview if switching TO image
                 $videoPreview.slideUp().find('iframe').attr('src', '');

            } else if (selectedType === 'video') {
                $videoField.slideDown();
                $youtubeInput.prop('required', true);
                // Trigger input event to show preview if link already exists
                 $('#youtube_link').trigger('input');
                // No need to clear image input if switching TO video
                 // Reset image preview if switching TO video
                const originalImageSrc = '{{ $gallery->type === "image" && $gallery->image_file ? $gallery->image_url : "" }}';
                 if(originalImageSrc) { $imagePreview.attr('src', originalImageSrc); $imagePlaceholder.hide(); $imagePreview.show(); }
                 else { $imagePreview.attr('src','#').hide(); $imagePlaceholder.show();}

            }
        }

        // --- Initial call and event listener ---
        toggleFields();
        $('#type').on('change', toggleFields);

        // --- Image Preview Logic ---
        $("#image_file").change(function() {
            const input = this; const preview = $('#imagePreviewG'); const placeholder = $('.image-preview-box .placeholder-text');
            if (input.files && input.files[0]) {
                 if(input.files[0].size > 2 * 1024 * 1024) { /* Size Check */ } // Add size check if needed
                const reader = new FileReader();
                reader.onload = function(e) { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                reader.readAsDataURL(input.files[0]);
            } else {
                 // Reset to original image or placeholder if file selection is cancelled
                 const originalSrc = '{{ $gallery->type === "image" && $gallery->image_file ? $gallery->image_url : "" }}';
                 if(originalSrc) { preview.attr('src', originalSrc).show(); placeholder.hide(); }
                 else { preview.attr('src','#').hide(); placeholder.show(); }
            }
        });

        // --- YouTube Video Preview Logic ---
         $('#youtube_link').on('input', function() { /* ... Same logic as create ... */
             const url = $(this).val();
            const $previewContainer = $('#videoPreviewContainer');
            const $iframe = $previewContainer.find('iframe');
            let videoId = null;
            const regex = /(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
            const match = url.match(regex);

            if (match && match[1]) {
                videoId = match[1];
                const embedUrl = `https://www.youtube.com/embed/${videoId}`;
                $iframe.attr('src', embedUrl);
                $previewContainer.slideDown();
            } else {
                $iframe.attr('src', '');
                $previewContainer.slideUp();
            }
         }).trigger('input'); // Trigger on load to show preview if link exists


        // --- Form Submission Validation Trigger ---
        $('form#editGalleryForm').submit(function(e) {
            let isValid = true; let $form = $(this);
            $('.form-error').empty(); $('.is-invalid').removeClass('is-invalid');

            // 1. Standard HTML5 (checks visible required fields)
            if ($form[0].checkValidity() === false) { isValid = false; }

            // 2. Custom check (redundant but safe)
            const selectedType = $('#type').val();
            const hasCurrentImage = '{{ $gallery->type === "image" && $gallery->image_file }}';
            if (selectedType === 'image' && !hasCurrentImage && $('#image_file').prop('required') && !$('#image_file').val()) {
                isValid = false; $('#image_file').addClass('is-invalid');
            } else if (selectedType === 'video' && $('#youtube_link').prop('required') && !$('#youtube_link').val()) {
                isValid = false; $('#youtube_link').addClass('is-invalid');
            }


            if (!isValid) {
                e.preventDefault(); e.stopPropagation();
                 const firstError = $form.find('.is-invalid').first();
                 if (firstError.length) { $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); }
            }
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on Input/Change ---
         $('form#editGalleryForm input, form#editGalleryForm select').on('input change', function() {
            // Check if required based on current type visibility and required prop
            let isRequired = $(this).prop('required');
             if ( $(this).attr('id') === 'image_file' && $('#type').val() !== 'image') isRequired = false;
             if ( $(this).attr('id') === 'youtube_link' && $('#type').val() !== 'video') isRequired = false;

             if ( (!isRequired) || (isRequired && $(this).val()) || ($(this).attr('type') === 'file' && this.files.length > 0) ) {
                 $(this).removeClass('is-invalid');
             } else if (isRequired) {
                  $(this).addClass('is-invalid');
             }
         });

    }); // End $(document).ready
</script>
@endsection