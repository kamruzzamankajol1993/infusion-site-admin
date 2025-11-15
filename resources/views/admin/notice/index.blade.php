@extends('admin.master.master')

@section('title')
Notice List | {{ $ins_name }}
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
                <li class="breadcrumb-item">Notice</li>
                <li class="breadcrumb-item active" aria-current="page">All Notices</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('noticeAdd'))
            <a href="{{ route('notice.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Notice
            </a>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Notice List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInputN" type="search" placeholder="Search notices..." aria-label="Search"> {{-- Unique ID --}}
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th class="sortableN" data-column="title" style="width: 40%;">Title</th> {{-- Unique class --}}
                            <th style="width: 25%;">Category</th>
                            <th class="sortableN" data-column="date" style="width: 15%;">Date</th> {{-- Unique class --}}
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyN"> {{-- Unique ID --}}
                        {{-- AJAX data here --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            <div class="text-muted" id="tableRowCountN"></div> {{-- Unique ID --}}
            <nav>
                <ul class="pagination justify-content-center mb-0" id="paginationN"></ul> {{-- Unique ID --}}
            </nav>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.notice._partial.script')
@endsection