@extends('admin.master.master')

@section('title')
Project List | {{ $ins_name }}
@endsection

@section('css')
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row --}}
    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Projects</li>
                <li class="breadcrumb-item active" aria-current="page">All Projects</li>
            </ol>
        </nav>
        {{-- MODIFIED THIS DIV --}}
        <div class="d-flex flex-wrap gap-2">
            @if (Auth::user()->can('projectAdd'))
            <a href="{{ route('project.createImport') }}" class="btn btn-outline-primary" style="white-space: nowrap;">
                <i data-feather="upload" class="me-1" style="width:18px; height:18px;"></i> Import Projects
            </a>
            <a href="{{ route('project.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Project
            </a>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Project List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInput" type="search" placeholder="Search projects..." aria-label="Search">
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th class="sortable" data-column="title" style="width: 25%;">Title</th>
                            <th style="width: 15%;">Category</th>
                            <th style="width: 15%;">Client</th>
                            <th style="width: 10%;">Country</th>
                            <th class="sortable" data-column="status" style="width: 10%;">Status</th>
                            <th class="sortable" data-column="agreement_signing_date" style="width: 10%;">Sign Date</th>
                            <th style="width: 10%;">Action</th>
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
    <form id="delete-project-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    {{-- ==== END HIDDEN DELETE FORM ==== --}}
@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.project._partial.script')
@endsection