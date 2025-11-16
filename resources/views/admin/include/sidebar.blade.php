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
            <i class="bi bi-youtube me-3"></i>Media (YouTube)
        </a>
        @endif
        {{-- *** END NEW LINK *** --}}
        {{-- Contact Messages Link --}}
        @if ($usr->can('contactUsView'))
        <a href="{{ route('contactUs.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('contactUs.*') ? 'active' : '' }}">
            <i class="bi bi-envelope me-3"></i> Messages
        </a>
        @endif

        {{-- Services Link --}}
        @if ($usr->can('serviceView'))
        <a href="{{ route('service.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('service.*') ? 'active' : '' }}">
            <i class="bi bi-card-checklist me-3"></i>Services
        </a>
        @endif

       

        {{-- Projects Submenu (Parent) --}}
        {{-- Checks if user can view any related item to show the parent --}}
        @if ($usr->can('projectCategoryView') || $usr->can('projectView') )
        <a href="#projects-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is(['projectCategory.*', 'project.*']) ? 'active' : '' }}">
            <div><i class="bi bi-kanban me-3"></i>Projects</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="projects-collapse" class="collapse {{ Route::is(['projectCategory.*', 'project.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                {{-- Project Categories Link --}}
                @if ($usr->can('projectCategoryView'))
                <a href="{{ route('projectCategory.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('projectCategory.*') ? 'active' : '' }}">
                    Categories
                </a>
                @endif
                {{-- All Projects Link --}}
                 @if ($usr->can('projectView'))
                <a href="{{ route('project.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('project.*') ? 'active' : '' }}">
                    All Projects
                </a>
                @endif
                {{-- Client Logos Link --}}
                
                {{-- Country List Link --}}
                
            </div>
        </div>
        @endif {{-- End Project Parent Check --}}

        {{-- Training Submenu (Parent) --}}
        @if ($usr->can('trainingView') || $usr->can('upcomingTabImageView')) {{-- Updated permission check --}}
        <a href="#training-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is(['training.*','upcomingTabImage.*','trainingEnrollment.*']) ? 'active' : '' }}" >
            <div><i class="bi bi-journals me-3"></i>Training</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="training-collapse" class="collapse {{ Route::is(['training.*', 'upcomingTabImage.*','trainingEnrollment.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                
                @if ($usr->can('upcomingTabImageView')) {{-- Added permission check --}}
                <a href="{{ route('upcomingTabImage.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('upcomingTabImage.*') ? 'active' : '' }}">
                    Upcoming Tab Image
                </a>
                @endif

                @if ($usr->can('trainingAdd'))
                <a href="{{ route('training.create') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('training.create') ? 'active' : '' }}">
                    Create Training
                </a>
                @endif
                
                 @if ($usr->can('trainingView'))
                <a href="{{ route('training.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('training.index') || Route::is('training.show') || Route::is('training.edit') ? 'active' : '' }}">
                    All Trainings
                </a>
                 @endif

                 @if ($usr->can('trainingEnrollmentView')) {{-- Added Enrollment Link --}}
                 <a href="{{ route('trainingEnrollment.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('trainingEnrollment.*') ? 'active' : '' }}">
                     Enrollments
                 </a>
                 @endif
            </div>
        </div>
        @endif {{-- End Training Parent Check --}}

        {{-- Notice Submenu (Parent) --}}
        @if ($usr->can('noticeCategoryView') || $usr->can('noticeView'))
        <a href="#notice-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is(['noticeCategory.*', 'notice.*']) ? 'active' : '' }}" >
            <div><i class="bi bi-bell me-3"></i>Notice</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="notice-collapse" class="collapse {{ Route::is(['noticeCategory.*', 'notice.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                {{-- Notice Categories Link --}}
                @if ($usr->can('noticeCategoryView'))
                <a href="{{ route('noticeCategory.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('noticeCategory.*') ? 'active' : '' }}">
                    Categories
                </a>
                @endif
                {{-- All Notices Link (UPDATED) --}}
                 @if ($usr->can('noticeView'))
                <a href="{{ route('notice.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('notice.*') ? 'active' : '' }}">
                    All Notices
                </a>
                 @endif
            </div>
        </div>
        @endif {{-- End Notice Parent Check --}}

        {{-- Other Frontend Links (Placeholders or actual links) --}}
    {{-- Press Release Link (UPDATED/CONFIRMED) --}}
        @if ($usr->can('pressReleaseView')) {{-- Assuming permission --}}
        <a href="{{ route('pressRelease.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('pressRelease.*') ? 'active' : '' }}">
            <i class="bi bi-newspaper me-3"></i>Press Release
        </a>
        @endif
{{-- Career Link (NEW) --}}
       {{-- *** NEW CAREER DROPDOWN *** --}}
        @if ($usr->can('careerView') || $usr->can('jobApplicantView')) {{-- Check if user can see either item --}}
        <div class="sidebar-title px-3 pt-3">Careers & Applications</div>
        <a href="#career-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is(['career.*', 'jobApplicant.*']) ? 'active' : '' }}">
            <div><i class="bi bi-briefcase me-3"></i>Careers</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="career-collapse" class="collapse {{ Route::is(['career.*', 'jobApplicant.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                {{-- Career Postings Link --}}
                @if ($usr->can('careerView'))
                <a href="{{ route('career.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('career.*') ? 'active' : '' }}">
                    Career Postings
                </a>
                @endif
                {{-- Job Applicants Link --}}
                @if ($usr->can('jobApplicantView'))
                <a href="{{ route('jobApplicant.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('jobApplicant.*') ? 'active' : '' }}">
                    Job Applicants
                </a>
                @endif
            </div>
        </div>
        @endif {{-- End Career Parent Check --}}
        
    @if ($usr->can('galleryView')) {{-- Assuming permission --}}
        <a href="{{ route('gallery.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('gallery.*') ? 'active' : '' }}">
            <i class="bi bi-images me-3"></i>Gallery
        </a>
        @endif
@if ($usr->can('publicationView')) {{-- Assuming permission --}}
        <a href="{{ route('publication.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('publication.*') ? 'active' : '' }}">
            <i class="bi bi-book me-3"></i>Publication
        </a>
        @endif

        {{-- ++++++++++ NEW FILE DOWNLOADS LINK ++++++++++ --}}
        @if ($usr->can('downloadView')) {{-- New permission check --}}
        <a href="{{ route('download.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('download.*') ? 'active' : '' }}">
            <i class="bi bi-download me-3"></i>File Downloads
        </a>
        @endif
        {{-- ++++++++++ END NEW LINK ++++++++++ --}}

        @if ($usr->can('eventView')) {{-- Assuming permission --}}
        <a href="{{ route('event.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('event.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event me-3"></i>Events {{-- Example Icon --}}
        </a>
        @endif
      
{{-- Important Links Link --}}
         @if ($usr->can('importantLinkView')) {{-- ADD PERMISSION CHECK --}}
         <a href="{{ route('importantLink.index') }}" class="list-group-item list-group-item-action bg-transparent {{ Route::is('importantLink.*') ? 'active' : '' }}">
             <i class="bi bi-link-45deg me-3"></i>Important Links {{-- Example Icon --}}
         </a>
         @endif
        {{-- Officer Section (Parent) --}}
        @if($usr->can('officerCategoryView') || $usr->can('officerView'))
         <div class="sidebar-title px-3 pt-3">Officer, Board Member</div>
        <a href="#team-collapse" data-bs-toggle="collapse" class="list-group-item list-group-item-action bg-transparent d-flex justify-content-between align-items-center {{ Route::is(['officerCategory.*', 'officer.*']) ? 'active' : '' }}" >
            <div><i class="bi bi-person-badge me-3"></i>Officers</div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="team-collapse" class="collapse {{ Route::is(['officerCategory.*', 'officer.*']) ? 'show' : '' }}" data-bs-parent="#sidebar-accordion">
            <div class="list-group list-group-flush sidebar-submenu">
                {{-- Officer Categories Link --}}
                @if($usr->can('officerCategoryView'))
                    <a href="{{ route('officerCategory.index') }}" class="{{ Route::is('officerCategory.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">Officer Categories</a>
                @endif
                {{-- All Officers Link --}}
                @if($usr->can('officerView'))
                    <a href="{{ route('officer.index') }}" class="{{ Route::is('officer.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">All Officers</a>
                @endif
            </div>
        </div>
        @endif {{-- End Officer Parent Check --}}

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

                 <a href="{{ route('navbarSetting.index') }}" class="{{ Route::is('navbarSetting.*') ? 'active' : '' }} list-group-item list-group-item-action bg-transparent">
                     Navbar Setting
                 </a>

   
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