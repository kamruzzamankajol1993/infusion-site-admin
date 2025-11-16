@extends('admin.master.master')

@section('title')
Team Members | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Style for the drag-and-drop reorder list */
    #sortableList {
        list-style-type: none;
        padding: 0;
        max-width: 800px;
        margin: auto;
    }
    #sortableList li {
        padding: 10px 15px;
        margin: 5px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    #sortableList li:hover {
        background-color: #f9f9f9;
    }
    .sortable-item-content {
        display: flex;
        align-items: center;
    }
    .sortable-item-content img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }
    .sortable-handle {
        cursor: move;
        font-size: 1.2rem;
        color: #aaa;
        margin-right: 15px;
    }
    .sortable-handle:hover {
        color: #333;
    }
    /* Helper class from SortableJS for the item being dragged */
    .sortable-ghost {
        opacity: 0.4;
        background: #e6f7ff;
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
                <li class="breadcrumb-item active" aria-current="page">Team</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('teamAdd')) {{-- Permission Check --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#addTeamModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Member
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
                    <a class="nav-link {{ $activeTab === 'table' ? 'active' : '' }}" href="{{ route('team.index', ['tab' => 'table']) }}" id="table-tab">
                        <i data-feather="list" class="me-1" style="width:16px;"></i> Data Table
                    </a>
                </li>
                @if (Auth::user()->can('teamUpdate')) {{-- Only show reorder tab if user can update --}}
                <li class="nav-item" role="presentation">
                     <a class="nav-link {{ $activeTab === 'reorder' ? 'active' : '' }}" href="{{ route('team.index', ['tab' => 'reorder']) }}" id="reorder-tab">
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
                    {{-- Search --}}
                    <div class="d-flex justify-content-end mb-3">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search by name/designation..." aria-label="Search">
                            <span class="input-group-text"><i data-feather="search" style="width:16px; height:16px;"></i></span>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">Sl</th>
                                    <th style="width: 10%;">Image</th>
                                    <th class="sortable" data-column="name" style="width: 30%;">Name</th>
                                    <th class="sortable" data-column="designation" style="width: 30%;">Designation</th>
                                    <th class="sortable" data-column="order" style="width: 10%;">Order</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                {{-- Rows will be loaded via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination & Row Count --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3">
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

                {{-- ====== Tab 2: Drag & Drop Reorder ====== --}}
                <div class="tab-pane fade {{ $activeTab === 'reorder' ? 'show active' : '' }}" id="reorder-tab-pane" role="tabpanel" aria-labelledby="reorder-tab">
                    @if($activeTab === 'reorder')
                        @if($teams->isEmpty())
                            <div class="alert alert-info text-center">No team members found to reorder. Please add members first.</div>
                        @else
                            <div class="alert alert-primary d-flex align-items-center" role="alert">
                               <i data-feather="info" class="me-2"></i>
                               <div>
                                  Drag and drop the items to change their order, then click "Save Order".
                               </div>
                            </div>

                            <ul id="sortableList">
                                @foreach($teams as $team)
                                <li data-id="{{ $team->id }}">
                                    <div class="sortable-item-content">
                                        <span class="sortable-handle"><i class="bi bi-grip-vertical"></i></span>
                                        <img src="{{ $team->image ? asset($team->image) : asset('path/to/default-avatar.png') }}" alt="{{ $team->name }}">
                                        <div>
                                            <strong>{{ $team->name }}</strong>
                                            <div class="small text-muted">{{ $team->designation }}</div>
                                        </div>
                                    </div>
                                    <span class="badge bg-secondary rounded-pill">Order: {{ $team->order }}</span>
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
    <form id="delete-team-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    
{{-- Include Modals --}}
@include('admin.team._partial.addModal')
@include('admin.team._partial.editModal')

@endsection

@section('script')
{{-- Include SortableJS library (only needed for this page) --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

{{-- Include the AJAX table script --}}
@include('admin.team._partial.script')
@endsection