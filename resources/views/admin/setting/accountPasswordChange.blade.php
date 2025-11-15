<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="{{ $ins_name }}">
	<meta name="robots" content="">
    <meta name="keywords" content="{{ $keyword }}">
	<meta name="description" content="{{ $description }}">
	<meta property="og:title" content="{{ $ins_name }}">
	<meta property="og:description" content="{{ $description }}">
	<meta property="og:image" content="{{ asset('/') }}{{ $logo }}">
    <title>Change Password</title>
    <link rel="shortcut icon" href="{{ asset('/') }}{{ $front_icon_name }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/') }}public/admin/assets/css/auth-style.css">
</head>
<body>
    <div class="wrapper">
        <div class="brand-section" style="background: linear-gradient(rgba(0, 91, 61, 0.75), rgba(0, 91, 61, 0.75)), url('{{ asset('public/image.png') }}');">
            <img src="{{ asset('/') }}{{ $front_logo_name }}" alt="IIFC Logo" class="logo">
            <h2>Infrastructure Investment Facilitation Company</h2>
            <p class="mt-2">Securing your account is our priority.</p>
        </div>
        <div class="form-section">
            <div class="form-container">
                <div class="text-center text-md-start mb-4">
                    <h3 class="fw-bold">Set a New Password</h3>
                    <p class="text-muted">Your new password must be different from previous passwords.</p>
                </div>
                
                <form class="theme-form login-form" action="{{route('postPasswordChange')}}" method="post" enctype="multipart/form-data" id="form" data-parsley-validate="">
                    @csrf
                    <input type="hidden" value="{{ $email }}" name="mainEmail" />
                    @include('flash_message') 
                    
                    <div class="mb-3">
                        <label for="newPassword" class="form-label" style="font-weight: 500;">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" name="password" id="newPassword" placeholder="New Password" required minlength="8">
                            <span class="input-group-text" id="toggleNewPassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash-fill"></i>
                            </span>
                        </div>
                        <div class="form-text text-muted" style="font-size: 0.875em; margin-top: 5px;">
                            Password must be at least 8 characters long.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label" style="font-weight: 500;">Confirm New Password</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text"><i class="bi bi-check-circle-fill"></i></span>
                            <input type="password" class="form-control" name="password_confirmation" id="confirmPassword" placeholder="Confirm New Password" required minlength="8">
                            <span class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash-fill"></i>
                            </span>
                            <div class="invalid-feedback" id="passwordMatchError" style="width: 100%;">
                                Passwords do not match.
                            </div>
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // Helper function to setup toggler
        function setupPasswordToggler(toggleId, passwordId) {
            const toggleButton = document.getElementById(toggleId);
            const passwordInput = document.getElementById(passwordId);
            
            if (!toggleButton || !passwordInput) return;

            const icon = toggleButton.querySelector('i');

            toggleButton.addEventListener('click', function () {
                // Toggle the type
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle the icon
                if (type === 'password') {
                    icon.classList.remove('bi-eye-fill');
                    icon.classList.add('bi-eye-slash-fill');
                } else {
                    icon.classList.remove('bi-eye-slash-fill');
                    icon.classList.add('bi-eye-fill');
                }
            });
        }

        // Setup togglers for both password fields
        setupPasswordToggler('toggleNewPassword', 'newPassword');
        setupPasswordToggler('toggleConfirmPassword', 'confirmPassword');


        // --- New Validation Code ---
        const form = document.getElementById('form');
        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        // Function to check if passwords match
        function validatePasswords() {
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid'); // Add Bootstrap error class
                return false; // Indicate failure
            } else {
                confirmPassword.classList.remove('is-invalid'); // Remove error class
                // Optionally add 'is-valid' for green check
                // confirmPassword.classList.add('is-valid'); 
                return true; // Indicate success
            }
        }

        // 1. Check when the form is submitted
        form.addEventListener('submit', function (event) {
            if (!validatePasswords()) {
                event.preventDefault(); // Stop form submission if passwords don't match
            }
        });

        // 2. Check in real-time as the user types
        if (confirmPassword) {
            confirmPassword.addEventListener('keyup', validatePasswords);
        }
        if (newPassword) {
            // Also re-validate if the *first* password changes
            newPassword.addEventListener('keyup', function() {
                // Only validate if the confirm password field has text in it
                if (confirmPassword.value.length > 0) {
                    validatePasswords();
                }
            }); 
        }

    });
    </script>
</body>
</html>