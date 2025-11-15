@extends('admin.master.master')

@section('title')
Profile | {{ $ins_name }}
@endsection

@section('css')
<style>
    .profile-details-list .list-group-item {
        border: none;
        padding: 0.75rem 0;
    }
    .profile-details-list .list-group-item strong {
        display: inline-block;
        width: 120px; /* Adjust width as needed */
        color: #555;
    }
</style>
@endsection

@section('body')

    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h2 class="mb-0">User Profile</h2>
            <a href="{{ route('profileSetting') }}" class="btn btn-primary">
                {{-- Use Feather icons if available, otherwise FontAwesome --}}
                <i data-feather="edit" class="me-1" style="width:18px; height:18px;"></i>
                {{-- <i class="fa fa-edit me-1"></i> --}}
                Edit Profile
            </a>
        </div>

        @php
            // --- UPDATED: Get Department name via relationship ---
            // Eager loading in the controller is better, but this works for a single view.
            // Ensure the relationship name matches your User model ('department').
             $departmentName = Auth::user()->department ? Auth::user()->department->name : 'N/A';
             // You can still get designation name this way if needed, or use a relationship too
             $designationName = DB::table('designations')->where('id', Auth::user()->designation_id)->value('name') ?? 'N/A';
        @endphp

        <div class="row">
            <div class="col-lg-4">
                <div class="card text-center">
                    <div class="card-body">
                        {{-- Use asset() helper correctly for images --}}
                        @if(empty(Auth::user()->image))
                            <img src="{{ asset('public/No_Image_Available.jpg') }}" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            {{-- Assuming Auth::user()->image stores the path like 'public/uploads/...' --}}
                            <img src="{{ asset(Auth::user()->image) }}" alt="{{ Auth::user()->name }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @endif
                        <h4 class="card-title mb-1">{{ Auth::user()->name }}</h4>
                        <p class="text-muted mb-1">{{ $designationName ?? 'N/A' }}</p>
                         <p class="text-muted small">Joined {{ Auth::user()->created_at->format("F d, Y") }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">About</h5>

                        <ul class="list-group profile-details-list">
                            <li class="list-group-item d-flex">
                                <strong>Full Name:</strong>
                                <span>{{ Auth::user()->name }}</span>
                            </li>
                            <li class="list-group-item d-flex">
                                <strong>Email:</strong>
                                <span>{{ Auth::user()->email }}</span>
                            </li>
                            <li class="list-group-item d-flex">
                                <strong>Phone:</strong>
                                <span>{{ Auth::user()->phone ?? 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex">
                                {{-- --- UPDATED: Use Department Name --- --}}
                                <strong>Department:</strong>
                                <span>{{ $departmentName }}</span>
                            </li>
                             <li class="list-group-item d-flex">
                                <strong>Address:</strong>
                                <span>{{ Auth::user()->address ?? 'N/A' }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
{{-- Add script if Feather icons need reinitialization --}}
{{-- <script> feather.replace(); </script> --}}
@endsection