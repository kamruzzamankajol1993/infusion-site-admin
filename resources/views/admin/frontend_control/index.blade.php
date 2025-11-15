@extends('admin.master.master')

@section('title', 'Frontend Control')

@section('css')
    <style>
        .setting-card {
            border: 1px solid #e0e0e0;
            border-radius: 0.5rem;
            background-color: #f8f9fa;
        }
        .setting-card-header {
            padding: 1rem 1.5rem;
            background-color: #f1f1f1;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
        }
        .dual-list-container {
            display: grid;
            grid-template-columns: 1fr 50px 1fr;
            gap: 1rem;
            align-items: center;
        }
        .category-list {
            border: 1px dashed #ced4da;
            border-radius: 0.375rem;
            padding: 1rem;
            height: 400px;
            overflow-y: auto;
            background-color: #fff;
        }
        .category-list-header {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #6c757d;
        }
        .category-item {
            background-color: #fff;
            padding: 0.5rem 0.75rem;
            border-radius: 0.25rem;
            border: 1px solid #eee;
            margin-bottom: 0.5rem;
            cursor: grab;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .transfer-arrows {
            text-align: center;
            color: #6c757d;
        }
        .sortable-ghost {
            opacity: 0.4;
            background: #e3f2fd;
        }
    </style>
@endsection

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="my-4">
            <h2 class="mb-0">Frontend Website Control</h2>
            <p class="text-muted">Drag categories between the lists to select them, and drag to reorder within the 'Selected' column.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i data-feather="check-circle" class="me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('frontend.control.update') }}" method="POST" id="frontendControlForm">
            @csrf
            
            <div id="hidden-inputs-container"></div>

            <div class="card mb-4">
                <div class="card-header setting-card-header">
                    <i data-feather="layout"></i> Header Navigation Categories
                </div>
                <div class="card-body p-4">
                    <div class="dual-list-container">
                        <div>
                            <p class="category-list-header">Available Categories</p>
                            <input type="text" class="form-control mb-2 category-filter-input" data-target="#header-available" placeholder="Search...">
                            <div class="category-list" id="header-available">
                                @foreach($headerAvailable as $category)
                                    <div class="category-item" data-id="{{ $category->id }}">{{ $category->name }}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="transfer-arrows">
                            <i data-feather="chevrons-left"></i>
                            <i data-feather="chevrons-right"></i>
                        </div>
                        <div>
                            <p class="category-list-header">Selected & Ordered</p>
                             <input type="text" class="form-control mb-2 category-filter-input" data-target="#header-selected" placeholder="Search...">
                            <div class="category-list" id="header-selected">
                                @foreach($headerSelected as $category)
                                    <div class="category-item" data-id="{{ $category->id }}">{{ $category->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                 <div class="card-header setting-card-header">
                    <i data-feather="sidebar"></i> Sidebar & Mobile Menu Categories
                </div>
                <div class="card-body p-4">
                    <div class="dual-list-container">
                        <div>
                            <p class="category-list-header">Available Categories</p>
                             <input type="text" class="form-control mb-2 category-filter-input" data-target="#sidebar-available" placeholder="Search...">
                            <div class="category-list" id="sidebar-available">
                                @foreach($sidebarAvailable as $category)
                                    <div class="category-item" data-id="{{ $category->id }}">{{ $category->name }}</div>
                                @endforeach
                            </div>
                        </div>
                        <div class="transfer-arrows">
                             <i data-feather="chevrons-left"></i>
                             <i data-feather="chevrons-right"></i>
                        </div>
                        <div>
                            <p class="category-list-header">Selected & Ordered</p>
                            <input type="text" class="form-control mb-2 category-filter-input" data-target="#sidebar-selected" placeholder="Search...">
                            <div class="category-list" id="sidebar-selected">
                                 @foreach($sidebarSelected as $category)
                                    <div class="category-item" data-id="{{ $category->id }}">{{ $category->name }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i data-feather="save" class="me-2" style="width:18px;"></i>
                    Save Settings
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();

            // Function to initialize a dual list setup
            function initDualList(availableId, selectedId) {
                const availableList = document.getElementById(availableId);
                const selectedList = document.getElementById(selectedId);

                new Sortable(availableList, {
                    group: 'shared-' + availableId, // a unique group name
                    animation: 150,
                    ghostClass: 'sortable-ghost'
                });

                new Sortable(selectedList, {
                    group: 'shared-' + availableId,
                    animation: 150,
                    ghostClass: 'sortable-ghost'
                });
            }

            // Initialize both sets of lists
            initDualList('header-available', 'header-selected');
            initDualList('sidebar-available', 'sidebar-selected');

            // --- BUG FIX: Populate hidden inputs before submitting ---
            const form = document.getElementById('frontendControlForm');
            const hiddenInputsContainer = document.getElementById('hidden-inputs-container');

            form.addEventListener('submit', function(event) {
                // Clear any previous hidden inputs
                hiddenInputsContainer.innerHTML = '';

                // Get all selected header category IDs in their current order
                const headerSelectedItems = document.querySelectorAll('#header-selected .category-item');
                headerSelectedItems.forEach(item => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'header_category_ids[]';
                    input.value = item.dataset.id;
                    hiddenInputsContainer.appendChild(input);
                });

                // Get all selected sidebar category IDs in their current order
                const sidebarSelectedItems = document.querySelectorAll('#sidebar-selected .category-item');
                sidebarSelectedItems.forEach(item => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'sidebar_category_ids[]';
                    input.value = item.dataset.id;
                    hiddenInputsContainer.appendChild(input);
                });
            });

            // Search filter functionality
            document.querySelectorAll('.category-filter-input').forEach(input => {
                input.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const targetList = document.querySelector(this.dataset.target);
                    targetList.querySelectorAll('.category-item').forEach(item => {
                        const text = item.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
@endsection