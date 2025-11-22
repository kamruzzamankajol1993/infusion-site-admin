@php
     $usr = Auth::user(); // Get authenticated user

     // Set default fallbacks in case the View Composer fails or DB is empty
     if (!isset($engageTitles)) {
         $engageTitles = [1 => 'Single Source Selection', 2 => 'Tendering'];
     }
@endphp

{{-- Sidebar Wrapper --}}
<div class="shadow-sm" id="sidebar-wrapper">

    {{-- Sidebar Header (Logo & Close Button) --}}
    <div class="sidebar-heading d-flex align-items-center justify-content-center position-relative">
        {{-- Logo --}}
        <img src="{{ asset($front_logo_name) }}" alt="{{ $ins_name }} Logo" style="height: 40px;">
        {{-- Mobile Close Button --}}
        <button class="btn d-md-none" id="sidebar-close-button" style="position: absolute; top: 50%; right: 0.5rem; transform: translateY(-50%);">
            <i class="bi bi-x-lg text-white fs-5"></i>
        </button>
    </div>

    {{-- Scrollable Link List --}}
    <div class="list-group list-group-flush my-3" id="sidebar-accordion">

        {{-- Dashboard Link --}}
        @if ($usr->can('dashboardView'))
        <a href="{{ route('home') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('home') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-3"></i>Dashboard
        </a>
        @endif

        {{-- Frontend Section Title --}}
        <div class="sidebar-title px-3 pt-3">Frontend</div>

        @if ($usr->can('sliderView') || $usr->can('iifcStrengthView') || $usr->can('clientView') || $usr->can('countryView'))
        
         {{-- Added slider.* and iifcStrength.* to Route::is checks --}}
        <a href="#homepage-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is(['slider.*', 'iifcStrength.*', 'singleSourceSelection.*', 'tendering.*', 'client.*', 'country.*','solution.*']) ? 'active' : '' }}">
            <div><i class="bi bi-house-door me-3"></i>Home Page</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        {{-- Added slider.* and iifcStrength.* to Route::is checks --}}
        <div id="homepage-collapse" class="collapse {{ Route::is(['slider.*', 'iifcStrength.*', 'singleSourceSelection.*', 'tendering.*', 'client.*', 'country.*','solution.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                {{-- Slider Link (MOVED HERE) --}}
                @if ($usr->can('sliderView'))
                <a href="{{ route('slider.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('slider.*') ? 'active' : '' }}">
                   Sliders
                </a>
                @endif
                 {{-- IIFC Strength Link (MOVED HERE) --}}
                @if ($usr->can('iifcStrengthView'))
              
               
                <a href="{{ route('iifcStrength.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('iifcStrength.*') ? 'active' : '' }}">
                  At A Glance
                </a>
              
               
                @endif

                @if ($usr->can('solutionView')) {{-- Make sure to create this permission --}}
        <a href="{{ route('solution.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('solution.*') ? 'active' : '' }}">
            Our Solutions
        </a>
        @endif

        @if ($usr->can('countryView'))
                <a href="{{ route('country.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('country.*') ? 'active' : '' }}">
                    Country List
                </a>
                @endif

        @if ($usr->can('clientView'))
                <a href="{{ route('client.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('client.*') ? 'active' : '' }}">
                    Client Logos
                </a>
                @endif
                {{-- Add other home page links here if needed --}}

                @if ($usr->can('whyUsView')) {{-- Make sure to create this permission --}}
        <a href="{{ route('why-us.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('why-us.*') ? 'active' : '' }}">
            Why Us
        </a>
        @endif
            </div>
        </div>
        @endif {{-- End Home Page Parent Check --}}

        {{-- About Us Link --}}
        @if ($usr->can('aboutUsView'))
        <a href="{{ route('aboutUs.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('aboutUs.*') ? 'active' : '' }}">
            <i class="bi bi-info-circle me-3"></i>About Us
        </a>
        @endif
{{-- *** NEW TEAM LINK *** --}}
        @if ($usr->can('teamView')) {{-- Make sure to create this permission --}}
        <a href="{{ route('team.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('team.*') ? 'active' : '' }}">
            <i class="bi bi-people me-3"></i>Team
        </a>
        @endif
        {{-- *** END NEW LINK *** --}}
        {{-- *** NEW WHY CHOOSE US LINK *** --}}
        @if ($usr->can('whyChooseUsView')) {{-- Make sure to create this permission --}}
        <a href="{{ route('why-choose-us.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('why-choose-us.*') ? 'active' : '' }}">
            <i class="bi bi-award me-3"></i>Why Choose Us
        </a>
        @endif
        {{-- *** END NEW LINK *** --}}

        {{-- *** NEW MEDIA LINK *** --}}
        @if ($usr->can('mediaView')) {{-- Make sure to create this permission --}}
        <a href="{{ route('media.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('media.*') ? 'active' : '' }}">
            <i class="bi bi-youtube me-3"></i>Media
        </a>
        @endif
        {{-- *** END NEW LINK *** --}}
        {{-- Contact Messages Link --}}
        @if ($usr->can('contactUsView'))
        <a href="{{ route('contactUs.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('contactUs.*') ? 'active' : '' }}">
            <i class="bi bi-envelope me-3"></i> Messages
        </a>
        @endif

       {{-- *** NEW DIGITAL MARKETING DROPDOWN *** --}}
        @if ($usr->can('digitalMarketingPageView') || $usr->can('digitalMarketingGrowthView') || $usr->can('digitalMarketingSolutionView'))
        <a href="#digital-marketing-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is(['digitalMarketingPage.*', 'digital-marketing-growth.*', 'digital-marketing-solution.*']) ? 'active' : '' }}">
            <div><i class="bi bi-megaphone me-3"></i>Digital Marketing</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="digital-marketing-collapse" class="collapse {{ Route::is(['digitalMarketingPage.*', 'digital-marketing-growth.*', 'digital-marketing-solution.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                
                @if ($usr->can('digitalMarketingPageView')) {{-- Permission for Page Content --}}
                <a href="{{ route('digitalMarketingPage.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('digitalMarketingPage.*') ? 'active' : '' }}">
                    Page Content
                </a>
                @endif
                
                @if ($usr->can('digitalMarketingGrowthView')) {{-- Permission for Growth Checklist --}}
                <a href="{{ route('digital-marketing-growth.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('digital-marketing-growth.*') ? 'active' : '' }}">
                    Growth Checklist
                </a>
                @endif

                @if ($usr->can('digitalMarketingSolutionView')) {{-- Permission for Solutions --}}
                 <a href="{{ route('digital-marketing-solution.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('digital-marketing-solution.*') ? 'active' : '' }}">
                    Marketing Solutions
                </a>
                @endif
            </div>
        </div>
        @endif {{-- End Digital Marketing Parent Check --}}

    {{-- *** NEW GRAPHIC DESIGN DROPDOWN *** --}}
        @if ($usr->can('graphicDesignPageView')) {{-- Create this base permission --}}
        <a href="#graphic-design-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is('graphicDesign.*') ? 'active' : '' }}">
            <div><i class="bi bi-palette me-3"></i>Graphic Design</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="graphic-design-collapse" class="collapse {{ Route::is('graphicDesign.*') ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                
                <a href="{{ route('graphicDesign.page.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('graphicDesign.page.*') ? 'active' : '' }}">
                    Page Content
                </a>
                
                <a href="{{ route('graphicDesign.checklist.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('graphicDesign.checklist.*') ? 'active' : '' }}">
                    Growth Checklist
                </a>

                 <a href="{{ route('graphicDesign.solution.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('graphicDesign.solution.*') ? 'active' : '' }}">
                    Marketing Solutions
                </a>
            </div>
        </div>
        @endif {{-- End Graphic Design Parent Check --}}
{{-- *** NEW WEB SOLUTION DROPDOWN *** --}}
        @if (Auth::user()->can('webSolutionPageView')) {{-- Create this base permission --}}
        <a href="#web-solution-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is('webSolution.*') ? 'active' : '' }}">
            <div><i class="bi bi-display me-3"></i>Web Solution</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="web-solution-collapse" class="collapse {{ Route::is('webSolution.*') ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                
                {{-- Add permission checks for each as needed --}}
                <a href="{{ route('webSolution.page.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('webSolution.page.*') ? 'active' : '' }}">
                    Page Content
                </a>
                
                <a href="{{ route('webSolution.checklist.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('webSolution.checklist.*') ? 'active' : '' }}">
                    Why Choose Us (List)
                </a>

                 <a href="{{ route('webSolution.include.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('webSolution.include.*') ? 'active' : '' }}">
                    Service Includes
                </a>
                
                <a href="{{ route('webSolution.providing.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('webSolution.providing.*') ? 'active' : '' }}">
                    Services Providing
                </a>
                
                <a href="{{ route('webSolution.workCategory.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('webSolution.workCategory.*') ? 'active' : '' }}">
                    Work Categories
                </a>
                
                 <a href="{{ route('webSolution.workItem.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('webSolution.workItem.*') ? 'active' : '' }}">
                    Work Portfolio Items
                </a>
                
                <a href="{{ route('webSolution.careItem.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('webSolution.careItem.*') ? 'active' : '' }}">
                    Website Care Items
                </a>

            </div>
        </div>
        @endif {{-- End Web Solution Parent Check --}}
      {{-- *** NEW FACEBOOK ADS DROPDOWN *** --}}
        @if ($usr->can('facebookAdsPageView')) {{-- Create this base permission --}}
        <a href="#facebook-ads-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is('facebookAds.*') ? 'active' : '' }}">
            <div><i class="bi bi-megaphone-fill me-3"></i>Facebook Ads</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="facebook-ads-collapse" class="collapse {{ Route::is('facebookAds.*') ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                
                <a href="{{ route('facebookAds.page.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('facebookAds.page.*') ? 'active' : '' }}">
                    Page Content
                </a>
                
                <a href="{{ route('facebookAds.feature.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('facebookAds.feature.*') ? 'active' : '' }}">
                    Features
                </a>

                 <a href="{{ route('facebookAds.campaign.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('facebookAds.campaign.*') ? 'active' : '' }}">
                    Campaign Types
                </a>
                
                <a href="{{ route('facebookAds.pricingCategory.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('facebookAds.pricingCategory.*') ? 'active' : '' }}">
                    Pricing Categories
                </a>
                
                 <a href="{{ route('facebookAds.pricingPackage.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('facebookAds.pricingPackage.*') ? 'active' : '' }}">
                    Pricing Packages
                </a>
                
                <a href="{{ route('facebookAds.faq.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('facebookAds.faq.*') ? 'active' : '' }}">
                    FAQs
                </a>

            </div>
        </div>
        @endif {{-- End Facebook Ads Parent Check --}}
{{-- *** NEW FACEBOOK PAGE SETUP DROPDOWN *** --}}
        @if ($usr->can('facebookPageView')) {{-- Create this base permission --}}
        <a href="#facebook-page-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is('facebookPage.*') ? 'active' : '' }}">
            <div><i class="bi bi-facebook me-3"></i>Facebook Page</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="facebook-page-collapse" class="collapse {{ Route::is('facebookPage.*') ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                
                <a href="{{ route('facebookPage.page.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('facebookPage.page.*') ? 'active' : '' }}">
                    Page Content
                </a>
                
                <a href="{{ route('facebookPage.package.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('facebookPage.package.*') ? 'active' : '' }}">
                    Pricing Packages
                </a>

                 <a href="{{ route('facebookPage.service.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('facebookPage.service.*') ? 'active' : '' }}">
                    More Services
                </a>
            </div>
        </div>
        @endif {{-- End Facebook Page Parent Check --}}
        {{-- *** NEW UK COMPANY SETUP DROPDOWN *** --}}
        @if ($usr->can('ukCompanyPageView')) {{-- Create this base permission --}}
        <a href="#uk-company-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is('ukCompany.*') ? 'active' : '' }}">
            <div><i class="bi bi-bank me-3"></i>UK Company Setup</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="uk-company-collapse" class="collapse {{ Route::is('ukCompany.*') ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                
                <a href="{{ route('ukCompany.page.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('ukCompany.page.*') ? 'active' : '' }}">
                    Page Content
                </a>
                
                <a href="{{ route('ukCompany.package.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('ukCompany.package.*') ? 'active' : '' }}">
                    Pricing Packages
                </a>

                 <a href="{{ route('ukCompany.testimonial.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('ukCompany.testimonial.*') ? 'active' : '' }}">
                    Testimonials
                </a>
                
                <a href="{{ route('ukCompany.review-platform.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('ukCompany.review-platform.*') ? 'active' : '' }}">
                    Review Platforms
                </a>

            </div>
        </div>
        @endif {{-- End UK Company Parent Check --}}

        {{-- *** NEW VPS/RDP DROPDOWN *** --}}
        @if ($usr->can('vpsPageView')) {{-- Create this base permission --}}
        <a href="#vps-page-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is('vpsPage.*') ? 'active' : '' }}">
            <div><i class="bi bi-server me-3"></i>VPS / RDP Page</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="vps-page-collapse" class="collapse {{ Route::is('vpsPage.*') ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                
                <a href="{{ route('vpsPage.page.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('vpsPage.page.*') ? 'active' : '' }}">
                    Page Content
                </a>
                
                <a href="{{ route('vpsPage.category.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('vpsPage.category.*') ? 'active' : '' }}">
                    Package Categories
                </a>

                 <a href="{{ route('vpsPage.package.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('vpsPage.package.*') ? 'active' : '' }}">
                    Packages
                </a>
            </div>
        </div>
        @endif {{-- End VPS/RDP Parent Check --}}
       {{-- ============================================================== --}}
        {{--                   ECOMMERCE MANAGEMENT                         --}}
        {{-- ============================================================== --}}
        @if ($usr->can('orderView') || $usr->can('productView') || $usr->can('categoryView') || $usr->can('reviewView') || $usr->can('storeMainBannerView'))
        <div class="sidebar-title px-3 pt-3">Ecommerce & Store</div>
        <a href="#ecommerce-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center 
           {{ Route::is(['order.*', 'product.*', 'category.*', 'review.*', 'coupon.*', 'storeMainBanner.*', 'storeSideBanner.*','customer.*']) ? 'active' : '' }}">
            <div><i class="bi bi-cart3 me-3"></i>Store Management</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="ecommerce-collapse" class="collapse {{ Route::is(['order.*', 'product.*', 'category.*', 'review.*', 'coupon.*', 'storeMainBanner.*', 'storeSideBanner.*','customer.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                @if ($usr->can('customerView'))
                <a href="{{ route('customer.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('customer.*') ? 'active' : '' }}">
                    Customers
                </a>
                @endif
                @if ($usr->can('orderView'))
                <a href="{{ route('order.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('order.*') ? 'active' : '' }}">
                    Orders
                </a>
                @endif

                @if ($usr->can('productView'))
                <a href="{{ route('product.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('product.*') ? 'active' : '' }}">
                    Products
                </a>
                @endif

                @if ($usr->can('categoryView'))
                <a href="{{ route('category.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('category.*') ? 'active' : '' }}">
                    Categories
                </a>
                @endif

                @if ($usr->can('reviewView'))
                <a href="{{ route('review.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('review.*') ? 'active' : '' }}">
                    Product Reviews
                </a>
                @endif

                @if ($usr->can('couponView')) {{-- Assuming coupon permission --}}
                <a href="{{ route('coupon.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('coupon.*') ? 'active' : '' }}">
                    Coupons
                </a>
                @endif

                @if ($usr->can('storeMainBannerView'))
                <a href="{{ route('storeMainBanner.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('storeMainBanner.*') ? 'active' : '' }}">
                    Store Sliders
                </a>
                @endif

                @if ($usr->can('storeSideBannerView'))
                <a href="{{ route('storeSideBanner.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('storeSideBanner.*') ? 'active' : '' }}">
                    Store Side Banners
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- ============================================================== --}}
        {{--                   FRONTEND PAGES MODULES                       --}}
        {{-- ============================================================== --}}
        

        {{-- Settings Section (Parent) --}}
        @if ($usr->can('userView') || $usr->can('designationView') || $usr->can('departmentView') || $usr->can('permissionView') || $usr->can('roleView') || $usr->can('panelSettingView'))
        <div class="sidebar-title px-3 pt-3">General & User Setting</div>
        <a href="#settings-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is(['users.*', 'department.*', 'designation.*','permissions.*', 'roles.*', 'systemInformation.*','socialLink.*', 'extraPage.*','topHeaderLink.*']) ? 'active' : '' }}">
            <div><i class="bi bi-gear me-3"></i>Setting</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="settings-collapse" class="collapse {{ Route::is(['users.*', 'department.*', 'designation.*','permissions.*', 'roles.*', 'systemInformation.*','socialLink.*', 'extraPage.*','topHeaderLink.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                {{-- Sub-sections within Settings --}}
                @if ($usr->can('departmentView') || $usr->can('designationView') || $usr->can('userView'))
                <div class="sidebar-title px-3 pt-3">User Management</div>
                @endif
                @if ($usr->can('departmentView'))
                <a href="{{ route('department.index') }}" class="{{ Route::is('department.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">Department/Institute</a>
                @endif
                @if ($usr->can('designationView'))
                <a href="{{ route('designation.index') }}" class="{{ Route::is('designation.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">Designation</a>
                 @endif
                @if ($usr->can('userView'))
                <a href="{{ route('users.index') }}" class="{{ Route::is('users.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">User List</a>
                @endif

                @if ($usr->can('permissionView') || $usr->can('roleView') || $usr->can('panelSettingView'))
                <div class="sidebar-title px-3 pt-3">System & Roles</div>
                @endif
                @if ($usr->can('permissionView'))
                <a href="{{ route('permissions.index') }}" class="{{ Route::is('permissions.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">Permissions</a>
                @endif
                 @if ($usr->can('roleView'))
                <a href="{{ route('roles.index') }}" class="{{ Route::is('roles.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">Roles</a>
                 @endif
                @if ($usr->can('panelSettingView'))
                <a href="{{ route('systemInformation.index') }}" class="{{ Route::is('systemInformation.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">System Setting</a>
                @endif
                {{-- Social Link (NEW) --}}
                 @if ($usr->can('socialLinkView'))
                 <a href="{{ route('socialLink.index') }}" class="{{ Route::is('socialLink.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">
                     Social Links
                 </a>
                 @endif
                 @can('headerLinkView')

                   <a href="{{ route('topHeaderLink.index') }}" class="{{ Route::is('topHeaderLink.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">
                     Top Header Links
                 </a>

                 {{--<a href="{{ route('navbarSetting.index') }}" class="{{ Route::is('navbarSetting.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">
                     Navbar Setting
                 </a> --}}

   
@endcan
                 @if ($usr->can('extraPageView')) {{-- Assuming permission --}}
                 <a href="{{ route('extraPage.index') }}" class="{{ Route::is('extraPage.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">
                     Extra Pages
                 </a>
                 @endif
            </div>
        </div>
        @endif {{-- End Settings Parent Check --}}

    </div> {{-- End #sidebar-accordion --}}
</div> {{-- End #sidebar-wrapper --}}