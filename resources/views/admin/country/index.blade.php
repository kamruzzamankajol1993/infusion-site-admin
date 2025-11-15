@extends('admin.master.master')

@section('title')
Country List | {{ $ins_name }}
@endsection

@section('css')
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row: Breadcrumb and Add Button --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                {{-- Adjust breadcrumb parent if needed --}}
                <li class="breadcrumb-item">Frontend</li>
                <li class="breadcrumb-item active" aria-current="page">Country List</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('countryAdd')) {{-- Assuming permission --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#addCountryModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Country
            </button>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Country List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInput" type="search" placeholder="Search countries..." aria-label="Search">
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            {{-- MODIFIED: Width adjusted --}}
                            <th class="sortable" data-column="name" style="width: 50%;">Country Name</th>
                            {{-- ADDED: New Column --}}
                            <th class="sortable" data-column="iso3" style="width: 15%;">ISO3</th>
                            <th class="sortable" data-column="status" style="width: 15%;">Status</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        {{-- Rows will be loaded via AJAX --}}
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
    <form id="delete-country-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    {{-- ==== END HIDDEN DELETE FORM ==== --}}
{{-- Include Modals --}}
@include('admin.country._partial.addModal')
@include('admin.country._partial.editModal')

@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.country._partial.script')
@endsection