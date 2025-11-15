@extends('admin.master.master')

@section('title')
Role Management | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* New styles for the redesigned cards */
    .details-card .list-group-item {
        padding-left: 0;
        padding-right: 0;
        border: 0;
    }
    .permission-list-new .d-flex {
        transition: all 0.2s ease-in-out;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .permission-list-new .d-flex:hover {
        /* Using your theme's primary and secondary colors */
        background-color: #e9f5f0 !important; 
        border-color: #175A3A !important; 
        transform: translateY(-2px);
    }
</style>
@endsection

@section('body')

<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">General</li>
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">Role Detail</li>
        </ol>
    </nav>

    @include('flash_message')


        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4 details-card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Role Details</h5>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title text-primary mb-3">{{ $role->name }}</h4>
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-muted">Role ID:</strong>
                                <span>{{ $role->id }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-muted">Created At:</strong>
                                <span>{{ $role->created_at->format('d M, Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong class="text-muted">Last Updated:</strong>
                                <span>{{ $role->updated_at->format('d M, Y') }}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light">
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil-fill me-1"></i> Edit Role
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-check me-2"></i>Assigned Permissions
                        </h5>
                        <span class="badge bg-primary rounded-pill">
                            {{ $rolePermissions->count() }} Total
                        </span>
                    </div>
                    <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                        @if(!empty($rolePermissions) && count($rolePermissions) > 0)
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2 permission-list-new">
                                @foreach($rolePermissions as $v)
                                    <div class="col">
                                        <div class="d-flex align-items-center p-2 bg-light rounded border">
                                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                                            <span>{{ $v->name }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-secondary text-center mb-0">
                                <i class="bi bi-x-circle me-2"></i> No permissions have been assigned to this role.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
   
</div>
@endsection

@section('script')
@endsection