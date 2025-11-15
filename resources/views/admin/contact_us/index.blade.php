@extends('admin.master.master')

@section('title')
Contact Us Messages | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Optional: Style for delete button */
    .btn-delete-selected {
        margin-left: 10px; /* Space between search and delete button */
    }
    .table th, .table td { /* Prevent text wrapping in actions */
        white-space: nowrap;
    }
    .table td:nth-child(3), /* Name */
    .table td:nth-child(4), /* Email */
    .table td:nth-child(5) { /* Mobile */
        white-space: normal; /* Allow wrapping for these */
        word-break: break-all;
    }

</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Contact Messages</li>
        </ol>
    </nav>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="card-title mb-0">Contact Message List</h5>

            <div class="d-flex flex-wrap gap-2">
                {{-- Search Form --}}
                <form class="d-flex" role="search" onsubmit="return false;">
                    <input class="form-control" id="searchInput" type="search" placeholder="Search messages..." aria-label="Search">
                </form>

                {{-- Delete Selected Button --}}
                @if (Auth::user()->can('contactUsDelete')) {{-- Assuming a permission --}}
                    <button class="btn btn-danger btn-delete-selected" id="deleteSelectedBtn" style="display: none;">
                        <i class="fa fa-trash me-1"></i> Delete Selected
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 3%;">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                            </th>
                            <th style="width: 5%;">Sl</th>
                            <th class="sortable" data-column="fullname" style="width: 20%;">Full Name</th>
                            <th class="sortable" data-column="email" style="width: 20%;">Email</th>
                            <th class="sortable" data-column="mobilenumber" style="width: 15%;">Mobile Number</th>
                            {{-- <th style="width: 30%;">Message</th> --}} {{-- <-- REMOVED --}}
                            <th class="sortable" data-column="created_at" style="width: 15%;">Received</th> {{-- Added Received Date --}}
                            <th style="width: 12%;">Action</th> {{-- Adjusted width --}}
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        {{-- Rows will be loaded via AJAX --}}
                        <tr><td colspan="7" class="text-center">Loading...</td></tr> {{-- <-- Colspan is now 7 --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer for Pagination --}}
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            <div class="text-muted" id="tableRowCount"></div>
            <nav>
                <ul class="pagination justify-content-center mb-0" id="pagination"></ul>
            </nav>
        </div>
    </div>
</div>

{{-- Include the View Modal --}}
@include('admin.contact_us._partial.viewModal')

@endsection

@section('script')
{{-- Include the script partial --}}
@include('admin.contact_us._partial.script')
@endsection