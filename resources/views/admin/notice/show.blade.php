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
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('notice.index') }}">Notices</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Notice</h5>
        </div>
        <div class="card-body">
            @include('flash_message')
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     Please fix the following errors:
                    <ul> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('notice.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createNoticeForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Notice Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select select2-basic @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="" disabled selected>Select Category...</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <div id="category_id-error" class="form-error"></div> {{-- For JS validation --}}
                        @error('category_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6">
                        <label for="date" class="form-label">Notice Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') }}" autocomplete="off" required>
                         @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-md-6">
                        <label for="pdf_file" class="form-label">Upload PDF <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf" required>
                        <small class="form-text text-muted">Max file size: 5MB.</small>
                         @error('pdf_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

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
            $('.select2-basic').select2({ placeholder: "Select...", allowClear: true, width: '100%' });
        } catch (e) { console.warn("Select2 failed.")}
        try {
            flatpickr(".datepicker", { dateFormat: "Y-m-d", allowInput: true });
         } catch (e) { console.warn("Flatpickr failed.")}

        // --- Custom Client-Side Validation ---
         function validateSelect2($element) {
             const $container = $element.siblings('.select2-container').find('.select2-selection');
             const $errorDiv = $element.siblings('.form-error');
             $errorDiv.empty(); $container.removeClass('is-invalid'); $element.removeClass('is-invalid');
             if ($element.prop('required') && (!$element.val() || $element.val() === '')) {
                 $errorDiv.text('This field is required.'); $container.addClass('is-invalid'); $element.addClass('is-invalid'); return false;
             } return true;
         }

        $('form#createNoticeForm').submit(function(e) {
            let isValid = true; let $form = $(this);
            $('.form-error').empty(); $('.is-invalid').removeClass('is-invalid');

            // 1. Standard HTML5
            if ($form[0].checkValidity() === false) isValid = false;
            // 2. Select2
            if (!validateSelect2($('#category_id'))) isValid = false;

            if (!isValid) {
                e.preventDefault(); e.stopPropagation();
                const firstError = $('.is-invalid, .form-error:not(:empty)').first();
                if (firstError.length) { $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); }
            }
            $form.addClass('was-validated');
        });

        // Clear Select2 error on change
        $('#category_id').on('change', function() { validateSelect2($(this)); });
         // Clear standard input errors on input/change
         $('form#createNoticeForm input, form#createNoticeForm select').on('input change', function() {
             if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) ) {
                 $(this).removeClass('is-invalid');
             }
         });

    });
</script>
@endsection