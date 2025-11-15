@extends('admin.master.master')

@section('title')
Add New Career Posting | {{ $ins_name }}
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
            <li class="breadcrumb-item active" aria-current="page">Add New Posting</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Career Posting</h5>
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

            <form action="{{ route('career.store') }}" method="POST" novalidate id="createCareerForm">
                @csrf
                <div class="row g-3">
                    {{-- Title --}}
                    <div class="col-md-6">
                        <label for="title" class="form-label">Job Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Company Name --}}
                    <div class="col-md-6">
                        <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $ins_name ?? 'IIFC') }}" required> {{-- Default to $ins_name if available --}}
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Position --}}
                    <div class="col-md-6">
                        <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" required>
                        @error('position') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- Job Location --}}
                     <div class="col-md-6">
                        <label for="job_location" class="form-label">Job Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('job_location') is-invalid @enderror" id="job_location" name="job_location" value="{{ old('job_location') }}" required>
                        @error('job_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Age Requirement --}}
                    <div class="col-md-6">
                        <label for="age" class="form-label">Age Requirement <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ old('age') }}" placeholder="e.g., 25-35 years or Not exceeding 30 years" required>
                        @error('age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Experience Requirement --}}
                    <div class="col-md-6">
                        <label for="experience" class="form-label">Experience Requirement <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('experience') is-invalid @enderror" id="experience" name="experience" value="{{ old('experience') }}" placeholder="e.g., Minimum 2 years in relevant field" required>
                        @error('experience') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
{{-- === ADD THIS NEW BLOCK === --}}
                    <div class="col-md-12">
                        <label for="salary" class="form-label">Salary (Optional)</label>
                        <input type="text" class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" value="{{ old('salary') }}" placeholder="e.g., Negotiable or BDT 50,000 - 60,000">
                        @error('salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    {{-- === END NEW BLOCK === --}}
                    {{-- Qualification --}}
                     <div class="col-12">
                         <label for="qualification" class="form-label">Qualification <span class="text-danger">*</span></label>
                         <textarea class="form-control summernote @error('qualification') is-invalid @enderror" id="qualification" name="qualification" required>{{ old('qualification') }}</textarea>
                         <div id="qualification-error" class="form-error"></div>
                                       <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                         @error('qualification') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    {{-- Description --}}
                    <div class="col-12">
                         <label for="description" class="form-label">Job Description / Responsibilities <span class="text-danger">*</span></label>
                         <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description" required>{{ old('description') }}</textarea>
                         <div id="description-error" class="form-error"></div>
                                       <small class="form-text text-muted">To show text in list form, select the entire text and click the unorder or order button.</small>
                         @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                    {{-- Application Deadline --}}
                    <div class="col-md-6">
                        <label for="application_deadline" class="form-label">Application Deadline <span class="text-danger">*</span></label>
                        <input type="text" class="form-control datepicker @error('application_deadline') is-invalid @enderror" id="application_deadline" name="application_deadline" value="{{ old('application_deadline') }}" autocomplete="off" required placeholder="YYYY-MM-DD">
                         @error('application_deadline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                     {{-- Application Email --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">Application Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Career Post</button>
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
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- SweetAlert (ensure loaded in master) --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}

<script>
    $(document).ready(function() {
        // --- Initialize Plugins ---
        try {
            $('.select2-basic').select2({
                placeholder: "Select...",
                allowClear: true, // Optional: Adds a clear 'x' button
                width: '100%' // Ensures Select2 takes full width of its container
            });
        } catch (e) { console.warn("Select2 failed to initialize.")}
        try {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d", // Standard SQL date format
                allowInput: true      // Allows user to type date manually
            });
         } catch (e) { console.warn("Flatpickr failed to initialize.")}
        try {
             $('.summernote').summernote({
                 height: 150, // Adjust height as preferred
                 toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    // ['fontsize', ['fontsize']], // Optional
                    // ['color', ['color']], // Optional
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']], // Removed 'picture' as less common here
                    ['view', ['fullscreen', 'codeview', 'help']]
                 ],
                callbacks: { // Add callback for validation
                    onChange: function(contents, $editable) { validateSummernote($(this)); }
                }
             });
        } catch (e) { console.warn("Summernote failed to initialize.")}

        // --- Image Preview Logic ---
        $("#image").change(function() {
            const input = this;
            const preview = $('#imagePreview');
            const placeholder = $('.placeholder-text'); // Get the placeholder text span

            if (input.files && input.files[0]) {
                // Optional: Client-side file size check
                const fileSize = input.files[0].size / 1024; // in KB
                if (fileSize > 1024) { // Max 1MB Check
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Too Large',
                        text: 'Image size should not exceed 1MB.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    $(this).val(''); // Clear the input
                    preview.attr('src','#').hide(); // Reset preview
                    placeholder.show();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show(); // Show preview img
                    placeholder.hide(); // Hide placeholder text
                };
                reader.readAsDataURL(input.files[0]); // Read file as Data URL
            } else {
                 preview.attr('src','#').hide(); // Hide preview img
                 placeholder.show(); // Show placeholder text
            }
        });

        // --- Skills Repeater Logic ---

        // Function to check the state of the last skill input and toggle the add button
        function checkLastSkillInput() {
            const $lastInput = $('#skillsRepeaterContainer .skill-row:last input[type="text"]');
            const $addButton = $('#addSkillRow'); // Assuming your button has id="addSkillRow"

            if ($lastInput.length === 0 || $lastInput.val().trim() !== '') {
                // Enable button if no inputs exist OR if the last one has text
                $addButton.prop('disabled', false);
                 // Optional: Change button appearance (e.g., remove a 'disabled' class)
                 $addButton.removeClass('btn-secondary').addClass('btn-success');
            } else {
                // Disable button if the last input exists and is empty
                $addButton.prop('disabled', true);
                // Optional: Change button appearance
                 $addButton.removeClass('btn-success').addClass('btn-secondary');
            }
        }

        // Initial check when the page loads
        checkLastSkillInput();

        // Check whenever the user types in ANY skill input
        $(document).on('input', '#skillsRepeaterContainer input[name="skills[]"]', function() {
            // We only care about the state of the *last* input to enable/disable the add button
             checkLastSkillInput();
        });


        $("#addSkillRow").click(function() {
            console.log('Add Skill button clicked!'); // Keep for debugging if needed

            const lastInput = $('#skillsRepeaterContainer .skill-row:last input[type="text"]');
            if (lastInput.length > 0 && lastInput.val().trim() === '') {
                 lastInput.focus(); // Focus the empty input

                 // --- Use SweetAlert for Notification ---
                 Swal.fire({
                     toast: true, // Use toast notification
                     icon: 'info',
                     title: 'Please fill the current skill field before adding a new one.',
                     position: 'top-end', // Position at top-right
                     showConfirmButton: false, // No OK button needed
                     timer: 3000, // Auto-close after 3 seconds
                     timerProgressBar: true // Show progress bar
                 });
                 return; // Stop execution here
                 // --- END SweetAlert ---
            }

            // Define the HTML for the new row
            let newRow = `
            <div class="input-group mb-2 skill-row" style="display: none;"> {{-- Start hidden --}}
                <input type="text" class="form-control" name="skills[]" placeholder="Enter skill" required>
                <button class="btn btn-outline-danger removeSkillRow" type="button" title="Remove Skill"><i class="fa fa-times"></i></button>
            </div>`;

            // Append the new row and then fade it in
             $('#skillsRepeaterContainer').append(newRow);
             $('#skillsRepeaterContainer .skill-row:last').fadeIn(300).find('input').focus(); // Fade in and focus

             // --- Disable button immediately after adding a new empty row ---
             checkLastSkillInput();
        });

        // Remove Skill Row (using event delegation)
        $(document).on('click', '.removeSkillRow', function() {
            const skillRows = $('#skillsRepeaterContainer .skill-row');
            if (skillRows.length > 1) {
                 // Fade out before removing
                 $(this).closest('.skill-row').fadeOut(300, function() {
                     $(this).remove();
                     // After removing, check the state of the *new* last input
                     checkLastSkillInput();
                     // Re-validate skills section
                     if (typeof validateSkillsRepeater === 'function') {
                         validateSkillsRepeater();
                     }
                 });
            } else {
                 // Clear the value if it's the last one
                 $(this).closest('.skill-row').find('input').val('');
                 Swal.fire({
                     toast: true, icon:'info', title: 'At least one skill input is recommended.',
                     position: 'top-end', showConfirmButton: false, timer: 2000
                    });
                 // Still need to check button state after clearing
                 checkLastSkillInput();
            }
        });


        // --- Custom Client-Side Validation Functions ---
        function validateSummernote($element) {
             const $editor = $element.siblings('.note-editor');
             const $errorDiv = $element.siblings('.form-error');
             $errorDiv.empty(); $editor.removeClass('is-invalid'); $element.removeClass('is-invalid');
             if ($element.prop('required') && $element.summernote('isEmpty')) {
                 $errorDiv.text('This field is required.'); $editor.addClass('is-invalid'); $element.addClass('is-invalid'); return false;
             } return true;
        }

         function validateSelect2($element) {
             const $container = $element.siblings('.select2-container').find('.select2-selection');
             const $errorDiv = $element.siblings('.form-error');
             $errorDiv.empty(); $container.removeClass('is-invalid'); $element.removeClass('is-invalid');
             if ($element.prop('required') && (!$element.val() || $element.val() === '')) {
                 $errorDiv.text('This field is required.'); $container.addClass('is-invalid'); $element.addClass('is-invalid'); return false;
             } return true;
         }

        // Validation for Skills Repeater
        function validateSkillsRepeater() {
             let skillFilled = false;
             const $repeaterContainer = $('#skillsRepeaterContainer');
             const $errorDivGeneral = $repeaterContainer.next('.form-error.skills-general-error'); // More specific selector
             $errorDivGeneral.remove(); // Remove previous general skills error

             $('input[name="skills[]"]').each(function() {
                 $(this).removeClass('is-invalid'); // Clear individual input errors first
                 if ($(this).val().trim() !== '') {
                     skillFilled = true;
                 } else if ($(this).prop('required')) {
                     // Mark empty required inputs as invalid
                     $(this).addClass('is-invalid');
                 }
             });

             const isSkillsSectionRequired = true; // Assuming at least one skill is mandatory overall
             if (isSkillsSectionRequired && !skillFilled && $repeaterContainer.find('.skill-row').length > 0) {
                 // Add a specific class to the error div for easier removal
                 $repeaterContainer.after('<div class="form-error skills-general-error">Please enter at least one skill or remove empty rows.</div>');
                 // Highlight the first input in the repeater section
                 $repeaterContainer.find('.skill-row:first input').addClass('is-invalid');
                 return false; // Invalid
             }
             return true; // Valid
        }


        // --- Form Submission Validation Trigger ---
        $('form#createTrainingForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);

            // Reset custom errors first
            $('.form-error').empty();
            $('.is-invalid').removeClass('is-invalid'); // Clear previous Bootstrap errors

            // 1. Trigger standard HTML5 validation and check validity
            if ($form[0].checkValidity() === false) {
                isValid = false;
                 // Find first invalid standard field
                 $form.find(':invalid').first().focus();
            }

            // 2. Validate all required Summernote fields
            $form.find('.summernote[required]').each(function() {
                if (!validateSummernote($(this))) {
                    isValid = false;
                }
            });

            // 3. Validate all required Select2 fields
            $form.find('.select2-basic[required]').each(function() {
                if (!validateSelect2($(this))) {
                    isValid = false;
                }
            });

            // 4. Validate Skills Repeater
            if (!validateSkillsRepeater()) {
                 isValid = false;
            }


            // If any validation failed, prevent submission
            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();

                 // Scroll to the first visible error message or invalid field for better UX
                 const firstError = $('.is-invalid, .form-error:not(:empty)').first();
                 if (firstError.length) {
                     $('html, body').animate({
                         scrollTop: firstError.offset().top - 100 // Adjust offset from top
                     }, 500);
                 }
            }

            // Add 'was-validated' class AFTER checking to show Bootstrap feedback styles
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on Input/Change ---
        // Clear Select2 error on change
        $('.select2-basic').on('change', function() {
            if ($(this).prop('required')) {
                 validateSelect2($(this));
            } else {
                  $(this).removeClass('is-invalid');
                  $(this).siblings('.select2-container').find('.select2-selection').removeClass('is-invalid');
                  $(this).siblings('.form-error').empty();
            }
         });

         // Clear standard input/select errors on input/change
         $('form#createTrainingForm input, form#createTrainingForm select').on('input change', function() {
             // Only remove 'is-invalid' if required and has value, or not required
             if ( (!$(this).prop('required')) || ($(this).prop('required') && $(this).val()) || ($(this).attr('type') === 'file' && this.files.length > 0) ) {
                 $(this).removeClass('is-invalid');
             } else if ($(this).prop('required')) {
                 // If it's required and becomes empty, re-add invalid class
                 // Bootstrap's 'was-validated' usually handles showing it on submit,
                 // but this provides slightly more immediate feedback if desired.
                 $(this).addClass('is-invalid');
             }
         });

         // Clear skill error on input
         $(document).on('input', 'input[name="skills[]"]', function() {
            if ($(this).val().trim() !== '') {
                $(this).removeClass('is-invalid'); // Remove error from this specific input
            }
            // Basic check to remove the general skills error message if ANY skill has value
            let anySkillFilled = false;
            $('input[name="skills[]"]').each(function() { if ($(this).val().trim() !== '') anySkillFilled = true; });
            if(anySkillFilled) $('#skillsRepeaterContainer').next('.form-error.skills-general-error').remove(); // Use specific class
        });


    }); // End $(document).ready
</script>
@endsection