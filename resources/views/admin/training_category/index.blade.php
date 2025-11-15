@extends('admin.master.master')

@section('title')
Training Categories | {{ $ins_name }}
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
                <li class="breadcrumb-item">Training</li> {{-- Assuming parent --}}
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('trainingCategoryAdd')) {{-- Assuming permission --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#addCategoryModalTC" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Category
            </button>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Training Category List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInputTC" type="search" placeholder="Search categories..." aria-label="Search">
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th style="width: 25%;">Image</th>
                            <th class="sortableTC" data-column="name" style="width: 55%;">Category Name</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyTC">
                        {{-- Rows will be loaded via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            <div class="text-muted" id="tableRowCountTC"></div>
            <nav>
                <ul class="pagination justify-content-center mb-0" id="paginationTC"></ul>
            </nav>
        </div>
    </div>
</div>

{{-- Include Modals --}}
@include('admin.training_category._partial.addModal')
@include('admin.training_category._partial.editModal')

@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.training_category._partial.script')
@endsection