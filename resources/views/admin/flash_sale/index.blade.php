@extends('admin.master.master')
@section('title', 'Flash Sales')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h2 class="mb-0">Flash Sales</h2>
            <a href="{{ route('flash-sales.create') }}" class="btn text-white" style="background-color: var(--primary-color);">
                <i data-feather="plus" class="me-1"></i> Create New Sale
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-header"><h5 class="card-title mb-0">Filter Sales</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="titleFilter" class="form-label">Sale Title</label>
                        <input type="text" class="form-control" id="titleFilter" placeholder="Enter title...">
                    </div>
                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select id="statusFilter" class="form-select">
                            <option value="" selected>All Statuses</option>
                            <option value="active">Active</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="startDateFilter" class="form-label">Start Date</label>
                        {{-- Flatpickr will attach to this input --}}
                        <input type="text" class="form-control" id="startDateFilter" placeholder="Select date...">
                    </div>
                     <div class="col-md-2">
                        <label for="endDateFilter" class="form-label">End Date</label>
                        {{-- Flatpickr will attach to this input --}}
                        <input type="text" class="form-control" id="endDateFilter" placeholder="Select date...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary me-2 w-100" id="filterBtn">Filter</button>
                        <button class="btn btn-secondary w-100" id="resetBtn">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                @include('flash_message')
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="sortable" data-column="title">Title</th>
                                <th class="sortable" data-column="start_date">Start Date</th>
                                <th class="sortable" data-column="end_date">End Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <div class="text-muted" id="pagination-info"></div>
                <nav>
                    <ul class="pagination justify-content-center mb-0" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function() {
    // --- NEW: Initialize Flatpickr ---
    const fpConfig = {
        altInput: true,       // Shows a user-friendly date format
        altFormat: "F j, Y",  // How the date is displayed to the user
        dateFormat: "Y-m-d",  // How the date is sent to the server (YYYY-MM-DD)
    };
    flatpickr("#startDateFilter", fpConfig);
    flatpickr("#endDateFilter", fpConfig);
    // --- END of new code ---

    var currentPage = 1,
        saleTitle = '',
        saleStatus = '',
        startDate = '',
        endDate = '',
        sortColumn = 'created_at',
        sortDirection = 'desc';

    var routes = {
        fetch: "{{ route('ajax.flash-sale.data') }}",
        destroy_base: "{{ url('flash-sales') }}",
        csrf: "{{ csrf_token() }}"
    };

    function fetchData() {
        $('#tableBody').html(`<tr><td colspan="5" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></td></tr>`);

        $.get(routes.fetch, {
            page: currentPage,
            title: saleTitle,
            status: saleStatus,
            start_date: startDate,
            end_date: endDate,
            sort: sortColumn,
            direction: sortDirection
        }, function (res) {
            let rows = '';
            if (res.data.length === 0) {
                rows = '<tr><td colspan="5" class="text-center">No flash sales found.</td></tr>';
            } else {
                const now = new Date();
                res.data.forEach(sale => {
                    const startDateObj = new Date(sale.start_date);
                    const endDateObj = new Date(sale.end_date);
                    let statusBadge = '';

                    if (endDateObj < now) {
                        statusBadge = '<span class="badge bg-secondary">Expired</span>';
                    } else if (startDateObj > now) {
                        statusBadge = '<span class="badge bg-info">Scheduled</span>';
                    } else {
                        statusBadge = '<span class="badge bg-success">Active</span>';
                    }

                    const showUrl = `{{ url('flash-sales') }}/${sale.id}`;
                    const editUrl = `{{ url('flash-sales') }}/${sale.id}/edit`;

                    rows += `<tr>
                        <td>${sale.title}</td>
                        <td>${startDateObj.toLocaleString()}</td>
                        <td>${endDateObj.toLocaleString()}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <a href="${showUrl}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i></a>
                            <a href="${editUrl}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${sale.id}"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>`;
                });
            }
            $('#tableBody').html(rows);

            $('#pagination-info').text(`Showing ${res.data.length} of ${res.total} results`);

            let paginationHtml = '';
            if (res.last_page > 1) {
                paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page - 1}">Prev</a></li>`;
                
                const startPage = Math.max(1, res.current_page - 2);
                const endPage = Math.min(res.last_page, res.current_page + 2);

                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                
                paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">Next</a></li>`;
            }
            $('#pagination').html(paginationHtml);
        });
    }

    function applyFiltersAndFetch() {
        saleTitle = $('#titleFilter').val();
        saleStatus = $('#statusFilter').val();
        startDate = $('#startDateFilter').val();
        endDate = $('#endDateFilter').val();
        currentPage = 1;
        fetchData();
    }

    $('#filterBtn').on('click', applyFiltersAndFetch);
    
    $('#resetBtn').on('click', function() {
        $('#titleFilter').val('');
        $('#statusFilter').val('');
        // Clear Flatpickr instances
        flatpickr("#startDateFilter", {}).clear();
        flatpickr("#endDateFilter", {}).clear();
        
        applyFiltersAndFetch();
    });

    // ... (rest of the javascript for sorting, pagination, delete is the same) ...
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column');
        if (sortColumn === col) {
            sortDirection = (sortDirection === 'asc') ? 'desc' : 'asc';
        } else {
            sortColumn = col;
            sortDirection = 'asc';
        }
        fetchData();
    });

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            currentPage = page;
            fetchData();
        }
    });

    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `${routes.destroy_base}/${id}`,
                    method: 'DELETE',
                    data: { _token: routes.csrf },
                    success: function() {
                        Swal.fire('Deleted!', 'The flash sale has been deleted.', 'success');
                        fetchData();
                    },
                    error: function() {
                         Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });

    fetchData(); // Initial data load
});
</script>
@endsection