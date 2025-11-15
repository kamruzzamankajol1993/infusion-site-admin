@extends('admin.master.master')

@section('title')
Service List | {{ $ins_name }}
@endsection

@section('css')
{{-- Add styles for the drag-and-drop list --}}
<style>
    .reorder-list {
        list-style-type: none;
        padding: 0;
        max-width: 800px;
        margin: 0 auto;
    }
    .reorder-list li {
        padding: 12px 18px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        margin-bottom: 8px;
        border-radius: .375rem;
        display: flex;
        align-items: center;
        font-weight: 500;
        cursor: move;
    }
    .reorder-list li i {
        margin-right: 12px;
        color: #6c757d;
    }
    /* Style for the item being dragged */
    .sortable-ghost {
        opacity: 0.4;
        background: #cce5ff;
    }

    /* --- NEW STYLES FOR HOMEPAGE TAB --- */
    .homepage-lists-container {
        display: flex;
        gap: 2rem;
    }
    .list-box {
        flex: 1;
        padding: 1rem;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: .375rem;
    }
    .list-box h5 {
        border-bottom: 2px solid #ccc;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .list-box ul {
        list-style-type: none;
        padding: 0;
        min-height: 200px; /* So the box is a drop target even when empty */
    }
    /* Use reorder-list styles for items */
    .list-box li { 
        padding: 10px 15px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        margin-bottom: 8px;
        border-radius: .375rem;
        display: flex;
        align-items: center;
        font-weight: 500;
        cursor: move;
    }
    .list-box li i {
        margin-right: 10px;
        color: #6c757d;
    }
    /* --- END NEW STYLES --- */
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row --}}
    <div class="d-flex justify-content-between align-items:center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Services</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('serviceAdd'))
            <a href="{{ route('service.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Service
            </a>
            @endif
        </div>
    </div>

    {{-- ==== TABS STRUCTURE ==== --}}
    <ul class="nav nav-tabs mb-3" id="serviceTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="list-tab" data-bs-toggle="tab" data-bs-target="#list-tab-pane" type="button" role="tab" aria-controls="list-tab-pane" aria-selected="true">
                <i data-feather="list" class="me-1" style="width:16px;"></i> Service List
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reorder-tab" data-bs-toggle="tab" data-bs-target="#reorder-tab-pane" type="button" role="tab" aria-controls="reorder-tab-pane" aria-selected="false">
                <i data-feather="move" class="me-1" style="width:16px;"></i> Reorder Services
            </button>
        </li>
        {{-- --- NEW TAB 3 --- --}}
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="homepage-tab" data-bs-toggle="tab" data-bs-target="#homepage-tab-pane" type="button" role="tab" aria-controls="homepage-tab-pane" aria-selected="false">
                <i data-feather="home" class="me-1" style="width:16px;"></i> Homepage Services
            </button>
        </li>
    </ul>

    <div class="tab-content" id="serviceTabsContent">
        
        {{-- ==== TAB 1: SERVICE LIST (Existing Table) ==== --}}
        <div class="tab-pane fade show active" id="list-tab-pane" role="tabpanel" aria-labelledby="list-tab" tabindex="0">
            {{-- ... (Your existing table code is unchanged) ... --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="card-title mb-0">Service List</h5>
                    <form class="d-flex" role="search" onsubmit="return false;">
                        <input class="form-control" id="searchInput" type="search" placeholder="Search services..." aria-label="Search">
                    </form>
                </div>
                <div class="card-body">
                    @include('flash_message')
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">Sl</th>
                                    <th style="width: 15%;">Image</th>
                                    <th class="sortable" data-column="title" style="width: 30%;">Title</th>
                                    <th style="width: 40%;">Description Preview</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                {{-- AJAX data here --}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
                    <div class="text-muted" id="tableRowCount"></div>
                    <nav>
                        <ul class="pagination justify-content-center mb-0" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>

        {{-- ==== TAB 2: REORDER SERVICES (Existing) ==== --}}
        <div class="tab-pane fade" id="reorder-tab-pane" role="tabpanel" aria-labelledby="reorder-tab" tabindex="0">
            {{-- ... (Your existing reorder tab code is unchanged) ... --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Reorder Services</h5>
                    <button id="saveOrderBtn" class="btn btn-primary">
                        <i data-feather="save" class="me-1" style="width:16px;"></i> Save Order
                    </button>
                </div>
                <div class="card-body">
                    <p class="text-muted">Drag and drop the services into your desired order and click "Save Order".</p>
                    @if($servicesForReorder->isEmpty())
                        <div class="alert alert-info">No services available to reorder.</div>
                    @else
                        <ul id="sortableList" class="reorder-list">
                            @foreach ($servicesForReorder as $service)
                                <li data-id="{{ $service->id }}">
                                    <i data-feather="menu"></i>
                                    <span>{{ $service->title }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
        
        {{-- ==== NEW TAB 3: HOMEPAGE SERVICES ==== --}}
        <div class="tab-pane fade" id="homepage-tab-pane" role="tabpanel" aria-labelledby="homepage-tab" tabindex="0">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Homepage Services</h5>
                    <button id="saveHomepageOrderBtn" class="btn btn-primary">
                        <i data-feather="save" class="me-1" style="width:16px;"></i> Save Homepage Order
                    </button>
                </div>
                <div class="card-body">
                    <p class="text-muted">Drag services from the "Available" list to the "Homepage" list. You can add a <strong>maximum of 6 services</strong> to the homepage and reorder them.</p>
                    
                    <div class="homepage-lists-container">
                        
                        {{-- List 1: Available Services --}}
                        <div class="list-box">
                            <h5>Available Services</h5>
                            <ul id="availableList">
                                @foreach($availableServices as $service)
                                    <li data-id="{{ $service->id }}">
                                        <i data-feather="plus"></i>
                                        <span>{{ $service->title }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        {{-- List 2: Homepage Services --}}
                        <div class="list-box">
                            <h5>Homepage Services (Max 6)</h5>
                            <ul id="homepageList">
                                @foreach($homepageServices as $service)
                                    <li data-id="{{ $service->id }}">
                                        <i data-feather="menu"></i>
                                        <span>{{ $service->title }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

{{-- ==== HIDDEN DELETE FORM (for Tab 1) ==== --}}
<form id="delete-service-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('script')
{{-- Include the AJAX table script (for Tab 1) --}}
@include('admin.service._partial.script')

{{-- ==== NEW SCRIPT for Drag-and-Drop ==== --}}
{{-- 1. Include SortableJS library --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Tab 2: Reordering Logic ---
        var sortableList = document.getElementById('sortableList');
        if (sortableList) {
            new Sortable(sortableList, {
                animation: 150,
                ghostClass: 'sortable-ghost',
            });
        }

        // --- Tab 2: Save Order Button Click Handler ---
        var saveOrderBtn = document.getElementById('saveOrderBtn');
        if (saveOrderBtn) {
            saveOrderBtn.addEventListener('click', function() {
                var order = [];
                document.querySelectorAll('#sortableList li').forEach(function(li) {
                    order.push(li.getAttribute('data-id'));
                });

                saveOrderBtn.disabled = true;
                saveOrderBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';

                fetch("{{ route('service.updateOrder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        showToast('success', data.message);
                    } else if (data.error) {
                        throw new Error(data.error);
                    }
                })
                .catch(error => {
                    showErrorAlert(error.message);
                })
                .finally(() => {
                    saveOrderBtn.disabled = false;
                    saveOrderBtn.innerHTML = '<i data-feather="save" class="me-1" style="width:16px;"></i> Save Order';
                    feather.replace(); 
                });
            });
        }

        // --- NEW: Tab 3: Homepage Reordering Logic ---
        var availableList = document.getElementById('availableList');
        var homepageList = document.getElementById('homepageList');
        var saveHomepageOrderBtn = document.getElementById('saveHomepageOrderBtn');

        if (availableList && homepageList) {
            // Initialize Sortable for Available List
            new Sortable(availableList, {
                group: 'homepage-services', // Define a shared group
                animation: 150,
                ghostClass: 'sortable-ghost',
                sort: false // Don't allow sorting in the available list
            });

            // Initialize Sortable for Homepage List
            new Sortable(homepageList, {
                group: 'homepage-services', // Same shared group
                animation: 150,
                ghostClass: 'sortable-ghost',
                onAdd: function(evt) {
                    // --- This is the MAX 6 check ---
                    if (homepageList.children.length > 6) {
                        // Move the item back to its original list
                        availableList.appendChild(evt.item);
                        // Show an error message
                        Swal.fire({
                            icon: 'warning',
                            title: 'Limit Reached',
                            text: 'You can only select a maximum of 6 services for the homepage.',
                        });
                    }
                }
            });
        }

        // --- NEW: Tab 3: Save Homepage Order Button Click ---
        if (saveHomepageOrderBtn) {
            saveHomepageOrderBtn.addEventListener('click', function() {
                var order = [];
                // Get IDs *only* from the homepage list
                document.querySelectorAll('#homepageList li').forEach(function(li) {
                    order.push(li.getAttribute('data-id'));
                });
                
                // Optional: Check if more than 6 (though the onAdd should prevent it)
                if(order.length > 6) {
                    showErrorAlert('Cannot save. Too many items selected (max 6).');
                    return;
                }

                saveHomepageOrderBtn.disabled = true;
                saveHomepageOrderBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...';

                // Send to the NEW route
                fetch("{{ route('service.updateHomepageOrder') }}", { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ order: order })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        showToast('success', data.message);
                    } else if (data.error) {
                        throw new Error(data.error);
                    }
                })
                .catch(error => {
                    showErrorAlert(error.message);
                })
                .finally(() => {
                    saveHomepageOrderBtn.disabled = false;
                    saveHomepageOrderBtn.innerHTML = '<i data-feather="save" class="me-1" style="width:16px;"></i> Save Homepage Order';
                    feather.replace(); 
                });
            });
        }

        // --- Tab Switching Feather Icon Fix ---
        var tabButtons = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabButtons.forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function(event) {
                feather.replace(); // Re-run Feather icons when a new tab is shown
            });
        });

        // --- Helper Functions for Alerts ---
        function showToast(icon, title) {
            Swal.fire({
                toast: true,
                icon: icon,
                title: title,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }

        function showErrorAlert(message) {
             Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message || 'Something went wrong!',
            });
        }
    });
</script>
@endsection