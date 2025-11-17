@extends('admin.master.master')

@section('title')
Categories | {{ $ins_name }}
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
                <li class="breadcrumb-item">Ecommerce</li>
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('categoryAdd'))
            <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Category
            </button>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
             <h5 class="card-title mb-0">All Categories</h5>
        </div>
        <div class="card-body">
            @include('flash_message')
            
            {{-- Search --}}
            <div class="d-flex justify-content-end mb-3">
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search by name..." aria-label="Search">
                    <span class="input-group-text"><i data-feather="search" style="width:16px; height:16px;"></i></span>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th class="sortable" data-column="name" style="width: 25%;">Name</th>
                            <th class="sortable" data-column="slug" style="width: 25%;">Slug</th>
                            <th class="sortable" data-column="parent" style="width: 20%;">Parent Category</th>
                            <th class="sortable" data-column="status" style="width: 10%;">Status</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        {{-- Rows will be loaded via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination & Row Count --}}
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
    </div>
</div>

{{-- Hidden Delete Form --}}
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
    
{{-- Include Modals --}}
@include('admin.category._partial.addModal')
@include('admin.category._partial.editModal')

@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.category._partial.script')
@endsection