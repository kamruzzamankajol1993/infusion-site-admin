@extends('admin.master.master')

@section('title')
Important Links | {{ $ins_name ?? 'Admin Panel' }}
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Important Links</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('importantLinkAdd'))
            <button type="button" class="btn text-white" data-bs-toggle="modal" data-bs-target="#addLinkModal" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Link
            </button>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Important Link List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInput" type="search" placeholder="Search links..." aria-label="Search">
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th class="sortable" data-column="title" style="width: 40%;">Title</th>
                            <th class="sortable" data-column="link" style="width: 40%;">Link</th>
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

{{-- Include Modals --}}
@include('admin.important_link._partial.addModal')
@include('admin.important_link._partial.editModal')

@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.important_link._partial.script')
@endsection