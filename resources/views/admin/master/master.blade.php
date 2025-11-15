<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="{{ $ins_name }}">
	<meta name="robots" content="">
    <meta name="keywords" content="{{ $keyword }}">
	<meta name="description" content="{{ $description }}">
	<meta property="og:title" content="{{ $ins_name }}">
	<meta property="og:description" content="{{ $description }}">
	<meta property="og:image" content="{{ asset('/') }}{{ $logo }}">
    <title>@yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('/') }}{{ $icon }}">

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <link rel="stylesheet" href="{{ asset('/') }}public/admin/assets/css/style.css?v=3">
    <link rel="stylesheet" href="{{ asset('/') }}public/admin/assets/css/custome.css?v=3">
     <link rel="stylesheet" href="{{asset('/')}}public/online/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('/') }}public/parsely.css"/>
    
    <link rel="stylesheet" href="{{asset('/')}}public/online/alertify.min.css"/>
    {{-- jQuery UI CSS for Datepicker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" xintegrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

    @yield('css')
</head>
<body>
    {{-- NEW: Added an overlay for the mobile sidebar --}}
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="d-flex" id="wrapper">
        @include('admin.include.sidebar')
        <div id="page-content-wrapper">
            @include('admin.include.header')

           @yield('body')
        </div>
    </div>

  
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" xintegrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
 
 <script>
        // --- MODIFIED: Enhanced Sidebar Toggle Logic ---
        const el = document.getElementById("wrapper");
        const toggleButton = document.getElementById("menu-toggle");
        const closeButton = document.getElementById("sidebar-close-button");
        const overlay = document.getElementById("sidebar-overlay");

        function toggleSidebar() {
            el.classList.toggle("toggled");
            // Only toggle the overlay on mobile screens
            if (window.innerWidth < 768) {
                overlay.classList.toggle("active");
            }
        }

        if (toggleButton) {
            toggleButton.onclick = toggleSidebar;
        }

        if (closeButton) {
            closeButton.onclick = toggleSidebar;
        }
        
        if (overlay) {
            overlay.onclick = toggleSidebar;
        }
        // --- END MODIFICATION ---

        // Set today's date
        const dateElement = document.getElementById('currentDate');
        // --- FIX: Corrected 'new new Date()' to 'new Date()' ---
        const today = new Date(); 
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateElement.textContent = today.toLocaleDateString('en-US', options);

   
    </script>
   
    <script src="{{ asset('/')}}public/parsely1.js"></script>
   

<script src="{{asset('/')}}public/online/toastr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    function activeTag(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to activate this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, activate it!'
        }).then((result) => {
            if (result.isConfirmed) {
                event.preventDefault();
                document.getElementById('adelete-form-' + id).submit();
            }
        });
    }

    function inactiveTag(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to deactivate this?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, deactivate it!'
        }).then((result) => {
            if (result.isConfirmed) {
                event.preventDefault();
                document.getElementById('adelete-form-' - id).submit();
            }
        });
    }

    function deleteTag(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                event.preventDefault();
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
{!! Toastr::message() !!}
<script>
    @if($errors->any())
        @foreach($errors->all() as $error)
              toastr.error('{{ $error }}','Error',{
                  closeButton:true,
                  progressBar:true,
               });
        {{-- --- FIX: Corrected @endDforeach to @endforeach --- --}}
        @endforeach
    @endif
</script>
<script>
    setTimeout(function(){
  $('#divID').remove();
}, 3000);
</script>

<script src="{{asset('/')}}public/online/alertify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


<script>
    $(document).ready(function() {
        // Find the scrollable link container (the list-group itself)
        var scrollableList = $('#sidebar-accordion'); 
        
        // Find the active link within it
        var activeLink = scrollableList.find('.list-group-item.active');

        // Check if an active link exists
        if (activeLink.length > 0) {
            // Scroll the active link into view on page load
            activeLink[0].scrollIntoView({
                behavior: 'auto', 
                block: 'center'
            });
        }

        // --- NEW: Auto-scroll for Settings menu ---
        var $settingsCollapse = $('#settings-collapse');
        var $settingsLink = $('a[href="#settings-collapse"]');

        if ($settingsCollapse.length && $settingsLink.length) {
            // Listen for when the collapse element is finished opening
            $settingsCollapse.on('shown.bs.collapse', function () {
                // Scroll the 'Settings' link itself into view
                $settingsLink[0].scrollIntoView({
                    behavior: 'smooth', // Nice smooth scroll
                    block: 'center'      // Center it in the sidebar
                });
            });
        }
        // --- END NEW SCRIPT ---
    });
</script>

    @yield('script')
</body>
</html>