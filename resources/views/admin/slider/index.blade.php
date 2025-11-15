@extends('admin.master.master')

@section('title')
Slider List | {{ $ins_name }}
@endsection

@section('css')
{{-- 1. ADD STYLES FOR NEW REORDER LIST --}}
<style>
    /* Styles for the reorder list */
    #reorderTabPane .list-group-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.25rem;
        background-color: #fff;
        border: 1px solid rgba(0,0,0,.125);
        margin-bottom: -1px; /* Overlap borders */
    }
    #reorderTabPane .reorder-handle {
        cursor: move;
        font-size: 1.2rem;
        color: #888;
        margin-right: 15px;
    }
    #reorderTabPane .reorder-img {
        width: 80px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 15px;
    }
    #reorderTabPane .reorder-title {
        font-weight: 500;
    }
    /* Style for the placeholder */
    #reorderSliderList .ui-sortable-placeholder {
        background-color: #f0f0f0;
        visibility: visible !important;
        height: 70px !important; /* Match item height */
        border: 1px dashed #ccc;
        margin-bottom: -1px;
    }
    /* Style for the helper (the item being dragged) */
    #reorderSliderList .ui-sortable-helper {
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row (No changes) --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Home Page</li> {{-- Parent --}}
                <li class="breadcrumb-item active" aria-current="page">Sliders</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('sliderAdd'))
            <a href="{{ route('slider.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Slider
            </a>
            @endif
        </div>
    </div>

    {{-- 2. ADD BOOTSTRAP TAB NAVIGATION --}}
    <ul class="nav nav-tabs" id="sliderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#listTabPane" type="button" role="tab" aria-controls="listTabPane" aria-selected="true">
                <i data-feather="list" class="me-1" style="width:16px;"></i> View List
            </button>
        </li>
        @if (Auth::user()->can('sliderUpdate'))
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reorder-tab" data-bs-toggle="tab" data-bs-target="#reorderTabPane" type="button" role="tab" aria-controls="reorderTabPane" aria-selected="false">
                <i data-feather="move" class="me-1" style="width:16px;"></i> Reorder Sliders
            </button>
        </li>
        @endif
    </ul>

    {{-- 3. ADD TAB CONTENT WRAPPER --}}
    <div class="tab-content" id="sliderTabsContent">

        {{-- 4. MOVE EXISTING CARD INTO THE FIRST TAB PANE --}}
        <div class="tab-pane fade show active" id="listTabPane" role="tabpanel" aria-labelledby="list-tab" tabindex="0">
            
            {{-- Main Card --}}
            <div class="card shadow-sm" style="border-top-left-radius: 0;"> {{-- Removed top-left radius for seamless tab --}}
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="card-title mb-0">Slider List</h5>
                    <form class="d-flex" role="search" onsubmit="return false;">
                        <input class="form-control" id="searchInputS" type="search" placeholder="Search sliders..." aria-label="Search"> {{-- Unique ID --}}
                    </form>
                </div>
                <div class="card-body">
                    @include('flash_message')
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    {{-- Table is reverted to original (no drag handle) --}}
                                    <th style="width: 5%;">Sl</th>
                                    <th style="width: 20%;">Image</th>
                                    <th class="sortableS" data-column="title" style="width: 25%;">Title</th> {{-- Unique class --}}
                                    <th style="width: 35%;">Subtitle / Description</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyS"> {{-- Unique ID --}}
                                {{-- AJAX data here --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
                    <div class="text-muted" id="tableRowCountS"></div> {{-- Unique ID --}}
                    <nav>
                        <ul class="pagination justify-content-center mb-0" id="paginationS"></ul> {{-- Unique ID --}}
                    </nav>
                </div>
            </div>

        </div>

        {{-- 5. CREATE THE NEW REORDER TAB PANE --}}
        <div class="tab-pane fade" id="reorderTabPane" role="tabpanel" aria-labelledby="reorder-tab" tabindex="0">
            <div class="card shadow-sm" style="border-top-right-radius: 0;">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Reorder Sliders</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Drag and drop the sliders into your desired order. The order saves automatically.</p>
                    <div class="list-group" id="reorderSliderList">
                        {{-- AJAX content will be loaded here --}}
                        <div class="text-center py-5">
                            <span class="spinner-border" role="status" aria-hidden="true"></span>
                            <p>Loading sliders...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> {{-- End tab-content --}}
</div>
@endsection

@section('script')
{{-- jQuery UI must be included before the partial script --}}
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

{{-- Include the AJAX table script --}}
@include('admin.slider._partial.script')
@endsection