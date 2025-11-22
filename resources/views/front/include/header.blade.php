  <header class="header-main">
        <!-- Top Bar (Desktop Only) -->
        <div class="header-top d-none d-lg-block">
            <div class="container">
                <div class="header-top-left">
                    <img src="https://api.iconify.design/mdi:phone.svg?color=%23343a40" alt="Phone" class="header-icon-svg">
                    <span>Need help? Call us: {{$ins_phone}}</span>
                </div>
                <div class="header-top-center">
                    <img src="https://api.iconify.design/mdi:gift.svg?color=%23343a40" alt="Gift" class="header-icon-svg">
                    <strong>{{$topLink}}</strong>
                </div>
                <div class="header-top-right">
                    <a href="mailto:info@optifusion.com">
                        <img src="https://api.iconify.design/mdi:email.svg?color=%23343a40" alt="Email" class="header-icon-svg">
                        <span>{{$ins_email}}</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Middle Bar (Logo, Search, Icons) -->
        <div class="header-middle">
            <div class="container">
                <!-- Desktop Middle Header -->
                <div class="d-none d-lg-flex justify-content-between align-items: center w-100">
    
    <a class="header-logo" href="index.html">
        <img src="{{ asset('/') }}{{ $rectangular_logo }}" alt="OPTIFUSION INC Logo">
    </a>
    
    <form class="header-search-redesigned mx-4">
        <div class="input-group">
            <select class="form-select header-search-select" aria-label="Category select">
               <option selected value="">All Categories</option>
                            @if(isset($categories))
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            @endif
            </select>
            <input type="text" class="form-control header-search-input" placeholder="Search for products..." aria-label="Search for products">
            <button class="btn header-search-btn" type="button">
                <img src="https://api.iconify.design/mdi:magnify.svg?color=%23ffffff" alt="Search" class="header-icon-svg-btn">
            </button>
        </div>
    </form>
    <div class="header-icons">
        <button id="desktop-login-btn" class="nav-icon" aria-label="User account">
            <img src="https://api.iconify.design/mdi:account-circle-outline.svg?color=%23ffffff" alt="User" class="header-icon-svg-main nav-icon-img">
        </button>
        <a href="wishlist.php" class="nav-icon" aria-label="Wishlist">
            <img src="https://api.iconify.design/mdi:heart-outline.svg?color=%23ffffff" alt="Wishlist" class="header-icon-svg-main nav-icon-img">
            <span class="badge bg-danger">0</span>
        </a>
        <button id="desktop-cart-btn" class="nav-icon" aria-label="Open cart">
            <img src="https://api.iconify.design/mdi:cart-outline.svg?color=%23ffffff" alt="Cart" class="header-icon-svg-main nav-icon-img">
            <span class="badge bg-danger">3</span>
        </button>
    </div>
</div>

                <!-- Mobile & Tablet Navigation -->
                <div class="mobile-nav-container d-lg-none justify-content-between align-items: center w-100">
                    
                    <!-- Logo on the left -->
                    <a class="navbar-brand m-0" href="#">
                        <img src="{{ asset('/') }}{{ $rectangular_logo }}" alt="OPTIFUSION INC Logo">
                    </a>

                    <!-- All icons on the right -->
                    <div class="mobile-nav-right-icons d-flex align-items: center">
                        <button class="nav-icon" id="mobile-search-btn" aria-label="Search">
                            <img src="https://api.iconify.design/mdi:magnify.svg?color=%23ffffff" alt="Search" class="header-icon-svg-main nav-icon-img">
                        </button>
                        <button class="nav-icon" id="mobile-login-btn" aria-label="User account">
                            <img src="https://api.iconify.design/mdi:account-circle-outline.svg?color=%23ffffff" alt="User" class="header-icon-svg-main nav-icon-img">
                        </button>
                        <button class="nav-icon" id="mobile-cart-btn" aria-label="Open cart">
                            <img src="https://api.iconify.design/mdi:cart-outline.svg?color=%23ffffff" alt="Cart" class="header-icon-svg-main nav-icon-img">
                            <span class="badge bg-danger">3</span>
                        </button>
                        <button class="nav-icon" id="nav-toggle-btn" aria-label="Open navigation">
                            <img src="https://api.iconify.design/mdi:menu.svg?color=%23ffffff" alt="Menu" class="header-icon-svg-main nav-icon-img">
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Bar (Desktop Only) -->
        <nav class="header-nav navbar navbar-expand-lg d-none d-lg-block">
            <div class="container">
                <div class="collapse navbar-collapse" id="desktopNavBar">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">
                                <img src="https://api.iconify.design/mdi:home-outline.svg?color=white" class="nav-link-icon" alt="">
                                <span>Home</span>
                            </a>
                        </li>
                       
                         <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://api.iconify.design/mdi:chevron-down.svg?color=white" class="nav-link-icon-dropdown-main" alt="">
                                <span>Our Services</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="digital.php">
                                        <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                        Digital Marketing
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="graphic.php">
                                        <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                        Graphics Design
                                    </a>
                                </li>

                                 <li>
                                    <a class="dropdown-item" href="websolution.php">
                                        <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                        Web Solutions
                                    </a>
                                </li>
                                <!-- NEW CHILD DROPDOWN -->
                                <li class="dropdown-submenu">
                                    <a class="dropdown-item dropdown-toggle" href="#">
                                        <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                        Social Media Management
                                        <img src="https://api.iconify.design/mdi:chevron-right.svg?color=%23343a40" class="nav-link-icon-submenu" alt="">
                                    </a>
                                    <ul class="dropdown-menu">
                                         <li>
                                            <a class="dropdown-item" href="facebook-ads.php">
                                                <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                                Facebook Marketing
                                            </a>
                                        </li>
                                         <li>
                                            <a class="dropdown-item" href="facebook-page-setup.php">
                                                <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                               Facebook Page Setup
                                            </a>
                                        </li>
                                    </ul>
                                </li>
  <li>
                                    <a class="dropdown-item" href="uk-company-registration.php">
                                        <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                        UK Company Registration
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="vps.php">
                                        <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                        Cheapest RDP/VPS
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="shop.php">
                                <img src="https://api.iconify.design/mdi:view-grid-outline.svg?color=white" class="nav-link-icon" alt="">
                                <span>Shop</span>
                            </a>
                        </li>
                        <!-- PRODUCT DETAILS REMOVED -->
                        <li class="nav-item">
                            <a class="nav-link" href="font.php">
                                <img src="https://api.iconify.design/mdi:cart-outline.svg?color=white" class="nav-link-icon" alt="">
                                <span>Font</span>
                            </a>
                        </li>

                          <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="about.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://api.iconify.design/mdi:chevron-down.svg?color=white" class="nav-link-icon-dropdown-main" alt="">
                                <span>About Us</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="about.php">
                                        <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                        Our Company
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="team.php">
                                        <img src="https://api.iconify.design/mdi:arrow-right-thin.svg?color=%23343a40" class="dropdown-icon-svg" alt="">
                                        Our Team
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="why-choose-us.php">
                                <img src="https://api.iconify.design/mdi:cash-register.svg?color=white" class="nav-link-icon" alt="">
                                <span>Why Choose Us</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="clients.php">
                                <img src="https://api.iconify.design/mdi:cash-register.svg?color=white" class="nav-link-icon" alt="">
                                <span>Clients</span>
                            </a>
                        </li>


                         <li class="nav-item">
                            <a class="nav-link" href="media.php">
                                <img src="https://api.iconify.design/mdi:cash-register.svg?color=white" class="nav-link-icon" alt="">
                                <span>Media</span>
                            </a>
                        </li>
                       
                      
                       
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">
                                <img src="https://api.iconify.design/mdi:email-fast-outline.svg?color=white" class="nav-link-icon" alt="">
                                <span>Contact</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- NEW: Mobile Search Popup -->
        <div class="mobile-search-popup d-lg-none" id="mobile-search-popup">
            <div class="container">
                <form class="mobile-search-form">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for products...">
                        <button class="btn" type="button">
                            <img src="https://api.iconify.design/mdi:magnify.svg?color=white" alt="Search" class="header-icon-svg-btn">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </header>
    
    <!-- Right Navigation Sidebar (for mobile) -->
    <div class="sidebar right nav-sidebar" id="nav-sidebar">
        <div class="sidebar-header">
            <h5>Menu</h5>
            <button class="close-btn" id="close-nav-sidebar" aria-label="Close navigation">
                <img src="https://api.iconify.design/mdi:close.svg?color=%23ffffff" alt="Close" class="header-icon-svg-close">
            </button>
        </div>
        <!-- UPDATED: Mobile Navigation List -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <img src="https://api.iconify.design/mdi:home-outline.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    Home
                </a>
            </li>
             <li class="nav-item">
                <a class="nav-link" href="about.php">
                    <img src="https://api.iconify.design/mdi:information-outline.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    About
                </a>
            </li>
              <!-- Collapsible Dropdown -->
            <li class="nav-item">
                <a class="nav-link nav-link-collapse" data-bs-toggle="collapse" href="#collapseDropdown" role="button" aria-expanded="false" aria-controls="collapseDropdown">
                    <img src="https://api.iconify.design/mdi:chevron-down.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    Our Services
                    <img src="https://api.iconify.design/mdi:chevron-right.svg?color=%23343a40" class="nav-sidebar-arrow" alt="">
                </a>
                <div class="collapse nav-sidebar-collapse" id="collapseDropdown">
                    <ul>
                        <li><a class="nav-link" href="digital.php">Digital Marketing</a></li>
                        <li><a class="nav-link" href="graphic.php">Graphics Design</a></li>
                        <li><a class="nav-link" href="websolution.php">Web Solutions</a></li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-collapse" data-bs-toggle="collapse" href="#collapseChildDropdown" role="button" aria-expanded="false" aria-controls="collapseChildDropdown">
                                Social Media Management
                                <img src="https://api.iconify.design/mdi:chevron-right.svg?color=%23343a40" class="nav-sidebar-arrow" alt="">
                            </a>
                            <div class="collapse nav-sidebar-collapse-child" id="collapseChildDropdown">
                                <ul>
                                    <li><a class="nav-link" href="facebook-page-setup.php">Facebook Page Setup</a></li>
                                    <li><a class="nav-link" href="facebook-ads.php">Facebook Ads Management</a></li>
                                </ul>
                            </div>
                        </li>
                        <li><a class="nav-link" href="uk-company-registration.php">Uk Company Registration</a></li>
                        <li><a class="nav-link" href="vps.php">Cheapest RDP/VPS</a></li>
                    </ul>
                </div>
            </li>
              <li class="nav-item">
                <a class="nav-link" href="shop.php">
                    <img src="https://api.iconify.design/mdi:view-grid-outline.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    Shop
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="font.php">
                    <img src="https://api.iconify.design/mdi:cart-outline.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    Font
                </a>
            </li>

             <!-- Collapsible Megamenu 1 -->
            <li class="nav-item">
                <a class="nav-link nav-link-collapse" data-bs-toggle="collapse" href="#collapseMegamenu1" role="button" aria-expanded="false" aria-controls="collapseMegamenu1">
                    <img src="https://api.iconify.design/mdi:chevron-down.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    About Us
                    <img src="https://api.iconify.design/mdi:chevron-right.svg?color=%23343a40" class="nav-sidebar-arrow" alt="">
                </a>
                <div class="collapse nav-sidebar-collapse" id="collapseMegamenu1">
                    <ul>
                        <li><a class="nav-link" href="about.php">Our Company</a></li>
                        <li><a class="nav-link" href="team.php">Our Team</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="why-choose-us.php">
                    <img src="https://api.iconify.design/mdi:cash-register.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    Why Choose Us
                </a>
            </li>


             <li class="nav-item">
                <a class="nav-link" href="clients.php">
                    <img src="https://api.iconify.design/mdi:cash-register.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    Clients
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="media.php">
                    <img src="https://api.iconify.design/mdi:cash-register.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    Media
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="wishlist.php">
                    <img src="https://api.iconify.design/mdi:heart-outline.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    Wishlist
                </a>
            </li>
           
          
            
            <li class="nav-item">
                <a class="nav-link" href="contact.php">
                    <img src="https://api.iconify.design/mdi:email-fast-outline.svg?color=%23343a40" class="nav-sidebar-icon" alt="">
                    Contact
                </a>
            </li>
            
          
        </ul>
    </div>

    <!-- Cart Sidebar -->
    @include('front.include.cartsidebar')
    
    <!-- NEW: Login Sidebar -->
    @include('front.include.loginsidebar')