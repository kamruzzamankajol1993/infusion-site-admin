@extends('admin.master.master')

@section('title')
Create User | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* New styles for professional image uploader */
    .image-upload-wrapper {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f9f9f9;
        transition: background-color 0.3s, border-color 0.3s;
    }
    .image-upload-wrapper:hover {
        background-color: #f1f1f1;
        border-color: #aaa;
    }
    .image-upload-wrapper .upload-text {
        color: #777;
    }
    .image-upload-wrapper .upload-text i {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 5px;
    }
    .image-upload-wrapper .upload-text span {
        font-size: 0.9rem;
    }
    .image-upload-wrapper .img-preview {
        max-width: 100%;
        max-height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 5px;
        display: none;
        background-color: #fff;
    }
    .profile-wrapper {
        width: 150px;
        height: 150px;
        border-radius: 50%; /* Circular for profile */
        margin: 0 auto;
    }
    .signature-wrapper {
        width: 100%;
        height: 100px;
        border-radius: 8px; /* Rectangular for signature */
    }
    /* Hide the default input */
    .image-upload-wrapper input[type="file"] {
        display: none;
    }
</style>
@endsection

@section('body')
  <div class="container-fluid px-4 py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
    

        <div class="card">
            <div class="card-body">
                @include('flash_message')

                <form method="post" action="{{ route('users.store') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">User Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Name<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                                        <div class="invalid-feedback">Please provide a name.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Department Name<span class="text-danger">*</span></label>
                                        <select name="department_id" class="form-control" required>
                                            <option value="">-- Select Department --</option>
                                            @foreach($departmentList as $departmentInfos)
                                            <option value="{{ $departmentInfos->id }}">{{ $departmentInfos->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please select a department.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Designation Name<span class="text-danger">*</span></label>
                                        <select name="designation_id" class="form-control" required>
                                            <option value="">-- Select Designation --</option>
                                            @foreach($designationList as $branchInfos)
                                            <option value="{{ $branchInfos->id }}">{{ $branchInfos->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please select a designation.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Phone Number<span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone" placeholder="01XXXXXXXXX" required pattern="01[0-9]{9}" maxlength="11">
                                        <div class="invalid-feedback">Please provide a valid 11-digit phone number.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email<span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" placeholder="example@domain.com" required>
                                        <div class="invalid-feedback">Please provide a valid email address.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address<span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="address" placeholder="User's full address" required></textarea>
                                        <div class="invalid-feedback">Please provide an address.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Security & Images</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Role<span class="text-danger">*</span></label>
                                        <select name="roles[]" class="form-control" required>
                                            <option value="">-- Select Role --</option>
                                            @foreach ($roles as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Please select a role.</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Password<span class="text-danger">*</span></label>
                                        <input type="password" name="password" id="password" placeholder="Password" class="form-control" required maxlength="8">
                                        <small class="form-text text-muted">Password must be 8 characters or less.</small>
                                        <div class="invalid-feedback" id="password-feedback">Password is required (max 8 characters).</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password<span class="text-danger">*</span></label>
                                        <input type="password" name="confirm-password" id="confirm_password" placeholder="Confirm Password" class="form-control" required maxlength="8">
                                        <div class="invalid-feedback" id="confirm-password-feedback">Please confirm your password.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label d-block text-center">Profile Image<span class="text-danger">*</span></label>
                                        <label for="profile_image" class="image-upload-wrapper profile-wrapper">
                                            <div class="upload-text">
                                                <i class="fa fa-upload"></i>
                                                <span>Click to upload (300x300)</span>
                                            </div>
                                            <img id="profile_preview" src="#" alt="Profile Preview" class="img-preview"/>
                                            <input type="file" class="form-control" name="image" id="profile_image" accept="image/jpeg,image/png,image/jpg" required>
                                        </label>
                                        <small class="form-text text-muted d-block text-center">Required. JPG or PNG. Max 300KB.</small>
                                        <div class="invalid-feedback text-center" id="profile-image-feedback">Please upload a valid profile image.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Signature</label>
                                        <label for="signature_image" class="image-upload-wrapper signature-wrapper">
                                            <div class="upload-text">
                                                <i class="fa fa-upload"></i>
                                                <span>Click to upload (300x80)</span>
                                            </div>
                                            <img id="signature_preview" src="#" alt="Signature Preview" class="img-preview"/>
                                            <input type="file" class="form-control" name="signature" id="signature_image" accept="image/png">
                                        </label>
                                        <small class="form-text text-muted">Optional. PNG only. Max 300KB.</small>
                                        <div class="invalid-feedback" id="signature-image-feedback">Please upload a valid signature image.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-plus me-1"></i> Add User Info</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
<script>
    (function () {
        'use strict'

        var forms = document.querySelectorAll('.needs-validation')
        var password = document.getElementById('password');
        var confirm_password = document.getElementById('confirm_password');
        var profile_image = document.getElementById('profile_image');
        var signature_image = document.getElementById('signature_image');

        // --- Password Match Validation ---
        function validatePasswordMatch() {
            if (password.value !== confirm_password.value) {
                confirm_password.setCustomValidity("Passwords do not match.");
                document.getElementById('confirm-password-feedback').textContent = "Passwords do not match.";
            } else {
                confirm_password.setCustomValidity('');
                document.getElementById('confirm-password-feedback').textContent = "Please confirm your password.";
            }
        }
        password.onchange = validatePasswordMatch;
        confirm_password.onkeyup = validatePasswordMatch;

        // --- NEW Image Validation Function (with SweetAlert) ---
        function validateImage(input, feedbackEl, previewEl, reqWidth, reqHeight, maxSizeKB, originalSrc) {
            var file = input.files[0];
            var uploadTextEl = input.closest('.image-upload-wrapper').querySelector('.upload-text');

            // Helper function to show the alert
            function showAlert(title, text) {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: text
                });
            }

            // Helper function to reset the input/UI
            function resetInput() {
                input.value = ''; // Clear the selected file
                input.setCustomValidity('Invalid file.'); // Mark as invalid
                
                if (originalSrc) {
                    // This is EDIT form and we have an original image
                    previewEl.src = originalSrc;
                    previewEl.style.display = 'block';
                    if (uploadTextEl) uploadTextEl.style.display = 'none';
                } else {
                    // This is CREATE form or an EDIT form with no original image
                    previewEl.src = '#';
                    previewEl.style.display = 'none';
                    if (uploadTextEl) uploadTextEl.style.display = 'flex';
                }
            }

            if (!file) {
                // No file selected. Reset to original state.
                input.setCustomValidity(''); // Clear validation
                feedbackEl.textContent = ''; // Clear feedback
                if (input.hasAttribute('required')) {
                    feedbackEl.textContent = 'This field is required.';
                }
                resetInput(); // Revert to original (or blank)
                return;
            }

            // --- 1. Validate Size FIRST ---
            if (file.size > maxSizeKB * 1024) {
                var errorTitle = 'File Too Large';
                var errorText = 'The file must be ' + maxSizeKB + 'KB or less. Your file is ' + (file.size / 1024).toFixed(0) + 'KB.';
                showAlert(errorTitle, errorText);
                feedbackEl.textContent = errorText; // Also show in standard feedback
                resetInput();
                return; // Stop processing
            }

            // --- 2. Validate Dimensions (if size is ok) ---
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = new Image();
                img.onload = function () {
                    if (this.width !== reqWidth || this.height !== reqHeight) {
                        // Dimension check FAILED
                        var errorTitle = 'Incorrect Dimensions';
                        var errorText = 'Image must be exactly ' + reqWidth + 'x' + reqHeight + ' pixels. Yours is ' + this.width + 'x' + this.height + ' pixels.';
                        showAlert(errorTitle, errorText);
                        feedbackEl.textContent = errorText; // Also show in standard feedback
                        resetInput();
                    } else {
                        // SUCCESS!
                        input.setCustomValidity('');
                        feedbackEl.textContent = 'Looks good!';
                        previewEl.src = e.target.result;
                        previewEl.style.display = 'block';
                        if (uploadTextEl) uploadTextEl.style.display = 'none';
                    }
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }

        // --- Attach Image Validators ---
        profile_image.onchange = function() {
            // Pass '' for originalSrc since this is the create form
            validateImage(profile_image, document.getElementById('profile-image-feedback'), document.getElementById('profile_preview'), 300, 300, 300, '');
        };
        signature_image.onchange = function() {
            // Pass '' for originalSrc since this is the create form
            validateImage(signature_image, document.getElementById('signature-image-feedback'), document.getElementById('signature_preview'), 300, 80, 300, '');
        };


        // --- Bootstrap Form Submission ---
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    // Manually trigger password match check on submit
                    validatePasswordMatch();

                    // Manually trigger image checks on submit
                    if (profile_image.hasAttribute('required') && !profile_image.files[0]) {
                        profile_image.setCustomValidity('Please upload a profile image.');
                        document.getElementById('profile-image-feedback').textContent = 'Please upload a profile image.';
                    } else if (profile_image.files[0]) {
                         validateImage(profile_image, document.getElementById('profile-image-feedback'), document.getElementById('profile_preview'), 300, 300, 300, '');
                    }

                    if (signature_image.files[0]) {
                        validateImage(signature_image, document.getElementById('signature-image-feedback'), document.getElementById('signature_preview'), 300, 80, 300, '');
                    }

                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
@endsection