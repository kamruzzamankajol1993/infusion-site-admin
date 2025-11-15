@extends('admin.master.master')

@section('title')
Training Enrollments | {{ $ins_name }}
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Training Enrollments</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('trainingEnrollmentAdd'))
            <a href="{{ route('trainingEnrollment.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Enrollment
            </a>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Enrollment List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInput" type="search" placeholder="Search enrollments..." aria-label="Search">
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th class="sortable" data-column="name" style="width: 20%;">Name</th>
                            <th style="width: 25%;">Training</th>
                            <th style="width: 15%;">Email</th>
                            <th style="width: 10%;">Mobile</th>
                            <th class="sortable" data-column="status" style="width: 10%;">Status</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        {{-- AJAX data here --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            <div class="text-muted" id="tableRowCount"></div>
            <nav>
                <ul class="pagination justify-content-center mb-0" id="pagination"></ul>
            </nav>
        </div>
    </div>
</div>
{{-- ==== ADD THIS HIDDEN DELETE FORM ==== --}}
    <form id="delete-enrollment-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    {{-- ==== END HIDDEN DELETE FORM ==== --}}
@endsection

@section('script')
{{-- Create and include this partial file --}}
@include('admin.trainingEnrollment._partial.script')
@endsection