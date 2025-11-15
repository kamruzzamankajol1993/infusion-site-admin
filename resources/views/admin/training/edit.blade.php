@extends('admin.master.master')

@section('title')
Edit Training | {{ $ins_name }}
@endsection

@section('css')
{{-- Summernote, Select2, Flatpickr CSS --}}

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Styles for Select2, CKEditor Error, Form Error, Image Preview */
    .select2-container .select2-selection--single { height: 38px !important; border: 1px solid #ced4da; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
    
    /* === NEW CKEDITOR STYLES === */
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
        position: relative; width: 100%; max-width: 300px; height: 200px;
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
            <li class="breadcrumb-item"><a href="{{ route('training.index') }}">Trainings</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ Str::limit($training->title, 30) }}</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Training</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            <form action="{{ route('training.update', $training->id) }}" method="POST" enctype="multipart/form-data" novalidate id="editTrainingForm">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    {{-- Left Column: Details --}}
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="title" class="form-label">Training Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $training->title) }}" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="text" class="form-control datepicker @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $training->start_date ? $training->start_date->format('Y-m-d') : '') }}" autocomplete="off">
                                @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $training->end_date ? $training->end_date->format('Y-m-d') : '') }}" autocomplete="off">
                                @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="deadline_for_registration" class="form-label">Registration Deadline</label>
                                <input type="text" class="form-control datepicker @error('deadline_for_registration') is-invalid @enderror" id="deadline_for_registration" name="deadline_for_registration" value="{{ old('deadline_for_registration', $training->deadline_for_registration ? $training->deadline_for_registration->format('Y-m-d') : '') }}" autocomplete="off">
                                @error('deadline_for_registration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-md-6">
                                <label for="training_fee" class="form-label">Training Fee (Optional)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('training_fee') is-invalid @enderror" id="training_fee" name="training_fee" value="{{ old('training_fee', $training->training_fee) }}" placeholder="e.g., 500.00">
                                @error('training_fee') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-md-6">
                                <label for="training_time" class="form-label">Training Time</label>
                                <input type="text" class="form-control @error('training_time') is-invalid @enderror" id="training_time" name="training_time" value="{{ old('training_time', $training->training_time) }}" placeholder="e.g., 9:00 AM - 5:00 PM">
                                @error('training_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-md-6">
                                <label for="training_venue" class="form-label">Training Venue</label>
                                <input type="text" class="form-control @error('training_venue') is-invalid @enderror" id="training_venue" name="training_venue" value="{{ old('training_venue', $training->training_venue) }}" placeholder="e.g., Online or Office Address">
                                @error('training_venue') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                             <div class="col-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control summernote @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $training->description) }}</textarea>
                                <div id="description-error" class="form-error"></div>
                                <small class="form-text text-muted">A general overview of the training.</small>
                                @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label for="learn_from_training" class="form-label">What You'll Learn</label>
                                <textarea class="form-control summernote @error('learn_from_training') is-invalid @enderror" id="learn_from_training" name="learn_from_training">{{ old('learn_from_training', $training->learn_from_training) }}</textarea>
                                @error('learn_from_training') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-12">
                                <label for="who_should_attend" class="form-label">Who Should Attend</label>
                                <textarea class="form-control summernote @error('who_should_attend') is-invalid @enderror" id="who_should_attend" name="who_should_attend">{{ old('who_should_attend', $training->who_should_attend) }}</textarea>
                                @error('who_should_attend') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                             <div class="col-12">
                                <label for="methodology" class="form-label">Methodology</label>
                                <textarea class="form-control summernote @error('methodology') is-invalid @enderror" id="methodology" name="methodology">{{ old('methodology', $training->methodology) }}</textarea>
                                @error('methodology') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
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
                                        <option value="upcoming" {{ old('status', $training->status) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="running" {{ old('status', $training->status) == 'running' ? 'selected' : '' }}>Running</option>
                                        <option value="postponed" {{ old('status', $training->status) == 'postponed' ? 'selected' : '' }}>Postponed</option>
                                        <option value="complete" {{ old('status', $training->status) == 'complete' ? 'selected' : '' }}>Complete</option>
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Image --}}
                                <div class="mb-3">
                                    <label for="image" class="form-label">Training Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    <small class="form-text text-muted">Size: 600x400 px, Max: 1MB. Leave blank to keep current.</small>
                                    @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                                <div class="image-preview-box">
                                    @if($training->image)
                                        <img id="imagePreview" src="{{ asset($training->image) }}" alt="Current Image">
                                        <span class="placeholder-text" style="display:none;">Image Preview<br>(600 x 400)</span>
                                    @else
                                        <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                                        <span class="placeholder-text">Image Preview<br>(600 x 400)</span>
                                    @endif
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
                                    @php
                                        $skills = old('skills', $training->skills->pluck('skill_name')->toArray()) ?? [];
                                    @endphp
                                    @if(!empty($skills))
                                        @foreach($skills as $skillName)
                                        <div class="input-group mb-2 skill-row">
                                            <input type="text" class="form-control @error('skills.*') is-invalid @enderror" name="skills[]" placeholder="Enter skill" value="{{ $skillName }}">
                                            <button class="btn btn-outline-danger removeSkillRow" type="button"><i class="fa fa-times"></i></button>
                                        </div>
                                        @endforeach
                                        @error('skills.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    @else
                                        {{-- Default first row if no skills exist --}}
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
                                <h6 class="mb-2 text-primary">Current Documents</h6>
                                @if($training->documents->isEmpty())
                                    <p class="small text-muted">No documents uploaded.</p>
                                @else
                                    <ul class="list-group list-group-flush mb-3">
                                        @foreach($training->documents as $doc)
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                                            <div class="form-check">
                                                {{-- This checkbox retains the existing doc --}}
                                                <input class="form-check-input" type="checkbox" name="existing_documents[]" value="{{ $doc->id }}" id="doc-{{ $doc->id }}" checked>
                                                <label class="form-check-label small" for="doc-{{ $doc->id }}">
                                                    {{ $doc->title }}
                                                </label>
                                            </div>
                                            <a href="{{ asset($doc->pdf_file) }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-1">
                                                <i data-feather="eye" style="width:14px;"></i> View
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <small class="form-text text-muted d-block mb-3">Uncheck a document to remove it. Add new documents below.</small>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-primary">Add New Documents</h6>
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
                                                   name="documents[{{ $index }}][title]" placeholder="e.g., Brochure" value="{{ $oldDoc['title'] ?? '' }}">
                                            <label class="form-label small">PDF File</label>
                                            <input type="file" class="form-control form-control-sm @error('documents.'.$index.'.file') is-invalid @enderror" 
                                                   name="documents[{{ $index }}][file]" accept=".pdf">
                                            <button class="btn btn-outline-danger btn-sm removeDocumentRow mt-1" type="button" style="width:100%"><i class="fa fa-times me-1"></i> Remove</button>
                                            @error('documents.'.$index.'.title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                            @error('documents.'.$index.'.file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                            <hr>
                                        </div>
                                        @endforeach
                                    @endif
                                    
                                    {{-- Add one empty row by default for adding new --}}
                                    <div class="input-group mb-2 doc-row">
                                        <label class="form-label small">Title</label>
                                        <input type="text" class="form-control form-control-sm mb-1" 
                                               name="documents[0][title]" placeholder="e.g., Brochure">
                                        <label class="form-label small">PDF File</label>
                                        <input type="file" class="form-control form-control-sm" 
                                               name="documents[0][file]" accept=".pdf">
                                        <button class="btn btn-outline-danger btn-sm removeDocumentRow mt-1" type="button" style="width:100%"><i class="fa fa-times me-1"></i> Remove</button>
                                        <hr>
                                    </div>
                                </div>
                                <small class="form-text text-muted">Both title and file are required to add a *new* document.</small>
                                @error('documents') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                                {{-- === END MODIFIED SECTION === --}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Training</button>
                    <a href="{{ route('training.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- JS Includes (Summernote, Select2, Flatpickr) --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // CKEDITOR 5 INITIALIZATION SETUP
const editors = {}; // Storage for CKEditor instances

function initializeCKEditor() {
    document.querySelectorAll('.summernote').forEach(textarea => {
        const editorId = textarea.id; 
        if (editors[editorId]) return;

        ClassicEditor
            .create(textarea, {
                // === UPDATED TOOLBAR CONFIGURATION (MAX FREE OPTIONS) ===
                toolbar: {
                    items: [
                        'heading', // Headings dropdown
                        '|',
                        // Basic Styling
                        'bold', 
                        'italic', 
                        'underline', 
                        'strikethrough', // Added: Strikethrough
                        'removeFormat',  // Added: Remove all formatting
                        '|',
                        // Lists and Alignment
                        'alignment',     // Left, Center, Right, Justify
                        'bulletedList', 
                        'numberedList', 
                        'blockquote',    // Blockquote
                        '|',
                        // Links and Specialized Formatting
                        'link', 
                        'subscript',     // Added: Subscript
                        'superscript',   // Added: Superscript
                        '|',
                        // View/Utility
                        'undo', 
                        'redo', 
                        'codeBlock',     // Code block (for displaying code)
                        'sourceEditing'  // HTML Source View
                    ]
                },
                // Configuration for the 'heading' feature
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                    ]
                },
                // Configuration for the 'alignment' feature
                alignment: {
                    options: [ 'left', 'right', 'center', 'justify' ]
                }
                // =========================================================
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
              $(this).removeClass('is-invalid');
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
             flatpickr(".datepicker", { dateFormat: "Y-m-d", allowInput: true });
         } catch (e) { console.warn("Flatpickr failed to initialize.")}
         
        // === FIX 1: Call CKEditor initialization here ===
        initializeCKEditor();
       
        // --- Image Preview Logic (No change) ---
        $("#image").change(function() {
            const input = this;
            const preview = $('#imagePreview');
            const placeholder = $('.placeholder-text');
            const originalSrc = '{{ $training->image ? asset($training->image) : "" }}';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show();
                    placeholder.hide();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                 if(originalSrc) {
                     preview.attr('src', originalSrc).show();
                     placeholder.hide();
                 } else {
                    preview.hide();
                    placeholder.show();
                 }
            }
        });

        // --- Repeater Logic (No change) ---
        let docIndex = {{ old('documents') ? count(old('documents')) : 1 }};

        $("#addSkillRow").click(function() {
             // ... skill row logic ...
             let newRow = `<div class="input-group mb-2 skill-row" style="display:none;"><input type="text" class="form-control" name="skills[]" placeholder="Enter skill"><button class="btn btn-outline-danger removeSkillRow" type="button" title="Remove Skill"><i class="fa fa-times"></i></button></div>`;
             $('#skillsRepeaterContainer').append(newRow).find('.skill-row:last').fadeIn(300).find('input').focus();
        });
        $(document).on('click', '.removeSkillRow', function() {
             $(this).closest('.skill-row').fadeOut(300, function() { $(this).remove(); });
        });
        $("#addDocumentRow").click(function() {
             // ... document row logic ...
             let newRow = `<div class="input-group mb-2 doc-row" style="display:none;"><label class="form-label small">Title</label><input type="text" class="form-control form-control-sm mb-1" name="documents[${docIndex}][title]" placeholder="e.g., Brochure"><label class="form-label small">PDF File</label><input type="file" class="form-control form-control-sm" name="documents[${docIndex}][file]" accept=".pdf"><button class="btn btn-outline-danger btn-sm removeDocumentRow mt-1" type="button" style="width:100%"><i class="fa fa-times me-1"></i> Remove</button><hr></div>`;
             $('#documentsRepeaterContainer').append(newRow).find('.doc-row:last').fadeIn(300);
             docIndex++;
        });
        $(document).on('click', '.removeDocumentRow', function() {
            $(this).closest('.doc-row').fadeOut(300, function() { $(this).remove(); });
        });
        
        // --- Form Submission Validation Trigger ---
        $('form#editTrainingForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);
            $('.form-error').empty();
            $('.is-invalid').removeClass('is-invalid');
             // Clear CKEditor error classes from all editors
            $form.find('.ck-editor__editable, .ck-toolbar').removeClass('is-invalid'); 

            // Standard HTML5 validation
            if ($form[0].checkValidity() === false) {
                isValid = false;
                 // Focus on standard invalid field if no other error took precedence
                 if (isValid) {
                     $form.find(':invalid').not('.summernote').not('input[name="skills[]"]').first().focus();
                 }
            }

             // === FIX 2: Call CKEditor validation instead of Summernote ===
            $form.find('.summernote').each(function() {
                if ($(this).prop('required') && !validateCKEditor($(this))) {
                    isValid = false;
                }
            });
             // ===================================

             if (!validateSkillsRepeater()) {
                 isValid = false;
                 // Focus on skills error if no other error took precedence
                 if ($form.find(':invalid').not('.summernote').length === 0 && !$form.find('.summernote').hasClass('is-invalid')) {
                     $form.find('input[name="skills[]"].is-invalid').first().focus();
                 }
             }

            // --- Custom validation for new doc rows ---
            $('#documentsRepeaterContainer .doc-row').each(function() {
                let $titleInput = $(this).find('input[type="text"]');
                let $fileInput = $(this).find('input[type="file"]');
                $titleInput.removeClass('is-invalid');
                $fileInput.removeClass('is-invalid');
                let titleFilled = $titleInput.val().trim() !== '';
                let fileFilled = $fileInput.prop('files').length > 0;
                if (titleFilled !== fileFilled) { 
                    isValid = false;
                    if (titleFilled) { $fileInput.addClass('is-invalid'); } 
                    else { $titleInput.addClass('is-invalid'); }
                }
            });


            if (!isValid) {
                e.preventDefault(); e.stopPropagation();
                 // Scroll to the first error: prioritize custom error classes
                 const firstError = $('.is-invalid, .form-error:not(:empty), .ck-toolbar.is-invalid').first();
                 if (firstError.length) {
                    // Scroll to the error element (toolbar if CKEditor, otherwise the element itself)
                    $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500);
                 }
            }
            $form.addClass('was-validated');
        });

    }); // End $(document).ready
</script>
@endsection