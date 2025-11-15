@extends('admin.master.master')

@section('title')
Client Logos | {{ $ins_name }}
@endsection

@section('css')
{{-- Add specific CSS if needed --}}
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- ... (Header Row: Breadcrumb and Add Button remains the same) ... --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Frontend</li> {{-- Adjust parent if needed --}}
                <li class="breadcrumb-item active" aria-current="page">Client Logos</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('clientAdd')) {{-- Assuming permission --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#addClientModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Client
            </button>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        {{-- ... (Card Header remains the same) ... --}}
        <div class="card-body">
            @include('flash_message')
            {{-- !! ADD THIS SECTION FOR SEARCH !! --}}
            <div class="d-flex justify-content-end mb-3">
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search by name..." aria-label="Search by name">
                    <span class="input-group-text"><i data-feather="search" style="width:16px; height:16px;"></i></span>
                </div>
            </div>
            {{-- !! END OF SEARCH SECTION !! --}}
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th style="width: 20%;">Logo</th>
                            {{-- Width updated --}}
                            <th class="sortable" data-column="name" style="width: 50%;">Client Name</th>
                            {{-- New Column --}}
                            <th class="sortable" data-column="image_shape" style="width: 10%;">Shape</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        {{-- Rows will be loaded via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- !! ADD THIS SECTION !! --}}
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted" id="tableRowCount">
                {{-- JS will populate this --}}
            </div>
            <nav>
                <ul class="pagination mb-0" id="pagination">
                    {{-- JS will populate this --}}
                </ul>
            </nav>
        </div>
        {{-- !! END OF ADDED SECTION !! --}}

    </div>
</div>
{{-- ==== ADD THIS HIDDEN DELETE FORM ==== --}}
    <form id="delete-client-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    {{-- ==== END HIDDEN DELETE FORM ==== --}}
{{-- Include Modals --}}
@include('admin.client._partial.addModal')
@include('admin.client._partial.editModal')

@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.client._partial.script')
@endsection