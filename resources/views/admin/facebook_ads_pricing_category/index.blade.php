@extends('admin.master.master')

@section('title')
FB Pricing Categories | {{ $ins_name }}
@endsection

@section('css')
<style>
    #sortableList { list-style-type: none; padding: 0; max-width: 800px; margin: auto; }
    #sortableList li { padding: 10px 15px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; background-color: #fff; display: flex; align-items: center; justify-content: space-between; }
    .sortable-handle { cursor: move; font-size: 1.2rem; color: #aaa; margin-right: 15px; }
    .sortable-ghost { opacity: 0.4; background: #e6f7ff; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Facebook Ads</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pricing Categories</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('facebookAdsPricingCategoryAdd'))
            <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Category
            </button>
            @endif
        </div>
    </div>
    @include('flash_message')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation"><a class="nav-link {{ $activeTab === 'table' ? 'active' : '' }}" href="{{ route('facebookAds.pricingCategory.index', ['tab' => 'table']) }}" id="table-tab"><i data-feather="list" class="me-1" style="width:16px;"></i> Data Table</a></li>
                @if (Auth::user()->can('facebookAdsPricingCategoryUpdate'))
                <li class="nav-item" role="presentation"><a class="nav-link {{ $activeTab === 'reorder' ? 'active' : '' }}" href="{{ route('facebookAds.pricingCategory.index', ['tab' => 'reorder']) }}" id="reorder-tab"><i data-feather="move" class="me-1" style="width:16px;"></i> Drag & Drop Reorder</a></li>
                @endif
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade {{ $activeTab === 'table' ? 'show active' : '' }}" id="table-tab-pane" role="tabpanel" aria-labelledby="table-tab">
                    <div class="d-flex justify-content-end mb-3"><div class="input-group" style="width: 250px;"><input type="text" class="form-control" id="searchInput" placeholder="Search by name..."><span class="input-group-text"><i data-feather="search" style="width:16px;"></i></span></div></div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">Sl</th>
                                    <th class="sortable" data-column="name" style="width: 70%;">Category Name</th>
                                    <th class="sortable" data-column="order" style="width: 10%;">Order</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                        <div class="text-muted" id="tableRowCount"></div>
                        <nav><ul class="pagination mb-0" id="pagination"></ul></nav>
                    </div>
                </div>
                <div class="tab-pane fade {{ $activeTab === 'reorder' ? 'show active' : '' }}" id="reorder-tab-pane" role="tabpanel" aria-labelledby="reorder-tab">
                    @if($activeTab === 'reorder' && !$items->isEmpty())
                        <div class="alert alert-primary d-flex align-items-center" role="alert"><i data-feather="info" class="me-2"></i><div>Drag and drop items to change their order, then click "Save Order".</div></div>
                        <ul id="sortableList">
                            @foreach($items as $item)
                            <li data-id="{{ $item->id }}">
                                <div><span class="sortable-handle"><i class="bi bi-grip-vertical"></i></span> <strong>{{ $item->name }}</strong></div>
                                <span class="badge bg-secondary rounded-pill">Order: {{ $item->order }}</span>
                            </li>
                            @endforeach
                        </ul>
                        <div class="text-end mt-4"><button class="btn btn-primary" id="saveOrderBtn"><i data-feather="save" class="me-1" style="width:18px;"></i> Save Order</button></div>
                    @else
                        <div class="alert alert-info text-center">No categories found to reorder.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<form id="delete-form" action="" method="POST" style="display: none;">@csrf @method('DELETE')</form>
@include('admin.facebook_ads_pricing_category._partial.addModal')
@include('admin.facebook_ads_pricing_category._partial.editModal')
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@include('admin.facebook_ads_pricing_category._partial.script')
@endsection