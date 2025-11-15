@extends('admin.master.master')

@section('title')
Add New Notice | {{ $ins_name }}
@endsection

@section('css')
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Select2 Height Fix */
    .select2-container .select2-selection--single { height: 38px !important; border: 1px solid #ced4da; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
    .select2-container--default.select2-container--focus .select2-selection--single { border-color: #86b7fe; } /* Match Bootstrap focus */
    .select2-container.is-invalid .select2-selection { border-color: var(--danger-color, #dc3545) !important;} /* Error border */
    /* General Validation Error */
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('notice.index') }}">Notices</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Notice</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            {{-- Display Validation Errors from Backend --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     Please fix the following errors:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('notice.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createNoticeForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Notice Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        {{-- Show backend error if present --}}
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        {{-- Add is-invalid class based on backend error --}}
                        <select class="form-select select2-basic @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required data-placeholder="Select Category...">
                            <option value=""></option> {{-- Empty option for placeholder --}}
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div id="category_id-error" class="form-error"></div> {{-- For JS validation message --}}
                        {{-- Show backend error if present --}}
                        @error('category_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6">
                        <label for="date" class="form-label">Notice Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') }}" autocomplete="off" required placeholder="YYYY-MM-DD">
                         @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6">
                        <label for="pdf_file" class="form-label">Upload PDF <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf,application/pdf" required> {{-- More specific accept --}}
                        <small class="form-text text-muted">Max file size: 5MB.</small>
                         @error('pdf_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Notice</button>
                    <a href="{{ route('notice.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function() {
        // --- Initialize Plugins ---
        try {
            $('.select2-basic').select2({
                placeholder: "Select...", // Match data-placeholder
                allowClear: true,       // Optional: Adds a clear 'x' button
                width: '100%'           // Ensures full width
            });
        } catch (e) { console.warn("Select2 failed to initialize.")}
        try {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",    // Match validation format
                allowInput: true,       // Allow manual typing
                // You can add more Flatpickr options here if needed
                // minDate: "today",
                // defaultDate: new Date()
            });
         } catch (e) { console.warn("Flatpickr failed to initialize.")}

        // --- Custom Client-Side Validation Function for Select2 ---
         function validateSelect2($element) {
             const $container = $element.siblings('.select2-container').find('.select2-selection'); // Target the visual element
             const $errorDiv = $element.siblings('.form-error'); // Target the dedicated error div
             $errorDiv.empty(); // Clear previous JS errors
             $container.removeClass('is-invalid'); // Remove visual error state
             $element.removeClass('is-invalid');   // Remove error state from original select

             // Check if it's required and if the value is empty or null
             if ($element.prop('required') && (!$element.val() || $element.val() === '')) {
                 $errorDiv.text('This field is required.'); // Display the error message
                 $container.addClass('is-invalid');       // Add red border to Select2
                 $element.addClass('is-invalid');         // Mark original select as invalid
                 return false; // Indicate validation failure
             }
             return true; // Indicate validation success
         }

        // --- Form Submission Validation Trigger ---
        $('form#createNoticeForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);

            // Reset previous custom errors and Bootstrap states
            $('.form-error').empty();
            // $form.find('.is-invalid').removeClass('is-invalid'); // Optionally reset all fields

            // 1. Trigger standard HTML5 validation first (for text, file, date inputs)
            if ($form[0].checkValidity() === false) {
                isValid = false;
                // Find the first invalid standard field for better UX
                 $form.find(':invalid').first().focus();
            }

            // 2. Validate required Select2 fields
            $form.find('.select2-basic[required]').each(function() {
                if (!validateSelect2($(this))) {
                    isValid = false; // Mark form as invalid if Select2 fails
                }
            });

            // If any validation failed (HTML5 or custom), prevent submission
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();

                 // Scroll to the first field with an error (either standard or custom)
                 const firstError = $form.find('.is-invalid, .form-error:not(:empty)').first();
                 if (firstError.length) {
                     $('html, body').animate({
                         scrollTop: firstError.offset().top - 100 // Adjust scroll offset as needed
                     }, 500);
                 }
            }

            // Add 'was-validated' class AFTER checking to show Bootstrap feedback styles
            // for standard HTML5 fields on the first failed submit attempt.
            $form.addClass('was-validated');
        });

        // --- Clear Validation Feedback on Input/Change ---
        // Clear Select2 error feedback on change
        $('#category_id').on('change', function() {
            // Re-validate when changed (it will clear the error if now valid)
            validateSelect2($(this));
        });

         // Clear standard Bootstrap validation feedback on input/change
         // for text, date, and file inputs.
         $('form#createNoticeForm input, form#createNoticeForm select:not(.select2-basic)').on('input change', function() {
             // If the field is required and now has a value, OR if it's not required, remove invalid state
             if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) ) {
                 $(this).removeClass('is-invalid');
                 // For file input, also check if files are selected
                 if ($(this).attr('type') === 'file' && $(this).prop('required') && this.files.length === 0) {
                     $(this).addClass('is-invalid'); // Re-add if required and cleared
                 }
             } else if ($(this).prop('required')) {
                 // If required and emptied, ensure it shows as invalid immediately
                 $(this).addClass('is-invalid');
             }
         });

    }); // End $(document).ready
</script>
@endsection