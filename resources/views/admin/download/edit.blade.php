@extends('admin.master.master')

@section('title')
Edit Download | {{ $ins_name }}
@endsection

@section('css')
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
    .current-pdf-link { display: inline-block; margin-top: 5px; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            {{-- 1. Update breadcrumb --}}
            <li class="breadcrumb-item"><a href="{{ route('download.index') }}">Downloads</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ Str::limit($download->title, 30) }}</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Download</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     Please fix the following errors:
                    <ul class="mb-0 mt-2"> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- 2. Update form action and ID --}}
            <form action="{{ route('download.update', $download->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editDownloadForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $download->title) }}" required>
                         @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- 3. Remove Category Field --}}

                     <div class="col-md-6">
                        <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $download->date->format('Y-m-d')) }}" autocomplete="off" required placeholder="YYYY-MM-DD">
                         @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     <div class="col-12">
                        <label for="pdf_file" class="form-label">Upload New PDF (Optional)</label>
                        <input type="file" class="form-control @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf,application/pdf">
                        <small class="form-text text-muted">Max file size: 5MB. Leave blank to keep current file.</small>
                         @error('pdf_file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                         
                         {{-- 4. Update path for current file --}}
                         @if($download->pdf_file)
                            <a href="{{ asset('public/'.$download->pdf_file) }}" target="_blank" class="current-pdf-link small" title="View Current PDF">
                                <i class="fa fa-file-pdf text-danger me-1"></i> {{ basename($download->pdf_file) }}
                            </a>
                         @else
                             <span class="current-pdf-link small text-muted">No PDF currently uploaded.</span>
                         @endif
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Download</button>
                    <a href="{{ route('download.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function() {
        // --- Initialize Plugins ---
        try {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
         } catch (e) { console.warn("Flatpickr failed.")}

        // --- Form Submission Validation Trigger ---
        // 5. Update form ID
        $('form#editDownloadForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);
            
            if ($form[0].checkValidity() === false) {
                isValid = false;
                 $form.find(':invalid').first().focus();
            }
            
            // 6. No Select2 validation

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                const firstError = $form.find('.is-invalid').first();
                if (firstError.length) {
                    $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                }
            }
            $form.addClass('was-validated');
        });

        // --- Clear Validation Feedback on Input/Change ---
         $('form#editDownloadForm input').on('input change', function() {
             if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) ) {
                 $(this).removeClass('is-invalid');
             } else if ($(this).prop('required')) {
                 $(this).addClass('is-invalid');
             }
         });

    }); // End $(document).ready
</script>
@endsection