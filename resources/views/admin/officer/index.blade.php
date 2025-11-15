@extends('admin.master.master')

@section('title')
Officer List | {{ $ins_name }}
@endsection

@section('css')
{{-- Styles for cards and drag-and-drop --}}
<style>
    /* Basic styling for officer cards */
    .officer-card {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #fff;
        display: flex;
        align-items: center;
        cursor: grab; /* Indicate draggable */
        transition: background-color 0.2s ease; /* Smooth transition */
    }
    .officer-card:hover {
        background-color: #f8f9fa; /* Light hover effect */
    }
    .officer-card img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 15px; /* Increased spacing */
        object-fit: cover;
        flex-shrink: 0; /* Prevent image shrinking */
    }
    .officer-card span {
        font-weight: 500;
    }

    /* Styling for SortableJS dragging */
    .sortable-ghost {
        opacity: 0.4;
        background-color: #e9ecef; /* Slightly darker ghost */
        border: 1px dashed #adb5bd;
    }
    .sortable-drag {
        cursor: grabbing; /* Cursor while dragging */
    }

     /* Hide spinner initially */
    #loadingSpinner { display: none; }

    /* Ensure active category button has pointer cursor */
    .category-btn.active {
        cursor: default; /* Make active button look non-clickable */
    }
    .category-btn:not(.active) {
        cursor: pointer;
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
                <li class="breadcrumb-item">Officer, Board Member</li>
                <li class="breadcrumb-item active" aria-current="page">All Officers</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('officerAdd'))
            <a href="{{ route('officer.create') }}" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Officer
            </a>
            @endif
        </div>
    </div>

    {{-- Category Filter Buttons --}}
    <div class="mb-4">
        <strong class="me-2">Filter & Reorder by Category:</strong>
        <a href="#" class="btn btn-sm btn-secondary category-btn active" data-category-id="all">All Officers (Table View)</a>
        @foreach($topLevelCategories as $category)
            <a href="#" class="btn btn-sm btn-outline-primary category-btn" data-category-id="{{ $category->id }}">
                {{ $category->name }}
            </a>
        @endforeach
    </div>

    {{-- Main Officer Table Card (Visible Initially) --}}
    <div class="card shadow-sm" id="officerTableCard">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Officer List</h5>
            <form class="d-flex" role="search" onsubmit="return false;"> {{-- Prevent default form submission --}}
                <input class="form-control" id="searchInput" type="search" placeholder="Search officers..." aria-label="Search">
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Image</th>
                            <th class="sortable" data-column="name">Name</th>
                            <th>Categories</th>
                            <th class="sortable" data-column="status">Status</th>
                            {{-- Removed global order column as it's now category-specific --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap">
            <div class="text-muted" id="tableRowCount"></div> {{-- Placeholder for showing row count --}}
            <nav>
                <ul class="pagination justify-content-center mb-0" id="pagination"></ul>
            </nav>
        </div>
    </div>

    {{-- Card Area for Category-Specific Officers & Drag-Drop (Hidden Initially) --}}
    <div class="card shadow-sm" id="officerCardArea" style="display: none;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
             <h5 class="card-title mb-0" id="cardAreaTitle">Category Officers</h5>
             {{-- Loading Spinner --}}
             <div id="loadingSpinner" class="spinner-border spinner-border-sm text-primary" role="status">
                 <span class="visually-hidden">Loading...</span>
             </div>
        </div>
        <div class="card-body">
            <p class="text-muted">Drag and drop the cards below to reorder the officers specifically for this category.</p>
             <div id="officerCardContainer">
                 {{-- Officer cards will be loaded here via AJAX --}}
             </div>
        </div>
         <div class="card-footer bg-white text-end">
            <span class="text-muted">Changes are saved automatically after dropping.</span>
        </div>
    </div>

</div>
{{-- ==== ADD THIS HIDDEN DELETE FORM ==== --}}
    <form id="delete-officer-form" action="" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    {{-- ==== END HIDDEN DELETE FORM ==== --}}
@endsection

@section('script')
{{-- Include SortableJS library --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

{{-- Include the original script for handling the main AJAX table --}}
@include('admin.officer._partial.script')

{{-- Script for Category Filtering and Drag & Drop --}}
<script>
    $(document).ready(function() {
        let currentCategoryId = 'all'; // Track the currently selected category ('all' or an ID)
        let sortableInstance = null; // Holds the SortableJS instance

        // --- Category Button Click Handler ---
        $('.category-btn').on('click', function(e) {
            e.preventDefault();
            const clickedCategoryId = $(this).data('category-id');

            // Do nothing if the already active button is clicked
            if ($(this).hasClass('active')) {
                return;
            }

            // Update button styles
            $('.category-btn').removeClass('active btn-primary btn-secondary').addClass('btn-outline-primary');
            $(this).removeClass('btn-outline-primary').addClass('active');
             if (clickedCategoryId === 'all') {
                $(this).addClass('btn-secondary'); // Special style for 'All'
            } else {
                 $(this).addClass('btn-primary');
            }

            currentCategoryId = clickedCategoryId; // Update the current category ID

            // Toggle visibility of table vs. card area
            if (currentCategoryId === 'all') {
                $('#officerTableCard').show();
                $('#officerCardArea').hide();
                // Optionally reset sort for the main table and reload
                sortColumn = 'name'; // Reset default sort for table view
                sortDirection = 'asc';
                fetchData(); // Reload original table data
            } else {
                $('#officerTableCard').hide();
                $('#officerCardArea').show();
                $('#cardAreaTitle').text($(this).text() + ' Officers (Drag to Reorder)'); // Update card title
                loadCategoryOfficers(currentCategoryId); // Load officers for the selected category
            }
        });

        // --- Function to Load Officers for a Category into Cards ---
        function loadCategoryOfficers(categoryId) {
            const container = $('#officerCardContainer');
            const spinner = $('#loadingSpinner');
            container.html(''); // Clear previous cards
            spinner.show(); // Show loading indicator

            // Destroy previous Sortable instance if it exists to prevent issues
            if (sortableInstance) {
                sortableInstance.destroy();
                sortableInstance = null;
            }

            // AJAX request to get officers for the category
            $.ajax({
                url: `{{ route('officer.getByCategory', ':id') }}`.replace(':id', categoryId), // Use named route
                method: 'GET',
                success: function(officers) {
                    spinner.hide(); // Hide spinner on success
                    if (!officers || officers.length === 0) {
                        container.html('<p class="text-muted text-center my-4">No officers found in this category.</p>');
                        return; // Stop if no officers
                    }

                    // Generate and append officer cards
                    officers.forEach(officer => {
                        let imageUrl = officer.image
                            ? `{{ asset('') }}${officer.image}` // Correct path for public folder
                            : `{{ asset('public/admin/assets/img/demo-user.svg') }}`; // Default image

                        // data-officer-id attribute is crucial for saving order
                        const cardHtml = `
                            <div class="officer-card" data-officer-id="${officer.id}">
                                <img src="${imageUrl}" alt="${officer.name}">
                                <span>${officer.name}</span>
                            </div>`;
                        container.append(cardHtml);
                    });

                    // Initialize SortableJS on the container holding the cards
                    initializeSortable(container[0], categoryId);

                },
                error: function(xhr) {
                    spinner.hide(); // Hide spinner on error
                    console.error("Error fetching officers:", xhr.responseText);
                    Swal.fire('Error', 'Could not load officers for this category. Please check the console.', 'error');
                    container.html('<p class="text-danger text-center my-4">Failed to load officers.</p>'); // Show error message
                }
            });
        }

        // --- Function to Initialize SortableJS ---
        function initializeSortable(element, categoryId) {
            if (!element) return; // Guard against null element
            sortableInstance = new Sortable(element, {
                animation: 150,           // Animation speed ms
                ghostClass: 'sortable-ghost', // Class name for the drop placeholder
                dragClass: 'sortable-drag',   // Class name for the dragging item
                forceFallback: true,        // Ensures behaviour is consistent across browsers
                onEnd: function (evt) {
                    // This function is called when the user finishes dragging an item
                    saveOfficerOrder(categoryId); // Save the new order for the current category
                }
            });
        }

        // --- Function to Save the Officer Order for a Specific Category ---
        function saveOfficerOrder(categoryId) {
            const officerIds = [];
            // Collect the officer IDs from the data attribute in their new order
            $('#officerCardContainer .officer-card').each(function() {
                officerIds.push($(this).data('officer-id'));
            });

            // If no officers are present, don't make the AJAX call
            if (officerIds.length === 0 && categoryId !== 'all') {
                 console.log("No officers to save order for in category " + categoryId);
                 // Optionally clear the order on the backend if needed, but usually not required
                 // If you *want* to clear the order if the list is empty, make a specific AJAX call here.
                 return;
            }

            // AJAX request to update the order in the pivot table
            $.ajax({
                url: `{{ route('officer.updateOrder', ':id') }}`.replace(':id', categoryId), // Use route with categoryId
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    order: officerIds // Send the array of officer IDs in the new order
                },
                success: function(response) {
                    // Show a temporary success message
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Order updated successfully!',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true
                    });
                },
                error: function(xhr) {
                    console.error("Error updating order:", xhr.responseText);
                    Swal.fire('Error', 'Could not save the new order. Please check the console.', 'error');
                     // Optionally, attempt to reload the category officers to revert visual changes on error
                     if(categoryId !== 'all') {
                        loadCategoryOfficers(categoryId);
                     }
                }
            });
        }

        // Initial load of the main table when the page loads
        if (currentCategoryId === 'all') {
            fetchData();
        }

    });
</script>

@endsection