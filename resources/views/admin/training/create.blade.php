@extends('admin.master.master')

@section('title')
Add New Training | {{ $ins_name }}
@endsection

@section('css')
{{-- REMOVED: Summernote CSS --}}
{{-- Select2 CSS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Styles for Select2, CKEditor Error, Form Error, Image Preview */
    .select2-container .select2-selection--single { height: 38px !important; border: 1px solid #ced4da; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
    
    /* === CKEDITOR 5 STYLES === */
    /* Target the editor's main content area (editable) */
    .ck-editor__editable { min-height: 150px; } 
    /* Apply invalid border to the toolbar */
    .ck.ck-toolbar.is-invalid { border-color: var(--danger-color, #dc3545) !important; border-bottom: none !important; }
    /* Apply invalid border to the editable area */
    .ck.ck-editor__main.is-invalid > .ck-editor__editable { 
        border: 1px solid var(--danger-color, #dc3545) !important; 
        border-top: none !important;
    }
    /* =========================== */

    .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
    .image-preview-box {
        position: relative; width: 100%; max-width: 300px; /* 600/2 */ height: 200px; /* 400/2 */
        border: 2px dashed #ced4da; border-radius: .375rem; display: flex;
        align-items: center; justify-content: center; background-color: #f8f9fa;
        color: #6c757d; overflow: hidden; margin-top: 1rem;
    }
    .image-preview-box img { width: 100%; height: 100%; object-fit: contain; }
    .image-preview-box .placeholder-text { font-size: 0.9rem; text-align: center; }
</style>
@endsection

@section('body')
{{-- Body Content (No change) --}}
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('training.index') }}">Trainings</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Training</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            <form action="{{ route('training.store') }}" method="POST" enctype="multipart/form-data" novalidate id="createTrainingForm">
                @csrf
                <div class="row g-3">
                    {{-- Left Column: Details --}}
                    <div class="col-md-8">
                        <div class="row g-3">
                             <div class="col-md-12"> {{-- Changed from col-md-8 --}}
                                <label for="title" class="form-label">Training Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Category Select Removed --}}

                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="text" class="form-control datepicker @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" autocomplete="off">
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" autocomplete="off">
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="deadline_for_registration" class="form-label">Registration Deadline</label>
                                <input type="text" class="form-control datepicker @error('deadline_for_registration') is-invalid @enderror" id="deadline_for_registration" name="deadline_for_registration" value="{{ old('deadline_for_registration') }}" autocomplete="off">
                                @error('deadline_for_registration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-md-6">
                                <label for="training_fee" class="form-label">Training Fee (Optional)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('training_fee') is-invalid @enderror" id="training_fee" name="training_fee" value="{{ old('training_fee') }}" placeholder="e.g., 500.00">
                                @error('training_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-md-6">
                                <label for="training_time" class="form-label">Training Time</label>
                                <input type="text" class="form-control @error('training_time') is-invalid @enderror" id="training_time" name="training_time" value="{{ old('training_time') }}" placeholder="e.g., 9:00 AM - 5:00 PM">
                                @error('training_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-md-6">
                                <label for="training_venue" class="form-label">Training Venue</label>
                                <input type="text" class="form-control @error('training_venue') is-invalid @enderror" id="training_venue" name="training_venue" value="{{ old('training_venue') }}" placeholder="e.g., Online or Office Address">
                                @error('training_venue') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                             <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                                <div id="description-error" class="form-error"></div>
                                <small class="form-text text-muted">A general overview of the training.</small>
                                @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- NEW TEXT AREAS --}}
                             <div class="col-12">
                                <label for="learn_from_training" class="form-label">What You'll Learn</label>
                                <textarea class="form-control summernote @error('learn_from_training') is-invalid @enderror" id="learn_from_training" name="learn_from_training">{{ old('learn_from_training') }}</textarea>
                                @error('learn_from_training') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-12">
                                <label for="who_should_attend" class="form-label">Who Should Attend</label>
                                <textarea class="form-control summernote @error('who_should_attend') is-invalid @enderror" id="who_should_attend" name="who_should_attend">{{ old('who_should_attend') }}</textarea>
                                @error('who_should_attend') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-12">
                                <label for="methodology" class="form-label">Methodology</label>
                                <textarea class="form-control summernote @error('methodology') is-invalid @enderror" id="methodology" name="methodology">{{ old('methodology') }}</textarea>
                                @error('methodology') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Requirements Text Area Removed --}}
                        </div>
                    </div>

                    {{-- Right Column: Image, Status, Skills, Documents --}}
                    <div class="col-md-4">
                        <div class="card shadow-sm border">
                            <div class="card-body">
                                {{-- Status Field --}}
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="upcoming" {{ old('status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="running" {{ old('status') == 'running' ? 'selected' : '' }}>Running</option>
                                        <option value="postponed" {{ old('status') == 'postponed' ? 'selected' : '' }}>Postponed</option>
                                        <option value="complete" {{ old('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Image --}}
                                <div class="mb-3">
                                    <label for="image" class="form-label">Training Image <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                                    <small class="form-text text-muted">Size: 600px (Width) x 400px (Height), Max: 1MB</small>
                                    @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="image-preview-box">
                                    <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                                    <span class="placeholder-text">Image Preview<br>(600 x 400)</span>
                                </div>

                                {{-- Skills Repeater --}}
                                <hr class="my-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-primary">Skills Covered (Optional)</h6>
                                    <button type="button" class="btn btn-sm btn-success" id="addSkillRow">
                                        <i data-feather="plus" style="width:16px;"></i> Add Skill
                                    </button>
                                </div>
                                <div id="skillsRepeaterContainer">
                                    @if(old('skills'))
                                        @foreach(old('skills') as $oldSkill)
                                        <div class="input-group mb-2 skill-row">
                                            <input type="text" class="form-control @error('skills.*') is-invalid @enderror" name="skills[]" placeholder="Enter skill" value="{{ $oldSkill }}">
                                            <button class="btn btn-outline-danger removeSkillRow" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                        @endforeach
                                        @error('skills.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    @else
                                        <div class="input-group mb-2 skill-row">
                                            <input type="text" class="form-control" name="skills[]" placeholder="Enter skill">
                                            <button class="btn btn-outline-danger removeSkillRow" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                    @endif
                                </div>
                                <small class="form-text text-muted">List the key skills participants will gain (optional).</small>
                                @error('skills') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror

                                {{-- Document Uploads --}}
                                <hr class="my-4">
                                {{-- === MODIFIED DOCUMENT SECTION === --}}
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-primary">Training Documents (PDF)</h6>
                                    <button type="button" class="btn btn-sm btn-success" id="addDocumentRow">
                                        <i data-feather="plus" style="width:16px;"></i> Add Doc
                                    </button>
                                </div>
                                <div id="documentsRepeaterContainer">
                                    {{-- Handle 'old' input if validation fails --}}
                                    @if(old('documents'))
                                        @foreach(old('documents') as $index => $oldDoc)
                                        <div class="input-group mb-2 doc-row">
                                            <label class="form-label small">Title</label>
                                            <input type="text" class="form-control form-control-sm mb-1 @error('documents.'.$index.'.title') is-invalid @enderror" 
                                                   name="documents[{{ $index }}][title]" placeholder="e.g., Brochure" value="{{ $oldDoc['title'] ?? '' }}" required>
                                            <label class="form-label small">PDF File</label>
                                            <input type="file" class="form-control form-control-sm @error('documents.'.$index.'.file') is-invalid @enderror" 
                                                   name="documents[{{ $index }}][file]" accept=".pdf" required>
                                            <button class="btn btn-outline-danger btn-sm removeDocumentRow mt-1" type="button" style="width:100%"><i class="fa fa-times me-1"></i> Remove</button>
                                            @error('documents.'.$index.'.title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                            @error('documents.'.$index.'.file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                            <hr>
                                        </div>
                                        @endforeach
                                    @else
                                        {{-- Start with one empty row --}}
                                        <div class="input-group mb-2 doc-row">
                                            <label class="form-label small">Title</label>
                                            <input type="text" class="form-control form-control-sm mb-1" 
                                                   name="documents[0][title]" placeholder="e.g., Brochure" required>
                                            <label class="form-label small">PDF File</label>
                                            <input type="file" class="form-control form-control-sm" 
                                                   name="documents[0][file]" accept=".pdf" required>
                                            <button class="btn btn-outline-danger btn-sm removeDocumentRow mt-1" type="button" style="width:100%"><i class="fa fa-times me-1"></i> Remove</button>
                                            <hr>
                                        </div>
                                    @endif
                                </div>
                                @error('documents') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                                {{-- === END MODIFIED SECTION === --}}

                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Save Training</button>
                    <a href="{{ route('training.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
{{-- End Body Content --}}

@section('script')
{{-- REMOVED: Summernote JS --}}
{{-- Select2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // CKEDITOR 5 INITIALIZATION SETUP
    const editors = {}; 

    function initializeCKEditor() {
        document.querySelectorAll('.summernote').forEach(textarea => {
            const editorId = textarea.id; 
            if (editors[editorId]) return;

            ClassicEditor
                .create(textarea, {
                    // === EXPANDED TOOLBAR CONFIGURATION ===
                    toolbar: {
                        items: [
                            'heading', 
                            '|',
                            'bold', 
                            'italic', 
                            'underline', 
                            'strikethrough', 
                            'removeFormat',  
                            '|',
                            'alignment',     
                            'bulletedList', 
                            'numberedList', 
                            'blockquote',    
                            '|',
                            'link', 
                            'subscript',     
                            'superscript',   
                            '|',
                            'undo', 
                            'redo', 
                            'codeBlock',     
                            'sourceEditing'  
                        ]
                    },
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                            { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                        ]
                    },
                    alignment: {
                        options: [ 'left', 'right', 'center', 'justify' ]
                    }
                    // ======================================
                })
                .then(editor => {
                    editors[editorId] = editor;
                    
                    const toolbarElement = editor.ui.view.toolbar.element;
                    const editableElement = editor.ui.view.editable.element;
                    
                    editor.model.document.on('change:data', () => {
                         toolbarElement.classList.remove('is-invalid');
                         editableElement.classList.remove('is-invalid');
                         $('#' + editorId).siblings('.form-error').empty();
                    });
                })
                .catch(error => {
                    console.error(`CKEditor initialization failed for #${editorId}:`, error);
                });
        });
    }

    // --- Custom Client-Side Validation Functions ---
    function validateCKEditor($element) {
         const editorId = $element.attr('id');
         const $errorDiv = $element.siblings('.form-error');
         const editorInstance = editors[editorId];
         
         if (!editorInstance) { return true; }

         const toolbarElement = editorInstance.ui.view.toolbar.element;
         const editableElement = editorInstance.ui.view.editable.element;
         const content = editorInstance.getData().trim();
         
         $errorDiv.empty(); 
         toolbarElement.classList.remove('is-invalid');
         editableElement.classList.remove('is-invalid');

         if ($element.prop('required') && content === '') {
             $errorDiv.text('This field is required.'); 
             toolbarElement.classList.add('is-invalid'); 
             editableElement.classList.add('is-invalid');
             return false;
         } 
         return true;
    }

    function validateSkillsRepeater() {
         let isValid = true;
         $('input[name="skills[]"]').each(function() {
             if($(this).hasClass('is-invalid')) {
                 isValid = false;
             }
         });
         return isValid;
    }
    // ---------------------------------------------

    $(document).ready(function() {
        // --- Initialize Plugins ---
        try {
            flatpickr(".datepicker", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
         } catch (e) { console.warn("Flatpickr failed to initialize.")}
         
        // === FIX: Call CKEditor initialization here ===
        initializeCKEditor();

        // --- Image Preview Logic (No change) ---
        $("#image").change(function() {
            const input = this;
            const preview = $('#imagePreview');
            const placeholder = $('.placeholder-text');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show();
                    placeholder.hide();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                 preview.hide();
                 placeholder.show();
            }
        });

        // --- Skills Repeater Logic (No change) ---
        $("#addSkillRow").click(function() {
            let newRow = `
            <div class="input-group mb-2 skill-row" style="display: none;">
                <input type="text" class="form-control" name="skills[]" placeholder="Enter skill">
                <button class="btn btn-outline-danger removeSkillRow" type="button" title="Remove Skill"><i class="fa fa-times"></i></button>
            </div>`;
             $('#skillsRepeaterContainer').append(newRow);
             $('#skillsRepeaterContainer .skill-row:last').fadeIn(300).find('input').focus();
        });

        // Remove Skill Row
        $(document).on('click', '.removeSkillRow', function() {
            $(this).closest('.skill-row').fadeOut(300, function() { $(this).remove(); });
        });

        // --- Document Repeater Logic (No change) ---
        let docIndex = {{ old('documents') ? count(old('documents')) : 1 }};

        $("#addDocumentRow").click(function() {
            let newRow = `
            <div class="input-group mb-2 doc-row" style="display:none;">
                <label class="form-label small">Title</label>
                <input type="text" class="form-control form-control-sm mb-1" 
                       name="documents[${docIndex}][title]" placeholder="e.g., Brochure" required>
                <label class="form-label small">PDF File</label>
                <input type="file" class="form-control form-control-sm" 
                       name="documents[${docIndex}][file]" accept=".pdf" required>
                <button class="btn btn-outline-danger btn-sm removeDocumentRow mt-1" type="button" style="width:100%"><i class="fa fa-times me-1"></i> Remove</button>
                <hr>
            </div>`;
            $('#documentsRepeaterContainer').append(newRow).find('.doc-row:last').fadeIn(300);
            docIndex++;
        });

        // Remove Document Row
        $(document).on('click', '.removeDocumentRow', function() {
            $(this).closest('.doc-row').fadeOut(300, function() { $(this).remove(); });
        });

        // --- Form Submission Validation Trigger ---
        $('form#createTrainingForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);

            $('.form-error').empty();
            $('.is-invalid').removeClass('is-invalid');
            // Clear CKEditor error classes from all editors
            $form.find('.ck-editor__editable, .ck-toolbar').removeClass('is-invalid'); 

            // Standard HTML5 validation
            if ($form[0].checkValidity() === false) { 
                isValid = false; 
                 $form.find(':invalid').not('.summernote').not('input[name="skills[]"]').first().focus(); 
            }
            
            // === FIX: CKEditor validation ===
            $form.find('.summernote[required]').each(function() { 
                if (!validateCKEditor($(this))) { // Calling the new function
                    isValid = false; 
                } 
            });
            // ===================================
            
             if (!validateSkillsRepeater()) { 
                 isValid = false; 
                 if ($form.find(':invalid').not('.summernote').length === 0 && !$form.find('.summernote[required]').hasClass('is-invalid')) {
                     $form.find('input[name="skills[]"].is-invalid').first().focus();
                 }
             }

            if (!isValid) {
                e.preventDefault(); 
                e.stopPropagation();
                
                // Scroll to the first error: prioritize custom error classes
                const firstError = $('.is-invalid, .form-error:not(:empty), .ck-toolbar.is-invalid').first();
                if (firstError.length) { 
                    $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                }
            }
            $form.addClass('was-validated'); 
        });

    }); // End $(document).ready
</script>
@endsection