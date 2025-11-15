@extends('admin.master.master')

@section('title')
Edit Event | {{ $ins_name }}
@endsection

@section('css')
{{-- Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Styles for Validation Error, Image Preview */
    .note-editor.note-frame.is-invalid { border-color: var(--danger-color, #dc3545) !important; }
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
    .image-preview-box {
        position: relative; width: 100%; max-width: 400px; height: 250px;
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
            <li class="breadcrumb-item"><a href="{{ route('event.index') }}">Events</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ Str::limit($event->title, 40) }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Event</h5>
        </div>
        <div class="card-body">
            @include('flash_message')
            @if ($errors->any())
                 <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h6 class="alert-heading mb-2">Errors found:</h6>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('event.update', $event->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editEventForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                     {{-- Title --}}
                    <div class="col-12">
                        <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $event->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Start Date --}}
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $event->start_date) }}" autocomplete="off" required placeholder="YYYY-MM-DD">
                        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- End Date --}}
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date (Optional)</label>
                        <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $event->end_date) }}" autocomplete="off" placeholder="YYYY-MM-DD">
                        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Time --}}
                     <div class="col-md-4">
                        <label for="time" class="form-label">Time (Optional)</label>
                        <input type="text" class="form-control @error('time') is-invalid @enderror" id="time" name="time" value="{{ old('time', $event->time) }}" placeholder="e.g., 10:00 AM - 4:00 PM">
                        @error('time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $event->description) }}</textarea>
                        <div id="description-error" class="form-error"></div>
                                                                      <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                        @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div class="col-md-8">
                        <label for="image" class="form-label">Event Image</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Size: 1200x750 px, Max: 2MB. Leave blank to keep current.</small>
                        @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        {{-- Image Preview --}}
                        <div class="image-preview-box">
                             @if($event->image)
                                <img id="imagePreviewE" src="{{ $event->image_url }}" alt="Current Image"> {{-- Use accessor --}}
                                <span class="placeholder-text" style="display:none;">Image Preview<br>(1200 x 750)</span>
                             @else
                                <img id="imagePreviewE" src="#" alt="Image Preview" style="display:none;">
                                <span class="placeholder-text">Image Preview<br>(1200 x 750)</span>
                             @endif
                        </div>
                    </div>

                     {{-- Status --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                             @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $event->status) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                         @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Event</button>
                    <a href="{{ route('event.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- jQuery (must be first) --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- Summernote, Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // --- Initialize Plugins ---
        try { 
            flatpickr(".datepicker", { dateFormat: "Y-m-d", allowInput: true }); 
        } catch (e) { console.error("Flatpickr failed to init:", e); }
        
        try {
             $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
             });
        } catch (e) { console.error("Summernote failed to init:", e); }

        // --- Image Preview Logic ---
        $("#image").change(function() {
            const input = this; const preview = $('#imagePreviewE'); const placeholder = $('.placeholder-text');
            if (input.files && input.files[0]) {
                 const fileSize = input.files[0].size / 1024 / 1024; // in MB
                 if (fileSize > 2) { 
                     Swal.fire({ icon: 'warning', title: 'File Too Large', text: 'Image size should not exceed 2MB.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                     $(this).val(''); 
                     /* Reset to original image */
                     const originalSrc = '{{ $event->image_url ?: "" }}';
                     if(originalSrc) { preview.attr('src', originalSrc).show(); placeholder.hide(); }
                     else { preview.attr('src','#').hide(); placeholder.show(); }
                     return; 
                }
                const reader = new FileReader();
                reader.onload = function(e) { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                reader.readAsDataURL(input.files[0]);
            } else {
                 const originalSrc = '{{ $event->image_url ?: "" }}'; // Use accessor
                 if(originalSrc) { preview.attr('src', originalSrc).show(); placeholder.hide(); }
                 else { preview.attr('src','#').hide(); placeholder.show(); }
            }
        });

        // --- Form Submission Validation Trigger ---
        $('form#editEventForm').submit(function(e) {
            let isValid = true; let $form = $(this);
            $('.form-error').empty(); $('.is-invalid').removeClass('is-invalid');

            // Standard HTML5 validation
            if ($form[0].checkValidity() === false) {
                isValid = false; $form.find(':invalid').first().focus();
            }

            // No custom validation needed here

            if (!isValid) {
                e.preventDefault(); e.stopPropagation();
                 const firstError = $form.find('.is-invalid').first();
                 if (firstError.length) { $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); }
            }
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on Input/Change ---
         $('form#editEventForm input, form#editEventForm select, form#editEventForm textarea').on('input change', function() {
              if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) || ($(this).attr('type') === 'file' && this.files.length > 0) ) {
                 $(this).removeClass('is-invalid');
             } else if ($(this).prop('required')) {
                  $(this).addClass('is-invalid');
             }
         });

    });
</script>
@endsection