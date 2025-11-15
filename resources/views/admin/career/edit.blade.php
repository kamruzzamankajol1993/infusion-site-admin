@extends('admin.master.master')

@section('title')
Edit Career Posting | {{ $ins_name }}
@endsection

@section('css')
{{-- Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Styles for Validation Errors */
    .note-editor.note-frame.is-invalid { border-color: var(--danger-color, #dc3545) !important; }
    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('career.index') }}">Careers</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ Str::limit($career->title, 40) }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Career Posting</h5>
        </div>
        <div class="card-body">
            @include('flash_message')
            @if ($errors->any())
               {{-- ... error display ... --}}
            @endif

            <form action="{{ route('career.update', $career->id) }}" method="POST" novalidate id="editCareerForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    {{-- Title --}}
                    <div class="col-md-6">
                        <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $career->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Company Name --}}
                    <div class="col-md-6">
                        <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $career->company_name) }}" required>
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Position --}}
                    <div class="col-md-6">
                        <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $career->position) }}" required>
                        @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Job Location --}}
                     <div class="col-md-6">
                        <label for="job_location" class="form-label">Job Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('job_location') is-invalid @enderror" id="job_location" name="job_location" value="{{ old('job_location', $career->job_location) }}" required>
                        @error('job_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Age Requirement --}}
                    <div class="col-md-6">
                        <label for="age" class="form-label">Age Requirement <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age', $career->age) }}" placeholder="e.g., 25-35 years" required>
                        @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Experience Requirement --}}
                    <div class="col-md-6">
                        <label for="experience" class="form-label">Experience Requirement <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('experience') is-invalid @enderror" id="experience" name="experience" value="{{ old('experience', $career->experience) }}" placeholder="e.g., Minimum 2 years" required>
                        @error('experience') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
{{-- === ADD THIS NEW BLOCK === --}}
                    <div class="col-md-6">
                        <label for="salary" class="form-label">Salary (Optional)</label>
                        <input type="text" class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ old('salary', $career->salary) }}" placeholder="e.g., Negotiable or BDT 50,000 - 60,000">
                        @error('salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- === END NEW BLOCK === --}}
                    {{-- Qualification --}}
                     <div class="col-12">
                         <label for="qualification" class="form-label">Qualification <span class="text-danger">*</span></label>
                         <textarea class="form-control summernote @error('qualification') is-invalid @enderror" id="qualification" name="qualification" required>{{ old('qualification', $career->qualification) }}</textarea>
                         <div id="qualification-error" class="form-error"></div>
                                       <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                         @error('qualification') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    {{-- Description --}}
                    <div class="col-12">
                         <label for="description" class="form-label">Job Description / Responsibilities <span class="text-danger">*</span></label>
                         <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description" required>{{ old('description', $career->description) }}</textarea>
                         <div id="description-error" class="form-error"></div>
                                       <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                         @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    {{-- Application Deadline --}}
                    <div class="col-md-6">
                        <label for="application_deadline" class="form-label">Application Deadline <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('application_deadline') is-invalid @enderror" id="application_deadline" name="application_deadline" value="{{ old('application_deadline', $career->application_deadline) }}" autocomplete="off" required placeholder="YYYY-MM-DD">
                         @error('application_deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Application Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">Application Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $career->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Career Post</button>
                    <a href="{{ route('career.index') }}" class="btn btn-secondary">Cancel</a>
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
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d", // Standard SQL date format
                allowInput: true,     // Allows user to type date manually
            });
         } catch (e) { console.warn("Flatpickr failed to initialize.")}
         
         try {
             $('.summernote').summernote({
                 height: 150, // Or your preferred height
                 toolbar: [ // Example toolbar configuration
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                 ],
                callbacks: { // Add callback for validation
                    onChange: function(contents, $editable) { validateSummernote($(this)); }
                }
             });
        } catch (e) { console.warn("Summernote failed to initialize.")}


        // --- Custom Client-Side Validation Functions ---
        function validateSummernote($element) {
             const $editor = $element.siblings('.note-editor'); const $errorDiv = $element.siblings('.form-error');
             $errorDiv.empty(); $editor.removeClass('is-invalid'); $element.removeClass('is-invalid');
             if ($element.prop('required') && $element.summernote('isEmpty')) {
                 $errorDiv.text('This field is required.'); $editor.addClass('is-invalid'); $element.addClass('is-invalid'); return false;
             } return true;
        }


        // --- Form Submission Validation Trigger ---
        $('form#editCareerForm').submit(function(e) { // <-- CORRECTED ID
            let isValid = true;
            let $form = $(this);

            $('.form-error').empty();
            $('.is-invalid').removeClass('is-invalid');

            // 1. Standard HTML5 Validation
            if ($form[0].checkValidity() === false) { 
                isValid = false; 
            }

            // 2. Summernote Validation
            $form.find('.summernote[required]').each(function() { 
                if (!validateSummernote($(this))) isValid = false; 
            });

            if (!isValid) {
                e.preventDefault(); 
                e.stopPropagation();
                // Focus the first invalid element
                const firstError = $form.find('.is-invalid, .form-error:not(:empty)').first();
                if (firstError.length) { 
                    // Scroll to error
                    $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                    // Focus if it's a standard input
                    if(firstError.is('input') || firstError.is('textarea:not(.summernote)')) {
                         firstError.focus();
                    }
                }
            }
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on Input/Change ---
         $('form#editCareerForm input, form#editCareerForm select, form#editCareerForm textarea:not(.summernote)').on('input change', function() { // <-- CORRECTED ID
             // Only remove 'is-invalid' if required and has value, or not required
             if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) ) {
                 $(this).removeClass('is-invalid');
             }
         });
         
         // Summernote validation clear is handled by its 'onChange' callback

    }); // End $(document).ready
</script>
@endsection