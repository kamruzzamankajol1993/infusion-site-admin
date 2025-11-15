@extends('admin.master.master')

@section('title')
Officer Category | {{ $ins_name }}
@endsection

@section('css') 
<style>
    /* This style connects the card content to the tabs visually */
    .tab-content .card {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        border-top: 0;
    }
</style>
@endsection

@section('body')
  <div class="container-fluid px-4 py-4">
                
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Settings</li>
                <li class="breadcrumb-item active" aria-current="page">Officer Category</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('officerCategoryAdd'))
            <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Officer Category
            </a>
            @endif
        </div>
    </div>
    
    <ul class="nav nav-tabs" id="categoryTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-tab-pane" type="button" role="tab" aria-controls="list-tab-pane" aria-selected="true">
                <i class="fa fa-list me-1"></i> Category List
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="order-tab" data-bs-toggle="tab" data-bs-target="#order-tab-pane" type="button" role="tab" aria-controls="order-tab-pane" aria-selected="false">
                <i class="fa fa-sort me-1"></i> Order Categories
            </button>
        </li>
    </ul>

    <div class="tab-content" id="categoryTabContent">

        <div class="tab-pane fade show active" id="list-tab-pane" role="tabpanel" aria-labelledby="list-tab" tabindex="0">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="card-title mb-0">Officer Category List</h5>
                    <form class="d-flex" role="search">
                        <input class="form-control" id="searchInput" type="search" placeholder="Search officer categories..." aria-label="Search">
                    </form>
                </div>
                <div class="card-body">
                    @include('flash_message')
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th class="sortable" data-column="name">Category Name</th>
                                    <th>Parent Category</th> <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody"></tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <div class="text-muted"></div>
                    <nav>
                        <ul class="pagination justify-content-center mb-0" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="order-tab-pane" role="tabpanel" aria-labelledby="order-tab" tabindex="0">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Order Child Categories</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="parentCategorySelect" class="form-label">Select Parent Category:</label>
                            <select id="parentCategorySelect" class="form-select">
                                <option value="">-- Select a Parent --</option>
                                {{-- Use the $categories variable passed from the index method --}}
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
            
                    <div id="childCategoryContainer" style="min-height: 100px;"> {{-- Container for children --}}
                        <p id="childCategoryPlaceholder" class="text-muted">Select a parent category to view and order its children.</p>
                        <ul id="childCategoryList" class="list-group sortable-list">
                            {{-- Child categories will be loaded here via AJAX --}}
                        </ul>
                    </div>
            
                    <div class="mt-3">
                        <button id="saveOrderBtn" class="btn btn-primary btn-sm" disabled>
                            <i data-feather="save" class="me-1" style="width:16px; height:16px;"></i> Save Order
                        </button>
                         <span id="orderStatus" class="ms-2"></span> {{-- For loading/success/error messages --}}
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

{{-- Pass the categories list to the modals --}}
@include('admin.officerCategory._partial.editModal', ['categories' => $categories])
@include('admin.officerCategory._partial.addModal', ['categories' => $categories])

@endsection

@section('script')
@include('admin.officerCategory._partial.script')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
  $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

let sortableInstance = null; // To hold the Sortable instance

// --- Child Category Ordering Logic ---
$('#parentCategorySelect').on('change', function() {
    const parentId = $(this).val();
    const $childList = $('#childCategoryList');
    const $placeholder = $('#childCategoryPlaceholder');
    const $saveBtn = $('#saveOrderBtn');
    const $status = $('#orderStatus');

    $childList.empty(); // Clear previous children
    $saveBtn.prop('disabled', true); // Disable save button
    $status.empty(); // Clear status
    if (sortableInstance) {
        sortableInstance.destroy(); // Destroy previous sortable instance
        sortableInstance = null;
    }

    if (!parentId) {
        $placeholder.text('Select a parent category to view and order its children.').show();
        return;
    }

    $placeholder.html('<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> Loading children...').show();

    // AJAX call to get children
    $.get(`{{ url('admin/officerCategory') }}/${parentId}/children`) // Use url() helper for flexibility
        .done(function(children) {
            if (children && children.length > 0) {
                children.forEach(child => {
                    // Create list items with data-id attribute
                    $childList.append(`
                        <li class="list-group-item sortable-item" data-id="${child.id}">
                            <i class="fa fa-bars me-2 sortable-handle" style="cursor: move;"></i>
                            ${child.name}
                        </li>
                    `);
                });
                $placeholder.hide();
                $saveBtn.prop('disabled', false); // Enable save button

                // Initialize SortableJS *after* items are added
                const listElement = document.getElementById('childCategoryList');
                if (listElement) {
                    sortableInstance = new Sortable(listElement, {
                        animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
                        handle: '.sortable-handle', // Restrict drag start to the handle
                        ghostClass: 'sortable-ghost' // Class name for the drop placeholder
                    });
                }

            } else {
                $placeholder.text('This category has no children to order.').show();
            }
        })
        .fail(function(jqXHR) {
            console.error("Failed to fetch children:", jqXHR);
            $placeholder.html('<span class="text-danger">Failed to load children.</span>').show();
        });
});

// --- Save Order Button Click ---
$('#saveOrderBtn').on('click', function() {
    if (!sortableInstance) return;

    const $button = $(this);
    const $status = $('#orderStatus');
    const orderedIds = sortableInstance.toArray(); // Get array of data-id attributes in order

    $button.prop('disabled', true);
    $status.html('<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Saving...</span></div> Saving...');

    // AJAX call to save the order
    $.ajax({
        url: "{{ route('officerCategory.updateChildOrder') }}",
        method: 'POST',
        data: {
            _token: routes.csrf, // Use CSRF token from existing setup in script.blade.php
            orderedIds: orderedIds
        },
        success: function(response) {
            $status.html('<span class="text-success"><i class="fa fa-check"></i> ' + (response.message || 'Order saved!') + '</span>');
            // Re-enable button after a short delay
            setTimeout(() => {
                $button.prop('disabled', false);
                $status.empty();
                 // Optional: Reload main table data if order affects it significantly
                 // fetchData();
            }, 2000);
        },
        error: function(jqXHR) {
            console.error("Failed to save order:", jqXHR);
            const errorMsg = jqXHR.responseJSON?.error || 'Could not save order.';
            $status.html('<span class="text-danger"><i class="fa fa-times"></i> ' + errorMsg + '</span>');
            $button.prop('disabled', false); // Re-enable immediately on error
             // Optional: Keep error message displayed longer or require manual dismissal
             setTimeout(() => { $status.empty(); }, 5000);
        }
    });
});
</script>
@endsection