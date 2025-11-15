@extends('admin.master.master')

@section('title')
Edit Notice | {{ $ins_name }}
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
    .select2-container--default.select2-container--focus .select2-selection--single { border-color: #86b7fe; }
    .select2-container.is-invalid .select2-selection { border-color: var(--danger-color, #dc3545) !important;}
    /* General Validation Error */
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
    /* Current PDF Link Style */
    .current-pdf-link { display: inline-block; margin-top: 5px; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('notice.index') }}">Notices</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ Str::limit($notice->title, 30) }}</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Notice</h5>
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

            <form action="{{ route('notice.update', $notice->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editNoticeForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Notice Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $notice->title) }}" required>
                         @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select select2-basic @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required data-placeholder="Select Category...">
                            <option value=""></option> {{-- Empty option for placeholder --}}
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $notice->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div id="category_id-error" class="form-error"></div> {{-- JS validation --}}
                         @error('category_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6">
                        <label for="date" class="form-label">Notice Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $notice->date) }}" autocomplete="off" required placeholder="YYYY-MM-DD">
                         @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6">
                        <label for="pdf_file" class="form-label">Upload New PDF (Optional)</label>
                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf,application/pdf">
                        <small class="form-text text-muted">Max file size: 5MB. Leave blank to keep current file.</small>
                         @error('pdf_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                         {{-- Display current file link --}}
                         @if($notice->pdf_file)
                            <a href="{{ asset('public/'.$notice->pdf_file) }}" target="_blank" class="current-pdf-link small" title="View Current PDF">
                                <i class="fa fa-file-pdf text-danger me-1"></i> {{ basename($notice->pdf_file) }}
                            </a>
                         @else
                             <span class="current-pdf-link small text-muted">No PDF currently uploaded.</span>
                         @endif
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Notice</button>
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
                placeholder: "Select...",
                allowClear: true,
                width: '100%'
            });
        } catch (e) { console.warn("Select2 failed.")}
        try {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
         } catch (e) { console.warn("Flatpickr failed.")}

        // --- Custom Client-Side Validation Function for Select2 ---
         function validateSelect2($element) {
             const $container = $element.siblings('.select2-container').find('.select2-selection');
             const $errorDiv = $element.siblings('.form-error');
             $errorDiv.empty(); $container.removeClass('is-invalid'); $element.removeClass('is-invalid');
             if ($element.prop('required') && (!$element.val() || $element.val() === '')) {
                 $errorDiv.text('This field is required.'); $container.addClass('is-invalid'); $element.addClass('is-invalid'); return false;
             } return true;
         }

        // --- Form Submission Validation Trigger ---
        $('form#editNoticeForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);
            $('.form-error').empty();
            // $form.find('.is-invalid').removeClass('is-invalid'); // Optionally reset all

            // 1. Standard HTML5 Validation
            if ($form[0].checkValidity() === false) {
                isValid = false;
                 $form.find(':invalid').first().focus();
            }

            // 2. Select2 Validation
            if (!validateSelect2($('#category_id'))) {
                 isValid = false;
             }

            // If invalid, prevent submission and scroll to error
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                const firstError = $form.find('.is-invalid, .form-error:not(:empty)').first();
                if (firstError.length) {
                    $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                }
            }
            // Add Bootstrap validation class
            $form.addClass('was-validated');
        });

        // --- Clear Validation Feedback on Input/Change ---
        // Clear Select2 error feedback on change
        $('#category_id').on('change', function() {
            validateSelect2($(this));
        });

         // Clear standard Bootstrap validation feedback on input/change
         $('form#editNoticeForm input, form#editNoticeForm select:not(.select2-basic)').on('input change', function() {
             if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) ) {
                 $(this).removeClass('is-invalid');
             } else if ($(this).prop('required')) {
                 $(this).addClass('is-invalid'); // Re-add if required and emptied
             }
             // For file input, ensure it's marked invalid if required and empty
             if ($(this).attr('type') === 'file' && $(this).prop('required') && this.files.length === 0 && !$('a.current-pdf-link').length) { // Only truly invalid if no current file exists either
                 // $(this).addClass('is-invalid'); // File input validation handled by 'required'
             }
         });

    }); // End $(document).ready
</script>
@endsection