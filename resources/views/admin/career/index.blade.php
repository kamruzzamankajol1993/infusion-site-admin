@extends('admin.master.master')

@section('title')
IIFC Careers | {{ $ins_name }}
@endsection

@section('css')
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                {{-- <li class="breadcrumb-item">Section</li> --}} {{-- Add parent if needed --}}
                <li class="breadcrumb-item active" aria-current="page">Careers</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('careerAdd')) {{-- Assuming permission --}}
            <a href="{{ route('career.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Career Post
            </a>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Career Postings</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInputC" type="search" placeholder="Search careers..." aria-label="Search"> {{-- Unique ID --}}
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th class="sortableC" data-column="title" style="width: 25%;">Title</th> {{-- Unique class --}}
                            <th class="sortableC" data-column="position" style="width: 20%;">Position</th> {{-- Unique class --}}
                            <th class="sortableC" data-column="job_location" style="width: 15%;">Location</th> {{-- Unique class --}}
                            <th class="sortableC" data-column="application_deadline" style="width: 15%;">Deadline</th> {{-- Unique class --}}
                            <th style="width: 20%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyC"> {{-- Unique ID --}}
                        {{-- AJAX data here --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            <div class="text-muted" id="tableRowCountC"></div> {{-- Unique ID --}}
            <nav>
                <ul class="pagination justify-content-center mb-0" id="paginationC"></ul> {{-- Unique ID --}}
            </nav>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.career._partial.script')
@endsection