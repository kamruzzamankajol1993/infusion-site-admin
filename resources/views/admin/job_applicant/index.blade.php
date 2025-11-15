@extends('admin.master.master')

@section('title')
Job Applicant List | {{ $ins_name }}
@endsection

@section('css')
{{-- Add any necessary CSS here --}}
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Careers</li> {{-- Parent --}}
                <li class="breadcrumb-item active" aria-current="page">Job Applicants</li>
            </ol>
        </nav>
        {{-- No Add button needed --}}
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Job Applicant List</h5>
            
            {{-- Career Filter Dropdown (UNCOMMENTED AND STYLED) --}}
            <div class="d-flex align-items-center flex-grow-1 flex-md-grow-0 me-auto">
                 <label for="jobFilter" class="form-label me-2 mb-0 small text-nowrap">Filter by Job:</label>
                 <select id="jobFilter" class="form-select form-select-sm" style="min-width: 150px;">
                    <option value="all" selected>All Jobs</option>
                    @foreach($jobTitles as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                 </select>
            </div>
            
            {{-- NEW: DOWNLOAD BUTTONS --}}
            <div class="btn-group me-md-3">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Download Export">
                    <i class="fas fa-file-download"></i> Export
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="javascript:void(0)" id="downloadExcelBtnJA"><i class="far fa-file-excel me-2"></i> Excel (.xlsx)</a></li>
                    <li><a class="dropdown-item" href="javascript:void(0)" id="downloadPdfBtnJA"><i class="far fa-file-pdf me-2"></i> PDF (.pdf)</a></li>
                </ul>
            </div>
            {{-- END NEW DOWNLOAD BUTTONS --}}

            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInputJA" type="search" placeholder="Search applicants..." aria-label="Search"> {{-- Unique ID --}}
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
        <th style="width: 5%;">Sl</th>
        <th class="sortableJA" data-column="full_name" style="width: 15%;">Full Name</th>
        <th style="width: 15%;">Applied For</th> {{-- Moved earlier --}}
        <th class="sortableJA" data-column="email" style="width: 15%;">Email</th>
        <th class="sortableJA" data-column="phone_number" style="width: 10%;">Phone</th>
        <th class="sortableJA" data-column="date_of_birth" style="width: 10%;">Date of Birth</th> {{-- NEW --}}
        {{-- Removed Qualification/Edu Background for brevity, add back if needed --}}
        <th class="sortableJA" data-column="created_at" style="width: 10%;">Applied On</th>
        <th style="width: 10%;">Action</th>
    </tr>
                    </thead>
                    <tbody id="tableBodyJA"> {{-- Unique ID --}}
                        {{-- AJAX data here --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            <div class="text-muted" id="tableRowCountJA"></div> {{-- Unique ID --}}
            <nav>
                <ul class="pagination justify-content-center mb-0" id="paginationJA"></ul> {{-- Unique ID --}}
            </nav>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.job_applicant._partial.script')
@endsection