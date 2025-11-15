@extends('admin.master.master')

@section('title')
Add New Event | {{ $ins_name }}
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
        position: relative; width: 100%; max-width: 400px; /* 1200/3 */ height: 250px; /* 750/3 */
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
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Event</h5>
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

            <form action="{{ route('event.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createEventForm">
                @csrf
                <div class="row g-3">
                    {{-- Title --}}
                    <div class="col-12">
                        <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Start Date --}}
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" autocomplete="off" required placeholder="YYYY-MM-DD">
                        @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- End Date --}}
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date (Optional)</label>
                        <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" autocomplete="off" placeholder="YYYY-MM-DD">
                        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Time --}}
                     <div class="col-md-4">
                        <label for="time" class="form-label">Time (Optional)</label>
                        <input type="text" class="form-control @error('time') is-invalid @enderror" id="time" name="time" value="{{ old('time') }}" placeholder="e.g., 10:00 AM - 4:00 PM">
                        @error('time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                        <div id="description-error" class="form-error"></div>
                                                                      <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                        @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div class="col-md-8">
                        <label for="image" class="form-label">Event Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                        <small class="form-text text-muted">Size: 1200px (Width) x 750px (Height), Max: 2MB</small>
                        @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        {{-- Image Preview --}}
                        <div class="image-preview-box">
                            <img id="imagePreviewE" src="#" alt="Image Preview" style="display:none;">
                            <span class="placeholder-text">Image Preview<br>(1200 x 750)</span>
                        </div>
                    </div>

                     {{-- Status --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                             @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ old('status', '1') == $value ? 'selected' : '' }}>{{ $label }}</option> {{-- Default to Published --}}
                            @endforeach
                        </select>
                         @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Event</button>
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
                if (fileSize > 2) { // Max 2MB Check
                     Swal.fire({ icon: 'warning', title: 'File Too Large', text: 'Image size should not exceed 2MB.', toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                     $(this).val(''); preview.attr('src','#').hide(); placeholder.show(); return;
                 }
                const reader = new FileReader();
                reader.onload = function(e) { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                reader.readAsDataURL(input.files[0]);
            } else { preview.attr('src','#').hide(); placeholder.show(); }
        });

        // --- Form Submission Validation Trigger ---
        $('form#createEventForm').submit(function(e) {
            let isValid = true; let $form = $(this);
            $('.form-error').empty(); $('.is-invalid').removeClass('is-invalid');

            // 1. Standard HTML5 Validation
            if ($form[0].checkValidity() === false) {
                isValid = false; $form.find(':invalid').first().focus();
            }

            // No custom validation needed for optional Summernote or Select2

            if (!isValid) {
                e.preventDefault(); e.stopPropagation();
                 const firstError = $form.find('.is-invalid').first();
                 if (firstError.length) { $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); }
            }
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on Input/Change ---
         $('form#createEventForm input, form#createEventForm select, form#createEventForm textarea').on('input change', function() {
             // Basic clearing for standard fields
              if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) || ($(this).attr('type') === 'file' && this.files.length > 0) ) {
                 $(this).removeClass('is-invalid');
             } else if ($(this).prop('required')) {
                  $(this).addClass('is-invalid');
             }
         });

    });
</script>
@endsection