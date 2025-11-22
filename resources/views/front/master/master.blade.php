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
   
    <link rel="shortcut icon" href="{{ asset('/') }}{{ $icon }}">
    <title>@yield('title')</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <!-- Swiper.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('/') }}public/front/style.css">
    @yield('css')
</head>
<body>

    <!-- New Header Section -->
  @include('front.include.header')

    <!-- Overlay -->
    <div class="overlay" id="overlay"></div>


  @yield('body')

   @include('front.include.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper.js JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script src="{{ asset('/') }}public/front/js/script.js"></script>
    <!-- Custom JS for Sidebars & Swiper -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- NEW HEADER SCROLL & PADDING SCRIPT ---

            const headerMain = document.querySelector('.header-main');
            const headerTop = document.querySelector('.header-top');
            
            // 1. Set initial body padding to prevent content from jumping
            const setBodyPadding = () => {
                const headerHeight = headerMain.offsetHeight;
                document.body.style.paddingTop = headerHeight + 'px';
            };
            
            // 2. Set CSS variable for the top bar height (for the scroll transform)
            const setTopBarHeight = () => {
                 // Check if headerTop exists (it's hidden on mobile)
                 if (headerTop) {
                    const headerTopHeight = headerTop.offsetHeight;
                    document.documentElement.style.setProperty('--header-top-height', headerTopHeight + 'px');
                 } else {
                    document.documentElement.style.setProperty('--header-top-height', '0px');
                 }
            };

            setBodyPadding();
            setTopBarHeight();
            
            // Recalculate on window resize
            window.addEventListener('resize', () => {
                setBodyPadding();
                setTopBarHeight();
            });

            // 3. Handle scroll class
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) { // Add scrolled class after 50px of scroll
                    document.body.classList.add('header-scrolled');
                } else {
                    document.body.classList.remove('header-scrolled');
                }
            });

            // --- END OF NEW HEADER SCRIPT ---

            // --- UPDATED SIDEBAR SCRIPT ---
            const navSidebar = document.getElementById('nav-sidebar');
            const cartSidebar = document.getElementById('cart-sidebar');
            const loginSidebar = document.getElementById('login-sidebar'); // NEW
            
            const navToggleBtn = document.getElementById('nav-toggle-btn');
            const mobileCartBtn = document.getElementById('mobile-cart-btn');
            const desktopCartBtn = document.getElementById('desktop-cart-btn');
            const mobileLoginBtn = document.getElementById('mobile-login-btn'); // NEW
            const desktopLoginBtn = document.getElementById('desktop-login-btn'); // NEW

            const closeNavBtn = document.getElementById('close-nav-sidebar');
            const closeCartBtn = document.getElementById('close-cart-sidebar');
            const closeLoginBtn = document.getElementById('close-login-sidebar'); // NEW
            
            const overlay = document.getElementById('overlay');
            const body = document.body;
            
            const checkoutBtn = document.getElementById('checkout-btn');
            const checkoutModalEl = document.getElementById('checkoutModal');
            const checkoutModal = new bootstrap.Modal(checkoutModalEl);

            function openSidebar(sidebar) {
                if (sidebar) {
                    sidebar.classList.add('open');
                    overlay.classList.add('show');
                    body.classList.add('sidebar-open');
                }
            }

            function closeSidebar(sidebar) {
                 if (sidebar) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('show');
                    body.classList.remove('sidebar-open');
                 }
            }
            
            function openCartSidebar(position) {
                if (position === 'left') {
                    cartSidebar.classList.remove('right');
                    cartSidebar.classList.add('left');
                } else {
                    cartSidebar.classList.remove('left');
                    cartSidebar.classList.add('right');
                }
                openSidebar(cartSidebar);
            }

            // --- NEW: Mobile Search Popup Script ---
            const mobileSearchBtn = document.getElementById('mobile-search-btn');
            const mobileSearchPopup = document.getElementById('mobile-search-popup');
            
            if (mobileSearchBtn) {
                mobileSearchBtn.addEventListener('click', () => {
                    mobileSearchPopup.classList.toggle('show');
                });
            }

            // Check if elements exist before adding listeners
            if (navToggleBtn) {
                navToggleBtn.addEventListener('click', () => openSidebar(navSidebar));
            }
            if (mobileCartBtn) {
                mobileCartBtn.addEventListener('click', () => openCartSidebar('left'));
            }
            if (desktopCartBtn) {
                desktopCartBtn.addEventListener('click', () => openCartSidebar('right'));
            }
            // NEW Login Listeners
            if (mobileLoginBtn) {
                mobileLoginBtn.addEventListener('click', () => openSidebar(loginSidebar));
            }
            if (desktopLoginBtn) {
                desktopLoginBtn.addEventListener('click', () => openSidebar(loginSidebar));
            }

            if (closeNavBtn) {
                closeNavBtn.addEventListener('click', () => closeSidebar(navSidebar));
            }
            if (closeCartBtn) {
                closeCartBtn.addEventListener('click', () => closeSidebar(cartSidebar));
            }
            if (closeLoginBtn) { // NEW
                closeLoginBtn.addEventListener('click', () => closeSidebar(loginSidebar));
            }
            
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', () => {
                    closeSidebar(cartSidebar);
                    // Use a short delay to ensure the sidebar has started closing before the modal opens
                    setTimeout(() => {
                        checkoutModal.show();
                    }, 300);
                });
            }

            if (overlay) {
                overlay.addEventListener('click', () => {
                    if (navSidebar) closeSidebar(navSidebar);
                    if (cartSidebar) closeSidebar(cartSidebar);
                    if (loginSidebar) closeSidebar(loginSidebar); // NEW
                });
            }


            // Testimonial Swiper (Original Script)
            var swiper = new Swiper('.testimonial-swiper', {
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    // when window width is >= 320px
                    320: {
                        slidesPerView: 1,
                        spaceBetween: 20
                    },
                    // when window width is >= 768px
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 30
                    },
                    // when window width is >= 992px
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 40
                    }
                }
            });
        });
    </script>
    @yield('script')
</body>
</html>

