@extends('admin.master.master')

@section('title')
Edit Officer | {{ $ins_name ?? 'Admin Panel' }}
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
            {{-- REMOVED admin. prefix --}}
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('officer.index') }}">Officers</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit: {{ $officer->name }}</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Edit Officer</h5>
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
            {{-- REMOVED admin. prefix --}}
            <form action="{{ route('officer.update', $officer->id) }}" method="POST" enctype="multipart/form-data" id="editOfficerForm" novalidate>
                @csrf
                @method('PUT') {{-- Method spoofing for update --}}

                {{-- Basic Information Section --}}
                <h5 class="mt-2 mb-3 text-primary border-bottom pb-2">Basic Information</h5>
                <div class="row">
                    {{-- Left Column --}}
                    <div class="col-md-8">
                        <div class="row">
                            {{-- Name (Required) --}}
                            <div class="col-md-4 mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $officer->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- Status (Optional, defaults Active) --}}
                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                    <option value="1" {{ old('status', $officer->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $officer->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Show Profile Button --}}
                             <div class="col-md-4 mb-3"> {{-- UPDATED FIELD --}}
                                <label class="form-label d-block">Show Profile Btn <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input @error('show_profile_details_button') is-invalid @enderror" type="radio" name="show_profile_details_button" id="show_profile_yes" value="1" {{ old('show_profile_details_button', $officer->show_profile_details_button) == 1 ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="show_profile_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input @error('show_profile_details_button') is-invalid @enderror" type="radio" name="show_profile_details_button" id="show_profile_no" value="0" {{ old('show_profile_details_button', $officer->show_profile_details_button) == 0 ? 'checked' : '' }} required>
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
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $officer->email) }}" placeholder="example@domain.com">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                             {{-- Phone (Optional, 11 digits) --}}
                            <div class="col-md-4 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $officer->phone) }}" placeholder="01xxxxxxxxx" pattern="\d{11}" title="Phone number must be 11 digits">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                              
                            </div>
                            {{-- ADD THIS NEW BLOCK --}}
                            <div class="col-md-4 mb-3">
                                <label for="mobile_number" class="form-label">Mobile Number (Optional)</label>
                                <input type="text" class="form-control @error('mobile_number') is-invalid @enderror" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $officer->mobile_number) }}" placeholder="e.g., 01xxxxxxxxx" pattern="\d{11}" title="Mobile number must be 11 digits">
                                @error('mobile_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- END OF NEW BLOCK --}}
                            {{-- Start Date (Optional) --}}
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="text" class="form-control datepicker @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', $officer->start_date) }}" placeholder="YYYY-MM-DD" autocomplete="off">
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- End Date (Optional) --}}
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="text" class="form-control datepicker @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', $officer->end_date) }}" placeholder="YYYY-MM-DD (Leave blank if current)" autocomplete="off">
                                @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                             {{-- Description (Optional) --}}
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">Description / Bio</label>
                                <textarea class="form-control summernote @error('description') is-invalid @enderror" id="summernote" name="description">{{ old('description', $officer->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div id="summernote-error" class="form-error"></div> {{-- Client-side error msg --}}
                                <small class="form-text text-muted">To show text in list form, select text and use list buttons.</small>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Image (Optional on Update) --}}
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Profile Image (Leave blank to keep)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp"> {{-- Removed 'required' --}}
                            @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="form-text text-muted">Size: 313x374 px, Max: 1024KB</small>
                        </div>
                        {{-- Image Preview Area --}}
                        <div class="image-preview-box">
                             {{-- Show current image if exists --}}
                            @if($officer->image)
                                <img id="imagePreview" src="{{ asset($officer->image) }}" alt="Current Image">
                                <span class="placeholder-text" style="display:none;">Image Preview<br>(313 x 374)</span>
                            @else
                                <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                                <span class="placeholder-text">Image Preview<br>(313 x 374)</span>
                            @endif
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
                            <option value="{{ $category->id }}" {{ (is_array(old('categories', $selectedCategories)) && in_array($category->id, old('categories', $selectedCategories))) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                     @error('categories')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                     {{-- Populate with existing or old data --}}
                     @php $departmentInfos = old('department_info', $officer->departmentInfos); @endphp
                     @if(!empty($departmentInfos))
                        @foreach($departmentInfos as $index => $info)
                        <div class="row align-items-center repeater-row dept-row" data-index="{{ $index }}">
                            <div class="col-md-4 mb-3">
                                <label class="form-label visually-hidden">Designation</label> {{-- Hide label for existing --}}
                                <select class="form-select @error('department_info.'.$index.'.designation_id') is-invalid @enderror" name="department_info[{{ $index }}][designation_id]" >
                                    <option value="">Select Designation...</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->id }}" {{ ($info['designation_id'] ?? ($info->designation_id ?? '')) == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_info.'.$index.'.designation_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                 <label class="form-label visually-hidden">Department</label>
                                <select class="form-select @error('department_info.'.$index.'.department_id') is-invalid @enderror" name="department_info[{{ $index }}][department_id]" >
                                    <option value="">Select Department...</option>
                                    @foreach($departments as $department)
                                         <option value="{{ $department->id }}" {{ ($info['department_id'] ?? ($info->department_id ?? '')) == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_info.'.$index.'.department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                 <label class="form-label visually-hidden">Additional Text</label>
                                <input type="text" class="form-control @error('department_info.'.$index.'.additional_text') is-invalid @enderror" name="department_info[{{ $index }}][additional_text]" placeholder="Additional Text (Optional)" value="{{ $info['additional_text'] ?? ($info->additional_text ?? '') }}">
                                @error('department_info.'.$index.'.additional_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-1 mb-3 text-end">
                                <button type="button" class="btn btn-sm btn-outline-danger removeDeptRow" title="Remove Row"><i class="fa fa-times"></i></button>
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
                     {{-- Populate with existing or old data --}}
                     @php $expertAreas = old('expert_areas', $officer->expertAreas->pluck('expert_area')); @endphp
                     @if(!empty($expertAreas))
                        @foreach($expertAreas as $index => $area)
                            @if(!is_null($area)) {{-- Check if area is not null --}}
                                <div class="row align-items-center repeater-row expert-area-row" data-index="{{ $index }}">
                                    <div class="col-md-11 mb-3">
                                        <input type="text" class="form-control @error('expert_areas.'.$index) is-invalid @enderror" name="expert_areas[{{ $index }}]" placeholder="Enter expert area {{ $index + 1 }} (e.g., Financial Modeling)" value="{{ $area }}">
                                        @error('expert_areas.'.$index)<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-1 mb-3 text-end">
                                        <button type="button" class="btn btn-sm btn-outline-danger removeExpertAreaRow" title="Remove Area"><i class="fa fa-times"></i></button>
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
                     {{-- Populate with existing or old data --}}
                     @php $socialLinks = old('social_links', $officer->socialLinks); @endphp
                     @if(!empty($socialLinks))
                        @foreach($socialLinks as $index => $link)
                            @if(!empty($link['title']) || !empty($link['link']) || !empty($link->title) || !empty($link->link)) {{-- Check both array and object access --}}
                            <div class="row align-items-center repeater-row social-row" data-index="{{ $index }}">
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control @error('social_links.'.$index.'.title') is-invalid @enderror" name="social_links[{{ $index }}][title]" placeholder="Title (e.g., LinkedIn)" value="{{ $link['title'] ?? ($link->title ?? '') }}">
                                     @error('social_links.'.$index.'.title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-7 mb-3">
                                    <input type="url" class="form-control @error('social_links.'.$index.'.link') is-invalid @enderror" name="social_links[{{ $index }}][link]" placeholder="Full URL (https://...)" value="{{ $link['link'] ?? ($link->link ?? '') }}">
                                     @error('social_links.'.$index.'.link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-1 mb-3 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger removeSocialRow" title="Remove Link"><i class="fa fa-times"></i></button>
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
                        <i data-feather="save" class="me-1" style="width:18px;"></i> Update Officer
                    </button>
                    {{-- REMOVED admin. prefix --}}
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
                     callbacks: { onChange: function() { validateSummernote(); } }
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
                } else {
                     // Don't necessarily hide placeholder if there's an existing image
                     // Only reset if you want to explicitly clear it
                }
            });

            // --- Client-Side Validation Functions ---
             function validateSummernote() {
                $('#summernote-error').empty();
                $('#summernote').next('.note-editor').removeClass('is-invalid');
                return true; // No longer required client-side
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
                $('#dept-error').empty();
                // Rely on HTML5 required for individual fields if row exists
                return true;
            }

            // --- Form Submission Validation ---
            var $form = $('#editOfficerForm'); // Updated form ID
            $form.submit(function(e) {
                let isValid = true;
                isValid = validateSelect2() && isValid; // Only validate category client-side
                isValid = validateSummernote() && isValid;
                isValid = validateDepartmentRows() && isValid;

                // Check HTML5 validation
                if ($form[0].checkValidity() === false) { isValid = false; }

                if (!isValid) {
                    e.preventDefault(); e.stopPropagation();
                    const firstError = $form.find('.is-invalid, .select2-selection.is-invalid, .note-editor.is-invalid, .form-error:not(:empty)').first();
                     if (firstError.length) { $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 300); }
                     else { Swal.fire('Validation Error', 'Please check the form for required fields (Name, Category). Image optional on update.', 'error'); }
                }
                 $form.addClass('was-validated');
            });

            // Clear validation feedback
            $form.find('input, select, textarea').on('input change', function() { $(this).removeClass('is-invalid'); });
            $('#categories').on('change', validateSelect2);
            // Summernote cleared via its onChange

            // --- REPEATER: Department Info ---
            // Start index AFTER existing items
             let deptIndex = {{ $officer->departmentInfos->isNotEmpty() ? $officer->departmentInfos->keys()->last() + 1 : 0 }};
            function addDeptRow(index) {
                 let newRow = `
                <div class="row align-items-center repeater-row dept-row" data-index="${index}">
                    <div class="col-md-4 mb-3">
                        <select class="form-select" name="department_info[${index}][designation_id]" >
                            <option value="">Select Designation...</option>
                            @foreach($designations as $designation) <option value="{{ $designation->id }}">{{ $designation->name }}</option> @endforeach
                        </select>
                         <div class="invalid-feedback">Required.</div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <select class="form-select" name="department_info[${index}][department_id]" >
                            <option value="">Select Department...</option>
                            @foreach($departments as $department) <option value="{{ $department->id }}">{{ $department->name }}</option> @endforeach
                        </select>
                         <div class="invalid-feedback">Required.</div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="text" class="form-control" name="department_info[${index}][additional_text]" placeholder="Additional Text (Optional)">
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

            // --- REPEATER: Expert Areas ---
            let expertAreaIndex = {{ $officer->expertAreas->isNotEmpty() ? $officer->expertAreas->keys()->last() + 1 : 0 }};
             function addExpertAreaRow(index) {
                 let newRow = `
                 <div class="row align-items-center repeater-row expert-area-row" data-index="${index}">
                     <div class="col-md-11 mb-3">
                         <input type="text" class="form-control" name="expert_areas[${index}]" placeholder="Enter expert area ${index + 1} (e.g., Financial Modeling)">
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

            // --- REPEATER: Social Links ---
            let socialIndex = {{ $officer->socialLinks->isNotEmpty() ? $officer->socialLinks->keys()->last() + 1 : 0 }};
             function addSocialRow(index) {
                 let newRow = `
                <div class="row align-items-center repeater-row social-row" data-index="${index}">
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" name="social_links[${index}][title]" placeholder="Title (e.g., LinkedIn)">
                    </div>
                    <div class="col-md-7 mb-3">
                        <input type="url" class="form-control" name="social_links[${index}][link]" placeholder="Full URL (https://...)">
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

             // Re-initialize Feather Icons
             if (typeof feather !== 'undefined') { feather.replace(); }

        }); // End $(document).ready
    </script>
@endsection