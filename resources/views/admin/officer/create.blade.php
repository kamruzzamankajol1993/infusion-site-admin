@extends('admin.master.master')

@section('title')
Add New Officer | {{ $ins_name ?? 'Admin Panel' }}
@endsection

@section('css')
    {{-- Add Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- Summernote CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .select2-container .select2-selection--single { height: 38px; }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 36px; }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px; }
        .select2-container--default .select2-selection--multiple { border-color: #ced4da; min-height: 38px;} /* Added min-height */

        /* Style for Summernote border on validation error */
        .note-editor.note-frame.is-invalid { border-color: var(--bs-danger, #dc3545) !important; }
        /* Style for Select2 border on validation error */
        .select2-container .select2-selection.is-invalid { border-color: var(--bs-danger, #dc3545) !important; }
        /* Error message styling */
        .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--bs-danger, #dc3545); display: block; width: 100%;}

        .image-preview-box {
            position: relative; width: 100%; max-width: 313px; height: 374px;
            border: 2px dashed #ced4da; border-radius: .375rem; display: flex;
            align-items: center; justify-content: center; background-color: #f8f9fa;
            color: #6c757d; overflow: hidden; margin-top: 1rem;
        }
        .image-preview-box img { width: 100%; height: 100%; object-fit: cover; }
        .image-preview-box .placeholder-text { font-size: 0.9rem; padding: 1rem; text-align: center; }
        /* Repeater row spacing */
        .repeater-row { margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee; }
        .repeater-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    </style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('officer.index') }}">Officers</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add New</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Add New Officer</h5>
        </div>
        <div class="card-body">
            {{-- Flash Messages --}}
            @include('flash_message')

            {{-- Display Validation Errors (Server-side) --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p class="fw-bold">Please fix the following errors:</p>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Officer Form --}}
            <form action="{{ route('officer.store') }}" method="POST" enctype="multipart/form-data" id="createOfficerForm" novalidate>
                @csrf

                {{-- Basic Information Section --}}
                <h5 class="mt-2 mb-3 text-primary border-bottom pb-2">Basic Information</h5>
                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-8">
                        <div class="row">
                            {{-- Name (Required) --}}
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- Status (Optional, defaults Active) --}}
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                           {{-- Show Profile Button --}}
                            <div class="col-md-4 mb-3"> {{-- UPDATED FIELD --}}
                                <label class="form-label d-block">Show Profile Btn <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input @error('show_profile_details_button') is-invalid @enderror" type="radio" name="show_profile_details_button" id="show_profile_yes" value="1" {{ old('show_profile_details_button', '1') == '1' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="show_profile_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input @error('show_profile_details_button') is-invalid @enderror" type="radio" name="show_profile_details_button" id="show_profile_no" value="0" {{ old('show_profile_details_button') == '0' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="show_profile_no">No</label>
                                </div>
                                @error('show_profile_details_button')
                                    {{-- Display error below the radio group --}}
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Email (Optional) --}}
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="example@domain.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                             {{-- Phone (Optional, 11 digits) --}}
                            <div class="col-md-4 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="01xxxxxxxxx" pattern="\d{11}" title="Phone number must be 11 digits">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                         
                            </div>
                            {{-- ADD THIS NEW BLOCK --}}
                            <div class="col-md-4 mb-3">
                                <label for="mobile_number" class="form-label">Mobile Number (Optional)</label>
                                <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" placeholder="e.g., 01xxxxxxxxx" pattern="\d{11}" title="Mobile number must be 11 digits">
                                @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- END OF NEW BLOCK --}}
                            {{-- Start Date (Optional) --}}
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="text" class="form-control datepicker @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" placeholder="YYYY-MM-DD" autocomplete="off">
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- End Date (Optional) --}}
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" placeholder="YYYY-MM-DD (Leave blank if current)" autocomplete="off">
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                             {{-- Description (Optional) --}}
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description / Bio</label>
                                <textarea class="form-control summernote @error('description') is-invalid @enderror" id="summernote" name="description">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div id="summernote-error" class="form-error"></div> {{-- Client-side error msg --}}
                                <small class="form-text text-muted">To show text in list form, select text and use list buttons.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Image (Required) --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Profile Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp" required>
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="form-text text-muted">Size: 313x374 px, Max: 1024KB</small>
                        </div>
                        {{-- Image Preview Area --}}
                        <div class="image-preview-box">
                            <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                            <span class="placeholder-text">Image Preview<br>(313 x 374)</span>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                {{-- Categories Section (Required) --}}
                <h5 class="mb-3 text-primary border-bottom pb-2">Assign Categories</h5>
                <div class="mb-3">
                    <label for="categories" class="form-label">Select Categories <span class="text-danger">*</span></label>
                    <select class="form-control select2 @error('categories') is-invalid @enderror" id="categories" name="categories[]" multiple="multiple" required>
                        {{-- Options populated by controller --}}
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('categories')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror {{-- Ensure error shows --}}
                    <div id="categories-error" class="form-error"></div> {{-- Client-side error msg --}}
                </div>

                <hr class="my-4">

                {{-- Department Info Repeater (Optional Section) --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 text-primary border-bottom pb-2 d-inline-block">Department & Designation</h5>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addDeptRow">
                        <i data-feather="plus" style="width:16px;"></i> Add Row
                    </button>
                </div>
                <div id="deptRepeaterContainer">
                    {{-- Initial rows added by JS if needed, also handles old() input --}}
                    @if(old('department_info'))
                        @foreach(old('department_info') as $index => $info)
                        <div class="row align-items-center repeater-row dept-row" data-index="{{ $index }}">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Designation </label>
                                <select class="form-select @error('department_info.'.$index.'.designation_id') is-invalid @enderror" name="department_info[{{ $index }}][designation_id]" >
                                    <option value="">Select...</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ ($info['designation_id'] ?? '') == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_info.'.$index.'.designation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Department </label>
                                <select class="form-select @error('department_info.'.$index.'.department_id') is-invalid @enderror" name="department_info[{{ $index }}][department_id]" >
                                    <option value="">Select...</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" {{ ($info['department_id'] ?? '') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                                @error('department_info.'.$index.'.department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Additional Text</label>
                                <input type="text" class="form-control @error('department_info.'.$index.'.additional_text') is-invalid @enderror" name="department_info[{{ $index }}][additional_text]" placeholder="e.g., (On Leave)" value="{{ $info['additional_text'] ?? '' }}">
                                @error('department_info.'.$index.'.additional_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-1 mb-3 pt-3 text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger removeDeptRow"><i class="fa fa-trash"></i></button>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div id="dept-error" class="form-error mb-3"></div> {{-- Client-side error msg --}}

                <hr class="my-4">

                {{-- Expert Areas Repeater (Optional Section) --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                     <h5 class="mb-0 text-primary border-bottom pb-2 d-inline-block">Expert Areas</h5>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addExpertAreaRow">
                        <i data-feather="plus" style="width:16px;"></i> Add Area
                    </button>
                </div>
                <div id="expertAreaRepeaterContainer">
                    {{-- Initial rows added by JS if needed, also handles old() input --}}
                    @if(old('expert_areas'))
                        @foreach(old('expert_areas') as $index => $area)
                            @if(!is_null($area)) {{-- Only show if old value exists --}}
                                <div class="row align-items-center repeater-row expert-area-row" data-index="{{ $index }}">
                                    <div class="col-md-11 mb-3">
                                        {{-- Hide label for cleaner look --}}
                                        {{-- <label class="form-label">Expert Area {{ $loop->iteration }}</label> --}}
                                        <input type="text" class="form-control @error('expert_areas.'.$index) is-invalid @enderror" name="expert_areas[{{ $index }}]" placeholder="Enter expert area (e.g., Financial Modeling)" value="{{ $area }}">
                                        @error('expert_areas.'.$index)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-1 mb-3 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger removeExpertAreaRow"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
                {{-- End Expert Areas --}}

                <hr class="my-4">

                {{-- Social Links Repeater (Optional Section) --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                     <h5 class="mb-0 text-primary border-bottom pb-2 d-inline-block">Social Links</h5>
                    <button type="button" class="btn btn-sm btn-outline-success" id="addSocialRow">
                        <i data-feather="plus" style="width:16px;"></i> Add Link
                    </button>
                </div>
                <div id="socialRepeaterContainer">
                    {{-- Initial rows added by JS if needed, also handles old() input --}}
                     @if(old('social_links'))
                        @foreach(old('social_links') as $index => $link)
                            @if(!empty($link['title']) || !empty($link['link'])) {{-- Only show if old value exists --}}
                            <div class="row align-items-center repeater-row social-row" data-index="{{ $index }}">
                                <div class="col-md-4 mb-3">
                                    {{-- <label class="form-label">Title</label> --}}
                                    <input type="text" class="form-control @error('social_links.'.$index.'.title') is-invalid @enderror" name="social_links[{{ $index }}][title]" placeholder="e.g., LinkedIn" value="{{ $link['title'] ?? '' }}">
                                     @error('social_links.'.$index.'.title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-7 mb-3">
                                    {{-- <label class="form-label">Link (URL)</label> --}}
                                    <input type="url" class="form-control @error('social_links.'.$index.'.link') is-invalid @enderror" name="social_links[{{ $index }}][link]" placeholder="https://..." value="{{ $link['link'] ?? '' }}">
                                     @error('social_links.'.$index.'.link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-1 mb-3 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger removeSocialRow"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>
                {{-- End Social Links --}}

                {{-- Submit Button --}}
                <div class="text-end mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i data-feather="save" class="me-1" style="width:18px;"></i> Save Officer
                    </button>
                    <a href="{{ route('officer.index') }}" class="btn btn-secondary btn-lg ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
    {{-- Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select one or more categories",
                allowClear: true,
                 width: '100%'
            });

            // Initialize Flatpickr
            try {
                flatpickr(".datepicker", { dateFormat: "Y-m-d", allowInput: true });
            } catch(e) { console.warn("Flatpickr not loaded."); }

            // Initialize Summernote
            let summernoteInstance = null;
            try {
                 summernoteInstance = $('#summernote').summernote({
                    height: 150,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['view', ['fullscreen']]
                    ],
                     callbacks: {
                        onChange: function(contents, $editable) { validateSummernote(); }
                    }
                });
            } catch(e) { console.warn("Summernote not loaded."); }

            // Image Preview
            $("#image").change(function() {
                const input = this;
                const preview = $('#imagePreview');
                const placeholder = $('.image-preview-box .placeholder-text');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) { preview.attr('src', e.target.result).show(); placeholder.hide(); };
                    reader.readAsDataURL(input.files[0]);
                } else { preview.hide().attr('src', '#'); placeholder.show(); }
            });

            // --- Client-Side Validation Functions ---
            function validateSummernote() {
                // Not required anymore, just clear errors
                $('#summernote-error').empty();
                $('#summernote').next('.note-editor').removeClass('is-invalid');
                return true;
            }
            function validateSelect2() {
                const $select2 = $('#categories');
                const $errorDiv = $('#categories-error');
                $errorDiv.empty();
                $select2.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                // Category IS required
                const categories = $select2.val();
                if (!categories || categories.length === 0) {
                   $errorDiv.text('Please assign at least one category.');
                   $select2.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                   return false;
                }
                return true;
            }
            function validateDepartmentRows() {
                // Not required anymore, just clear errors
                $('#dept-error').empty();
                // Check if ANY row exists and has BOTH dropdowns selected
                 let hasValidRow = false;
                 $('.dept-row').each(function() {
                     const designation = $(this).find('select[name*="[designation_id]"]').val();
                     const department = $(this).find('select[name*="[department_id]"]').val();
                     // If a row exists, it needs both fields to be considered valid for the pair
                     if (designation && department) {
                         hasValidRow = true;
                     }
                     // But an empty row is also okay since the section is optional
                 });
                 // This validation is tricky. The controller handles required_with.
                 // We primarily rely on HTML5 'required' for individual fields if a row is added.
                return true; // Keep client-side simple, rely on server
            }

            // --- Form Submission Validation ---
            var $form = $('#createOfficerForm');
            $form.submit(function(e) {
                let isValid = true;
                isValid = validateSelect2() && isValid; // Only validate category client-side
                isValid = validateSummernote() && isValid; // Run to clear errors if needed
                isValid = validateDepartmentRows() && isValid; // Run to clear errors if needed

                // Check HTML5 validation for other required fields (Name, Image)
                if ($form[0].checkValidity() === false) {
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault(); e.stopPropagation();
                    const firstError = $form.find('.is-invalid, .select2-selection.is-invalid, .note-editor.is-invalid, .form-error:not(:empty)').first();
                     if (firstError.length) {
                         $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 300);
                     } else {
                          Swal.fire('Validation Error', 'Please check the form for required fields (Name, Image, Category).', 'error');
                     }
                }
                 $form.addClass('was-validated'); // Trigger Bootstrap styles
            });

            // Clear validation feedback
            $form.find('input, select, textarea').on('input change', function() { $(this).removeClass('is-invalid'); });
            $('#categories').on('change', validateSelect2);
            // Summernote cleared via its onChange

            // --- REPEATER: Department Info ---
            let deptIndex = {{ is_array(old('department_info')) ? count(old('department_info')) : 0 }};
            function addDeptRow(index, data = {}) { // Added data param
                 let newRow = `
                <div class="row align-items-center repeater-row dept-row" data-index="${index}">
                    <div class="col-md-4 mb-3">
                        <label class="form-label visually-hidden">Designation</label>
                        <select class="form-select" name="department_info[${index}][designation_id]" >
                            <option value="">Select Designation...</option>
                            @foreach($designations as $designation)
                                <option value="{{ $designation->id }}">{{ $designation->name }}</option>
                            @endforeach
                        </select>
                         <div class="invalid-feedback">Required.</div>
                    </div>
                    <div class="col-md-4 mb-3">
                         <label class="form-label visually-hidden">Department</label>
                        <select class="form-select" name="department_info[${index}][department_id]" >
                            <option value="">Select Department...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                         <div class="invalid-feedback">Required.</div>
                    </div>
                    <div class="col-md-3 mb-3">
                         <label class="form-label visually-hidden">Additional Text</label>
                        <input type="text" class="form-control" name="department_info[${index}][additional_text]" placeholder="Additional Text (Optional)" value="${data.additional_text || ''}">
                    </div>
                    <div class="col-md-1 mb-3 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger removeDeptRow" title="Remove Row"><i class="fa fa-times"></i></button>
                    </div>
                </div>`;
                $('#deptRepeaterContainer').append(newRow);
                if (typeof feather !== 'undefined') { feather.replace(); }
                validateDepartmentRows();
            }
            $("#addDeptRow").click(function() { addDeptRow(deptIndex); deptIndex++; });
            $(document).on('click', '.removeDeptRow', function() { $(this).closest('.dept-row').remove(); validateDepartmentRows(); });
            // Add initial row only if container is empty AND there's no old input
             if (deptIndex === 0 && $('#deptRepeaterContainer').children().length === 0) {
                 addDeptRow(0); // Add one empty row to start
                 deptIndex++;
             }

            // --- REPEATER: Expert Areas ---
            let expertAreaIndex = {{ is_array(old('expert_areas')) ? count(old('expert_areas')) : 0 }};
             function addExpertAreaRow(index, value = '') {
                 let newRow = `
                 <div class="row align-items-center repeater-row expert-area-row" data-index="${index}">
                     <div class="col-md-11 mb-3">
                         <input type="text" class="form-control" name="expert_areas[${index}]" placeholder="Enter expert area ${index + 1} (e.g., Financial Modeling)" value="${value}">
                     </div>
                     <div class="col-md-1 mb-3 text-end">
                         <button type="button" class="btn btn-sm btn-outline-danger removeExpertAreaRow" title="Remove Area"><i class="fa fa-times"></i></button>
                     </div>
                 </div>`;
                 $('#expertAreaRepeaterContainer').append(newRow);
                  if (typeof feather !== 'undefined') { feather.replace(); }
             }
            $("#addExpertAreaRow").click(function() { addExpertAreaRow(expertAreaIndex); expertAreaIndex++; });
            $(document).on('click', '.removeExpertAreaRow', function() { $(this).closest('.expert-area-row').remove(); });
            // Don't add initial empty row unless specifically needed

            // --- REPEATER: Social Links ---
            let socialIndex = {{ is_array(old('social_links')) ? count(old('social_links')) : 0 }};
             function addSocialRow(index, title = '', link = '') {
                 let newRow = `
                <div class="row align-items-center repeater-row social-row" data-index="${index}">
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" name="social_links[${index}][title]" placeholder="Title (e.g., LinkedIn)" value="${title}">
                    </div>
                    <div class="col-md-7 mb-3">
                        <input type="url" class="form-control" name="social_links[${index}][link]" placeholder="Full URL (https://...)" value="${link}">
                    </div>
                    <div class="col-md-1 mb-3 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger removeSocialRow" title="Remove Link"><i class="fa fa-times"></i></button>
                    </div>
                </div>`;
                $('#socialRepeaterContainer').append(newRow);
                 if (typeof feather !== 'undefined') { feather.replace(); }
             }
            $("#addSocialRow").click(function() { addSocialRow(socialIndex); socialIndex++; });
            $(document).on('click', '.removeSocialRow', function() { $(this).closest('.social-row').remove(); });
             // Don't add initial empty row

             // Re-initialize Feather Icons
             if (typeof feather !== 'undefined') { feather.replace(); }

        }); // End $(document).ready
    </script>
@endsection