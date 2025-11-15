@extends('admin.master.master')

@section('title')
Training List | {{ $ins_name }}
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
                <li class="breadcrumb-item">Training</li>
                <li class="breadcrumb-item active" aria-current="page">All Trainings</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('trainingAdd'))
            <a href="{{ route('training.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Training
            </a>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Training List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInputT" type="search" placeholder="Search trainings..." aria-label="Search"> {{-- Unique ID --}}
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th style="width: 15%;">Image</th>
                            <th class="sortableT" data-column="title" style="width: 30%;">Title</th> {{-- Width adjusted --}}
                            {{-- Category Column Removed --}}
                            <th class="sortableT" data-column="status" style="width: 10%;">Status</th>
                            <th class="sortableT" data-column="start_date" style="width: 15%;">Start Date</th>
                            <th class="sortableT" data-column="training_fee" style="width: 10%;">Fee</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodyT"> {{-- Unique ID --}}
                        {{-- AJAX data here --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            <div class="text-muted" id="tableRowCountT"></div> {{-- Unique ID --}}
            <nav>
                <ul class="pagination justify-content-center mb-0" id="paginationT"></ul> {{-- Unique ID --}}
            </nav>
        </div>
    </div>
</div>
{{-- ==== ADD THIS HIDDEN DELETE FORM ==== --}}
    <form id="delete-training-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    {{-- ==== END HIDDEN DELETE FORM ==== --}}
@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.training._partial.script')
@endsection