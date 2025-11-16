@extends('admin.master.master')

@section('title')
Media (YouTube) | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Style for the drag-and-drop reorder list */
    #sortableList { list-style-type: none; padding: 0; max-width: 800px; margin: auto; }
    #sortableList li {
        padding: 10px 15px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px;
        background-color: #fff; display: flex; align-items: center; justify-content: space-between;
    }
    .sortable-item-content { display: flex; align-items: center; width: 80%; }
    .sortable-handle { cursor: move; font-size: 1.2rem; color: #aaa; margin-right: 15px; }
    .sortable-ghost { opacity: 0.4; background: #e6f7ff; }

    /* iframe preview styles */
    .video-preview-table {
        width: 160px; /* 16:9 aspect ratio */
        height: 90px;
        border: none;
        border-radius: 4px;
    }
    .video-preview-reorder {
        width: 128px; /* 16:9 */
        height: 72px;
        border: none;
        border-radius: 4px;
        margin-right: 15px;
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row: Breadcrumb and Add Button --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Media (YouTube)</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('mediaAdd'))
            <button type="button" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Video
            </button>
            @endif
        </div>
    </div>

    @include('flash_message')

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link {{ $activeTab === 'table' ? 'active' : '' }}" href="{{ route('media.index', ['tab' => 'table']) }}" id="table-tab">
                        <i data-feather="list" class="me-1" style="width:16px;"></i> Data Table
                    </a>
                </li>
                @if (Auth::user()->can('mediaUpdate'))
                <li class="nav-item" role="presentation">
                     <a class="nav-link {{ $activeTab === 'reorder' ? 'active' : '' }}" href="{{ route('media.index', ['tab' => 'reorder']) }}" id="reorder-tab">
                       <i data-feather="move" class="me-1" style="width:16px;"></i> Drag & Drop Reorder
                    </a>
                </li>
                @endif
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content" id="myTabContent">

                {{-- ====== Tab 1: Data Table ====== --}}
                <div class="tab-pane fade {{ $activeTab === 'table' ? 'show active' : '' }}" id="table-tab-pane" role="tabpanel" aria-labelledby="table-tab">
                    <div class="d-flex justify-content-end mb-3">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search by title..." aria-label="Search">
                            <span class="input-group-text"><i data-feather="search" style="width:16px; height:16px;"></i></span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">Sl</th>
                                    <th class="sortable" data-column="title" style="width: 35%;">Title</th>
                                    {{-- *** UPDATED HEADER *** --}}
                                    <th style="width: 35%;">Video Preview</th> 
                                    <th class="sortable" data-column="order" style="width: 10%;">Order</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                {{-- Rows will be loaded via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
                        <div class="text-muted" id="tableRowCount"></div>
                        <nav><ul class="pagination mb-0" id="pagination"></ul></nav>
                    </div>
                </div>

                {{-- ====== Tab 2: Drag & Drop Reorder ====== --}}
                <div class="tab-pane fade {{ $activeTab === 'reorder' ? 'show active' : '' }}" id="reorder-tab-pane" role="tabpanel" aria-labelledby="reorder-tab">
                    @if($activeTab === 'reorder')
                        @if($items->isEmpty())
                            <div class="alert alert-info text-center">No media items found to reorder. Please add items first.</div>
                        @else
                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                               <i data-feather="info" class="me-2"></i>
                               <div>Drag and drop items to change their order, then click "Save Order".</div>
                            </div>

                            <ul id="sortableList">
                                {{-- *** UPDATED LOOP *** --}}
                                @foreach($items as $item)
                                <li data-id="{{ $item->id }}">
                                    <div class="sortable-item-content">
                                        <span class="sortable-handle"><i class="bi bi-grip-vertical"></i></span>
                                        @if($item->video_id)
                                        <iframe class="video-preview-reorder"
                                            src="https://www.youtube.com/embed/{{ $item->video_id }}" 
                                            title="{{ $item->title }}" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                        </iframe>
                                        @endif
                                        <div>
                                            <strong>{{ $item->title }}</strong>
                                            <div class="small text-muted" style="word-break: break-all;">{{ $item->youtube_link }}</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill">Order: {{ $item->order }}</span>
                                </li>
                                @endforeach
                            </ul>
                            <div class="text-end mt-4">
                                <button class="btn btn-primary" id="saveOrderBtn">
                                    <i data-feather="save" class="me-1" style="width:18px;"></i> Save Order
                                </button>
                            </div>
                        @endif
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
{{-- Hidden Delete Form --}}
    <form id="delete-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    
{{-- Include Modals --}}
@include('admin.media._partial.addModal')
@include('admin.media._partial.editModal')

@endsection

@section('script')
{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

{{-- Include the AJAX table script --}}
@include('admin.media._partial.script')
@endsection