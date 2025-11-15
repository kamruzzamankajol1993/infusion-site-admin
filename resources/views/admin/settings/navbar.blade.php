@extends('admin.master.master')

@section('title')
Manage Navbar Menus | {{ $ins_name ?? 'IIFC' }}
@endsection

@section('css')
<style>
    .card-header-custom {
        background-color: #f0f2f5;
        border-bottom: 2px solid var(--primary-color, #175A3A);
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active" aria-current="page">Navbar Menus</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Manage Navbar Menu Names</h5>
        </div>
        <div class="card-body">
            @include('flash_message')
            
            <form action="{{ route('navbarSetting.storeOrUpdate') }}" method="POST">
                @csrf
                <p class="text-muted">Change the text that appears in your React site's main navigation bar.</p>
                
                {{-- Main Navigation Card --}}
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h6 class="mb-0">Main Navigation Items</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4"><label for="nav_home" class="form-label">Home <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_home') is-invalid @enderror" id="nav_home" name="nav_home" value="{{ old('nav_home', $nav_home ?? 'Home') }}" required>@error('nav_home')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_about_iifc" class="form-label">About IIFC (Main) <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_about_iifc') is-invalid @enderror" id="nav_about_iifc" name="nav_about_iifc" value="{{ old('nav_about_iifc', $nav_about_iifc ?? 'About IIFC') }}" required>@error('nav_about_iifc')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_services" class="form-label">Services <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_services') is-invalid @enderror" id="nav_services" name="nav_services" value="{{ old('nav_services', $nav_services ?? 'Services') }}" required>@error('nav_services')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_projects" class="form-label">Projects <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_projects') is-invalid @enderror" id="nav_projects" name="nav_projects" value="{{ old('nav_projects', $nav_projects ?? 'Projects') }}" required>@error('nav_projects')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_training" class="form-label">Training (Main) <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_training') is-invalid @enderror" id="nav_training" name="nav_training" value="{{ old('nav_training', $nav_training ?? 'Training') }}" required>@error('nav_training')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_resources" class="form-label">Resources (Main) <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_resources') is-invalid @enderror" id="nav_resources" name="nav_resources" value="{{ old('nav_resources', $nav_resources ?? 'Resources') }}" required>@error('nav_resources')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_notice" class="form-label">Notice <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_notice') is-invalid @enderror" id="nav_notice" name="nav_notice" value="{{ old('nav_notice', $nav_notice ?? 'Notice') }}" required>@error('nav_notice')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        </div>
                    </div>
                </div>

                {{-- "About IIFC" Dropdown Card --}}
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h6 class="mb-0">"About IIFC" Dropdown</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4"><label for="nav_about_us" class="form-label">About Us <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_about_us') is-invalid @enderror" id="nav_about_us" name="nav_about_us" value="{{ old('nav_about_us', $nav_about_us ?? 'About Us') }}" required>@error('nav_about_us')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_board" class="form-label">Board Of Directors <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_board') is-invalid @enderror" id="nav_board" name="nav_board" value="{{ old('nav_board', $nav_board ?? 'Board Of Directors') }}" required>@error('nav_board')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_subscriber" class="form-label">Subscriber Members <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_subscriber') is-invalid @enderror" id="nav_subscriber" name="nav_subscriber" value="{{ old('nav_subscriber', $nav_subscriber ?? 'Subscriber Members') }}" required>@error('nav_subscriber')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_experts" class="form-label">Our Experts <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_experts') is-invalid @enderror" id="nav_experts" name="nav_experts" value="{{ old('nav_experts', $nav_experts ?? 'Our Experts') }}" required>@error('nav_experts')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_officers" class="form-label">Officers <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_officers') is-invalid @enderror" id="nav_officers" name="nav_officers" value="{{ old('nav_officers', $nav_officers ?? 'Officers') }}" required>@error('nav_officers')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_past-chairmen" class="form-label">Past Chairmen <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_past-chairmen') is-invalid @enderror" id="nav_past-chairmen" name="nav_past-chairmen" value="{{ old('nav_past-chairmen', $nav_past_chairmen ?? 'Past Chairmen') }}" required>@error('nav_past-chairmen')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_past-mds" class="form-label">Past MDs <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_past-mds') is-invalid @enderror" id="nav_past-mds" name="nav_past-mds" value="{{ old('nav_past-mds', $nav_past_mds ?? 'Past Mds') }}" required>@error('nav_past-mds')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_contact_us" class="form-label">Contact Us <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_contact_us') is-invalid @enderror" id="nav_contact_us" name="nav_contact_us" value="{{ old('nav_contact_us', $nav_contact_us ?? 'Contact Us') }}" required>@error('nav_contact_us')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        </div>
                    </div>
                </div>

                {{-- "Training" Dropdown Card --}}
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h6 class="mb-0">"Training" Dropdown</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6"><label for="nav_upcomming_training" class="form-label">Upcoming Training <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_upcomming_training') is-invalid @enderror" id="nav_upcomming_training" name="nav_upcomming_training" value="{{ old('nav_upcomming_training', $nav_upcomming_training ?? 'Upcoming Training') }}" required>@error('nav_upcomming_training')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-6"><label for="nav_all_training" class="form-label">All Training <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_all_training') is-invalid @enderror" id="nav_all_training" name="nav_all_training" value="{{ old('nav_all_training', $nav_all_training ?? 'All Training') }}" required>@error('nav_all_training')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        </div>
                    </div>
                </div>

                {{-- "Resources" Dropdown Card --}}
                <div class="card mb-4">
                    <div class="card-header card-header-custom">
                        <h6 class="mb-0">"Resources" Dropdown</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4"><label for="nav_career" class="form-label">Career <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_career') is-invalid @enderror" id="nav_career" name="nav_career" value="{{ old('nav_career', $nav_career ?? 'Career') }}" required>@error('nav_career')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_publication" class="form-label">Publication <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_publication') is-invalid @enderror" id="nav_publication" name="nav_publication" value="{{ old('nav_publication', $nav_publication ?? 'Publication') }}" required>@error('nav_publication')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_press-release" class="form-label">Press Release <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_press-release') is-invalid @enderror" id="nav_press-release" name="nav_press-release" value="{{ old('nav_press-release', $nav_press_release ?? 'Press Release') }}" required>@error('nav_press-release')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_events" class="form-label">Event <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_events') is-invalid @enderror" id="nav_events" name="nav_events" value="{{ old('nav_events', $nav_events ?? 'Event') }}" required>@error('nav_events')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_gallery" class="form-label">Gallery <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_gallery') is-invalid @enderror" id="nav_gallery" name="nav_gallery" value="{{ old('nav_gallery', $nav_gallery ?? 'Gallery') }}" required>@error('nav_gallery')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label for="nav_download" class="form-label">Download <span class="text-danger">*</span></label><input type="text" class="form-control @error('nav_download') is-invalid @enderror" id="nav_download" name="nav_download" value="{{ old('nav_download', $nav_download ?? 'Download') }}" required>@error('nav_download')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i data-feather="save" class="me-1" style="width:16px;"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection