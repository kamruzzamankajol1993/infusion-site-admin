@extends('admin.master.master')

@section('title')
Add New Press Release | {{ $ins_name }}
@endsection

@section('css')
{{-- Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .note-editor.note-frame.is-invalid { border-color: var(--danger-color, #dc3545) !important; }
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
    /* Image Preview Box */
    .image-preview-box {
        position: relative; width: 100%; max-width: 400px; height: 267px;
        border: 2px dashed #ced4da; border-radius: .375rem; display: flex;
        align-items: center; justify-content: center; background-color: #f8f9fa;
        color: #6c757d; overflow: hidden; margin-top: 1rem;
    }
    .image-preview-box img { width: 100%; height: 100%; object-fit: contain; }
    .image-preview-box .placeholder-text { font-size: 0.9rem; text-align: center; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('pressRelease.index') }}">Press Releases</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Press Release</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            <form action="{{ route('pressRelease.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createPressReleaseForm">
                @csrf
                <div class="row g-3">
                    {{-- Title --}}
                    <div class="col-12">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                     {{-- Image Upload --}}
                    <div class="col-md-6">
                        <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                        <small class="form-text text-muted">Size: 1200px (Width) x 800px (Height), Max: 2MB</small>
                        @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        {{-- Image Preview --}}
                        <div class="image-preview-box">
                            <img id="imagePreviewPR" src="#" alt="Image Preview" style="display:none;">
                            <span class="placeholder-text">Image Preview<br>(1200 x 800)</span>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-md-6">
                         {{-- Release Date --}}
                         <div class="mb-3">
                            <label for="release_date" class="form-label">Release Date (Optional)</label>
                            <input type="text" class="form-control datepicker @error('release_date') is-invalid @enderror" id="release_date" name="release_date" value="{{ old('release_date') }}" placeholder="YYYY-MM-DD" autocomplete="off">
                            @error('release_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                         </div>

                         {{-- Link Field --}}
                         <div class="mb-3" id="linkField">
                            <label for="link" class="form-label">Link URL (Optional)</label>
                            <input type="url" class="form-control @error('link') is-invalid @enderror" id="link" name="link" value="{{ old('link') }}" placeholder="https://example.com/press-release">
                            @error('link') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                         </div>
                    </div>

                    {{-- Description Field --}}
                    <div class="col-12" id="descriptionField">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                        <div id="description-error" class="form-error"></div>
                        <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                        @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Press Release</button>
                    <a href="{{ route('pressRelease.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Summernote JS --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function() {
        // --- Initialize Plugins ---
         try {
             $('#description').summernote({
                 height: 250,
                 toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                 ],
                 callbacks: { 
                     onChange: function(contents, $editable) { 
                         // Optional: You can add validation logic here if needed
                     } 
                 }
             });
        } catch (e) { console.warn("Summernote failed.")}

         // --- Initialize Flatpickr ---
         try {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
         } catch (e) { console.warn("Flatpickr failed to initialize.")}

        // --- Image Preview Logic ---
        $("#image").change(function() {
            const input = this; const preview = $('#imagePreviewPR'); const placeholder = $('.placeholder-text');
            if (input.files && input.files[0]) {
                 if(input.files[0].size > 2 * 1024 * 1024) { 
                     Swal.fire({title: 'Error!', text: 'Image size cannot exceed 2MB.', icon: 'error'}); 
                     $(this).val(''); 
                     return; 
                 }
                const reader = new FileReader();
                reader.onload = function(e) { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                reader.readAsDataURL(input.files[0]);
            } else { 
                preview.attr('src','#').hide(); 
                placeholder.show(); 
            }
        });

        // --- Form Submission Validation ---
        $('form#createPressReleaseForm').submit(function(e) {
            let isValid = true; 
            let $form = $(this);
            $('.form-error').empty(); 
            $('.is-invalid').removeClass('is-invalid');

            // Standard HTML5 validation
            if ($form[0].checkValidity() === false) { 
                isValid = false; 
            }

            if (!isValid) {
                e.preventDefault(); 
                e.stopPropagation();
                // Focus on the first invalid field
                 const firstError = $form.find('.is-invalid, .form-error:not(:empty)').first();
                 if (firstError.length) { 
                     $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); 
                 }
            }
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on input/change ---
         $('form#createPressReleaseForm input, form#createPressReleaseForm select, form#createPressReleaseForm textarea').on('input change', function() {
             let isRequired = $(this).prop('required');
             if ( (!isRequired) || (isRequired && $(this).val()) || ($(this).attr('type') === 'file' && this.files.length > 0) ) {
                 $(this).removeClass('is-invalid');
                  if ($(this).is('.summernote')) $(this).siblings('.note-editor').removeClass('is-invalid');
             } else if (isRequired) {
                 $(this).addClass('is-invalid');
             }
         });

    });
</script>
@endsection