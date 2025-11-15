@extends('admin.master.master')

@section('title')
Press Release List | {{ $ins_name }}
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
                <li class="breadcrumb-item">Frontend</li> {{-- Adjust parent if needed --}}
                <li class="breadcrumb-item active" aria-current="page">Press Releases</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('pressReleaseAdd'))
            <a href="{{ route('pressRelease.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Press Release
            </a>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Press Release List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInputPR" type="search" placeholder="Search press releases..." aria-label="Search"> {{-- Unique ID --}}
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th style="width: 15%;">Image</th>
                            <th class="sortablePR" data-column="title" style="width: 35%;">Title</th> {{-- Adjusted width --}}
                            <th class="sortablePR" data-column="release_date" style="width: 15%;">Release Date</th> {{-- <-- ADDED --}}
                            <th style="width: 15%;">Content Preview</th> {{-- Adjusted width --}}
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyPR"> {{-- Unique ID --}}
                        {{-- AJAX data here --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            <div class="text-muted" id="tableRowCountPR"></div> {{-- Unique ID --}}
            <nav>
                <ul class="pagination justify-content-center mb-0" id="paginationPR"></ul> {{-- Unique ID --}}
            </nav>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.press_release._partial.script')
@endsection