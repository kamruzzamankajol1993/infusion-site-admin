<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="author" content="{{ $ins_name }}">
	<meta name="robots" content="">
    <meta name="keywords" content="{{ $keyword }}">
	<meta name="description" content="{{ $description }}">
	<meta property="og:title" content="{{ $ins_name }}">
	<meta property="og:description" content="{{ $description }}">
	<meta property="og:image" content="{{ asset('/') }}{{ $front_logo_name }}">
    <title>IIFC Admin Login</title>
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
        <div class="brand-section" style="background: linear-gradient(rgba(0, 56, 91, 0.75), rgba(0, 44, 91, 0.75)), url('{{ asset('public/image.png') }}');">
            <img src="{{ asset('/') }}{{ $front_logo_name }}" alt="IIFC Logo" class="logo">
            <h2>Admin Panel Access</h2>
            <p class="mt-2">Welcome! Please sign in to continue.</p>
        </div>
        <div class="form-section">
            <div class="form-container">
                <div class="text-center text-md-start mb-4">
                    <h3 class="fw-bold">Sign In</h3>
                    <p class="text-muted">Enter your credentials to access your account.</p>
                </div>
                <form method="POST" action="{{ route('login') }}" >

                    @csrf 
                    <div class="form-group mb-3">
                        <span class="form-icon"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email address" required>
                    </div>
                   <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required >
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye-slash-fill"></i>
                            </span>
                        </div>
                        <div class="form-text text-muted" style="font-size: 0.875em; margin-top: 5px;">
                            Password must be at least 8 characters long.
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="rememberMe">
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <a href="{{ route('showLinkRequestForm') }}" class="text-decoration-none" style="color: var(--primary-color);">Forgot password?</a>
                    </div>
                    <div class="d-grid">
                         <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
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

        // Setup toggler for the login password field
        setupPasswordToggler('togglePassword', 'password');
    </script>
</body>
</html>