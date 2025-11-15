@extends('admin.master.master')

@section('title')
Download List | {{ $ins_name }}
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
                <li class="breadcrumb-item active" aria-current="page">All Downloads</li>
            </ol>
        </nav>
        <div>
            {{-- 1. Check new permission --}}
            @if (Auth::user()->can('downloadAdd'))
            {{-- 2. Point to new route --}}
            <a href="{{ route('download.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Download
            </a>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Download List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                {{-- 3. Use unique ID --}}
                <input class="form-control" id="searchInputD" type="search" placeholder="Search downloads..." aria-label="Search">
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            {{-- 4. Use unique class --}}
                            <th class="sortableD" data-column="title" style="width: 50%;">Title</th>
                            <th class="sortableD" data-column="date" style="width: 20%;">Date</th>
                            <th style="width: 25%;">Action</th>
                        </tr>
                    </thead>
                    {{-- 5. Use unique ID --}}
                    <tbody id="tableBodyD">
                        {{-- AJAX data here --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            {{-- 6. Use unique ID --}}
            <div class="text-muted" id="tableRowCountD"></div>
            <nav>
                {{-- 7. Use unique ID --}}
                <ul class="pagination justify-content-center mb-0" id="paginationD"></ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- 8. Include new script file --}}
@include('admin.download._partial.script')
@endsection