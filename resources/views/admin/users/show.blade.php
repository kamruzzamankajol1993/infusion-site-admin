@extends('admin.master.master')

@section('title')
User Details | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Profile Card Styles */
    .profile-card {
        text-align: center;
        padding: 2rem;
        border-right: 1px solid #eee;
    }
    .profile-card .profile-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 1rem;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .profile-card .profile-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .profile-card .profile-designation {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    
    /* Profile Details List */
    .profile-details-list .list-group-item {
        border: 0;
        padding: 0.75rem 0.25rem;
        display: flex;
        align-items: center;
    }
    .profile-details-list .list-icon {
        width: 30px;
        font-size: 1.2rem;
        color: var(--primary-color);
        margin-right: 1rem;
    }
    .profile-details-list .list-label {
        font-weight: 600;
        color: #343a40;
        min-width: 120px;
    }
    .profile-details-list .list-value {
        color: #495057;
    }
    .signature-img {
        height: 40px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
    }
</style>
@endsection

@section('body')
  <div class="container-fluid px-4 py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </nav>
        <div class="d-flex justify-content-end align-items-center mb-4">
           
            <a href="{{ route('users.index') }}" class="btn btn-outline-primary">
                <i class="fa fa-arrow-left me-1"></i> Back to User List
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-lg-4 col-md-5">
                        <div class="profile-card">
                            @if(empty($user->image))
                                <img src="{{asset('/')}}public/No_Image_Available.jpg" class="profile-img" alt="User Image"/>
                            @else
                                <img src="{{ asset('/') }}{{ $user->image }}" class="profile-img" alt="User Image"/>
                            @endif
                            
                            <h4 class="profile-name">{{ $user->name }}</h4>
                            <p class="profile-designation">{{ $user->designation ? $user->designation->name : 'N/A' }}</p>
                            
                            <span class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-danger' }} fs-6">
                                {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div class="p-4">
                            <ul class="nav nav-tabs mb-4" id="userTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                                        <i class="bi bi-person-lines-fill me-1"></i> Profile Info
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                                        <i class="bi bi-shield-lock-fill me-1"></i> Security
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="userTabContent">
                                <div class="tab-pane fade show active" id="profile" role="tabpanel">
                                    <h5 class="mb-3">Contact & Job Details</h5>
                                    <ul class="list-group list-group-flush profile-details-list">
                                        <li class="list-group-item">
                                            <i class="bi bi-envelope-fill list-icon"></i>
                                            <span class="list-label">Email:</span>
                                            <span class="list-value">{{ $user->email }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="bi bi-phone-fill list-icon"></i>
                                            <span class="list-label">Phone:</span>
                                            <span class="list-value">{{ $user->phone ?? 'N/A' }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="bi bi-building list-icon"></i>
                                            <span class="list-label">Department:</span>
                                            <span class="list-value">{{ $user->department ? $user->department->name : 'N/A' }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="bi bi-geo-alt-fill list-icon"></i>
                                            <span class="list-label">Address:</span>
                                            <span class="list-value">{{ $user->address ?? 'N/A' }}</span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="bi bi-patch-check-fill list-icon"></i>
                                            <span class="list-label">Email Verified:</span>
                                            <span class="list-value">
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">Verified</span>
                                                @else
                                                    <span class="badge bg-secondary">Not Verified</span>
                                                @endif
                                            </span>
                                        </li>
                                        <li class="list-group-item">
                                            <i class="bi bi-pen-fill list-icon"></i>
                                            <span class="list-label">Signature:</span>
                                            <span class="list-value">
                                                @if($user->signature)
                                                    <img src="{{ asset($user->signature) }}" class="signature-img" alt="Signature"/>
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="security" role="tabpanel">
                                    <h5 class="mb-3">Security Details</h5>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold"><i class="bi bi-key-fill me-1 text-muted"></i>Visible Password</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" value="{{ $user->viewpassword }}" readonly>
                                            <span class="input-group-text bg-light">
                                                <i class="bi bi-eye-slash-fill"></i>
                                            </span>
                                        </div>
                                        <small class="text-muted"><i class="bi bi-exclamation-triangle-fill text-warning"></i> This is the user's visible password for reference.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
{{-- We can keep this include if 'script.blade.php' has other scripts, like delete confirmation --}}
@include('admin.users._partial.script')
@endsection