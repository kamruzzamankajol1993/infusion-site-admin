@php
     $usr = Auth::user();
 @endphp

 <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 px-4 shadow-sm">
                <!-- Menu Toggle Button -->
                <div class="d-flex align-items-center">
                    <i class="bi bi-filter-left primary-text fs-4 me-3" id="menu-toggle" style="font-size: 2rem !important;"></i>
                    <h4 class=" m-0">{{ $ins_name }}</h4>
                </div>

                <!-- Navbar Toggler for mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar content -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- ms-auto pushes content to the right -->
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        <!-- Today's Date -->
                        <li class="nav-item">
                            <span class="navbar-text me-lg-3" id="currentDate"></span>
                        </li>
                        <!-- Clear Cache Button -->
                        <li class="nav-item my-2 my-lg-0">
                            <button  onclick="window.location.href='{{ url('/clear') }}';" class="btn btn-danger btn-sm me-lg-3">
                                <i class="bi bi-arrow-clockwise me-1"></i>Clear Cache
                            </button>
                        </li>
                        <!-- User Profile Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle second-text fw-bold text-dark" href="#" id="navbarDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                     @if(empty(Auth::user()->image))
                  
                     <img src="{{asset('/')}}public/No_Image_Available.jpg" alt="Admin" width="30" height="30" class="rounded-circle">
                    @else
                    <img src="{{asset('/')}}{{ Auth::user()->image }}" alt="Admin" width="30" height="30" class="rounded-circle">
                    @endif<span class="ms-2">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                  @if ($usr->can('profileView'))
                                <li><a class="dropdown-item" href="{{ route('profileView') }}">Profile</a></li>
                                    @endif
                                    @if ($usr->can('profileSetting'))
                                <li><a class="dropdown-item" href="{{ route('profileSetting') }}">Settings</a></li>
                                    @endif
                                <li><a class="dropdown-item" href="{{ route('logout') }}"  onclick="event.preventDefault();
                      document.getElementById('admin-logout-form').submit();">Logout</a></li>
                      <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>


 

