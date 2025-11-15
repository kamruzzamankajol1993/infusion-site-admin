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
    <!-- Title -->
    <title>Forgot Password</title>
    <!-- Favicon -->
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
            <h2>Account Recovery</h2>
            <p class="mt-2">Don't worry, we'll help you get back in.</p>
        </div>
        <div class="form-section">
            <div class="form-container">
                <div class="text-center text-md-start mb-4">
                    <h3 class="fw-bold">Forgot Password?</h3>
                    <p class="text-muted">Enter your email and we'll send a link to reset your password.</p>
                </div>
                 <form id="form" class="theme-form login-form" action="{{route('checkMailPost')}}" method="post">
                    @csrf
                    @include('flash_message')  
                    <div class="form-group mb-4">
                        <span class="form-icon"><i class="bi bi-envelope-fill"></i></span>
                       <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                   </div>
                   <div class="d-grid">
                       <button type="submit" id="finalValue" class="btn btn-primary">Send Reset Link</button>
                   </div>
                </form>
                <div class="text-center mt-4">
                   <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--primary-color);">
                       <i class="bi bi-arrow-left me-1"></i> Back to Login
                   </a>
               </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script>
        $(document).ready(function () {
            $("#email").keyup(function () {
                var mainId = $(this).val();
                //alert(mainId);
    
                $.ajax({
            url: "{{ route('checkMailForPassword') }}",
            method: 'GET',
            data: {mainId:mainId},
            success: function(data) {
    
                //alert(data);
    
             if(data == 0){
    
                $('#finalValue').attr('disabled','disabled');
    
             }else{
                $('#finalValue').removeAttr('disabled');
    
             }
            }
        });
            });
        });
    </script>
</body>
</html>




