@extends('admin.master.master')
@section('title') Coupons | {{ $ins_name }} @endsection
@section('body')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Ecommerce</li>
                <li class="breadcrumb-item active">Coupons</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('couponAdd'))
            <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white" style="background-color: var(--primary-color);">
                <i data-feather="plus" style="width:18px;"></i> Add New Coupon
            </button>
            @endif
        </div>
    </div>
    @include('flash_message')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-end">
                <div class="input-group" style="width: 250px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search code...">
                    <span class="input-group-text"><i data-feather="search" style="width:16px;"></i></span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sl</th>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <div id="tableRowCount" class="text-muted"></div>
                <nav><ul class="pagination mb-0" id="pagination"></ul></nav>
            </div>
        </div>
    </div>
</div>
<form id="delete-form" action="" method="POST" style="display: none;">@csrf @method('DELETE')</form>
@include('admin.coupon._partial.addModal')
@include('admin.coupon._partial.editModal')
@endsection
@section('script')
@include('admin.coupon._partial.script')
@endsection