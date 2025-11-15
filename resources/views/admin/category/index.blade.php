@extends('admin.master.master')

@section('title')
Category Management | {{ $ins_name }}
@endsection

@section('css')
 <style>
        /* --- Custom Searchable Select --- */
        .custom-select-container {
            position: relative;
        }
        .custom-select-control {
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.375rem;
            min-height: 38px;
            cursor: pointer;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.25rem;
        }
        .custom-select-placeholder {
            color: #6c757d;
            padding-left: 0.375rem;
        }
        .custom-select-pill {
            background-color: #0d6efd;
            color: white;
            padding: 0.25em 0.6em;
            font-size: 85%;
            border-radius: 0.375rem;
            display: inline-flex;
            align-items: center;
        }
        .custom-select-pill .remove-pill {
            margin-left: 0.5em;
            cursor: pointer;
            font-weight: bold;
        }
        .custom-select-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 0.375rem 0.375rem;
            z-index: 1056; /* Higher than Bootstrap modal's z-index */
        }
        .custom-select-search {
            width: 100%;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px solid #dee2e6;
        }
        .custom-select-options {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 200px;
            overflow-y: auto;
        }
        .custom-select-options li {
            padding: 0.5rem;
        }
        .custom-select-options li:hover {
            background-color: #f8f9fa;
        }
        .custom-select-options .form-check-label {
            cursor: pointer;
            width: 100%;
        }
        .custom-select-pill .remove-pill {
            margin-left: 0.5em;
            cursor: pointer;
            font-weight: bold;
        }

        /* 👇 ADD THESE NEW STYLES BELOW 👇 */
        .custom-select-header {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.5rem;
            border-bottom: 1px solid #dee2e6;
        }
        .custom-select-search {
            flex-grow: 1;
            border: none;
            padding: 0.375rem;
        }
        .custom-select-search:focus {
            outline: none;
            box-shadow: none;
        }
        .close-dropdown-btn {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            padding: 0 0.5rem;
            color: #6c757d;
        }
        .close-dropdown-btn:hover {
            color: #212529;
        }
    </style>
@endsection

@section('body')
<main class="main-content">
     <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h2 class="mb-0">Category List</h2>
            <div class="d-flex align-items-center">
                {{-- 👇 NEW: Delete Selected Button --}}
                <button id="deleteSelectedBtn" class="btn btn-danger me-2" style="display: none;">
                    <i data-feather="trash-2" class="me-1" style="width:18px;"></i> Delete Selected
                </button>

                <form class="d-flex me-2" role="search">
                    <input class="form-control" id="searchInput" type="search" placeholder="Search categories..." aria-label="Search">
                </form>
                <a type="button" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                    <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Category
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @include('flash_message')
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
    <thead>
        <tr>
              <th style="width: 40px;"><input class="form-check-input" type="checkbox" id="selectAllCheckbox"></th>
            <th>Sl</th>
            <th>Image</th>
            <th class="sortable" data-column="name">Category Name</th>
            <th>Parent Category</th>
              <th>Featured</th>
            <th class="sortable" data-column="status">Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="tableBody"></tbody>
</table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <div class="text-muted"></div>
                <nav>
                    <ul class="pagination justify-content-center" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</main>

@include('admin.category._partial.addModal')
@include('admin.category._partial.editModal')
@endsection

@section('script')

@include('admin.category._partial.script')
@endsection
