@extends('admin.master.master')
@section('title') Product Reviews | {{ $ins_name }} @endsection

@section('css')
<style>
    .star-rating { color: #ffc107; font-size: 0.9em; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Ecommerce</li>
                <li class="breadcrumb-item active">Product Reviews</li>
            </ol>
        </nav>
    </div>

    @include('flash_message')

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Customer Reviews</h5>
            
            <div class="d-flex gap-2">
                {{-- Bulk Action --}}
                <button class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn" onclick="bulkDelete()">
                    <i data-feather="trash-2" style="width: 14px;"></i> Delete Selected
                </button>
                
                {{-- Search --}}
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search product, user...">
                    <span class="input-group-text"><i data-feather="search" style="width: 14px;"></i></span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="3%" class="text-center">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th width="20%">Product</th>
                            <th width="15%">Customer</th>
                            <th width="15%">Rating</th>
                            <th width="30%">Review</th>
                            <th width="10%">Status</th>
                            <th width="7%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small" id="tableRowCount"></div>
                <nav><ul class="pagination pagination-sm mb-0" id="pagination"></ul></nav>
            </div>
        </div>
    </div>
</div>

{{-- Forms --}}
<form id="status-form" action="" method="POST" style="display: none;">@csrf @method('PUT')</form>
<form id="delete-form" action="" method="POST" style="display: none;">@csrf @method('DELETE')</form>

@endsection

@section('script')
<script>
    let currentPage = 1;
    let search = '';
    const routes = { 
        fetch: "{{ route('ajax.review.data') }}",
        update: "{{ route('review.update', ':id') }}",
        delete: "{{ route('review.destroy', ':id') }}",
        bulkDelete: "{{ route('review.destroyMultiple') }}",
        token: "{{ csrf_token() }}"
    };

    function renderStars(rating) {
        let stars = '';
        for(let i=1; i<=5; i++) {
            stars += i <= rating ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
        }
        return `<span class="star-rating">${stars}</span>`;
    }

    function fetchData() {
        $.get(routes.fetch, { page: currentPage, search: search }, function(res) {
            let rows = ''; 
            if (!res.data || res.data.length === 0) {
                $('#tableBody').html('<tr><td colspan="7" class="text-center text-muted py-4">No reviews found.</td></tr>');
                $('#tableRowCount').text('Showing 0 records');
                $('#pagination').empty();
                return;
            }
            
            res.data.forEach(item => {
                let statusBadge = item.status 
                    ? '<span class="badge bg-success">Approved</span>' 
                    : '<span class="badge bg-warning text-dark">Pending</span>';
                
                let btnClass = item.status ? 'btn-warning' : 'btn-success';
                let btnIcon = item.status ? 'bi-x-circle' : 'bi-check-circle';
                let btnTitle = item.status ? 'Unapprove' : 'Approve';

                let productName = item.product ? item.product.name : '<span class="text-danger">Deleted Product</span>';
                let userName = item.user ? item.user.name : '<span class="text-muted">Guest</span>';

                rows += `<tr>
                    <td class="text-center"><input type="checkbox" class="form-check-input row-checkbox" value="${item.id}"></td>
                    <td>${productName}</td>
                    <td>${userName}</td>
                    <td>${renderStars(item.rating)}</td>
                    <td><small class="text-muted d-block" style="max-width: 300px; white-space: normal;">${item.review || 'No text review'}</small></td>
                    <td>${statusBadge}</td>
                    <td>
                        <div class="btn-group">
                            <button onclick="updateStatus(${item.id})" class="btn btn-sm ${btnClass}" title="${btnTitle}">
                                <i class="bi ${btnIcon}"></i>
                            </button>
                            <button onclick="deleteReview(${item.id})" class="btn btn-sm btn-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>`;
            });
            
            $('#tableBody').html(rows);
            $('#tableRowCount').text(`Showing ${((res.current_page-1)*res.per_page)+1} to ${Math.min(res.current_page*res.per_page, res.total)} of ${res.total} reviews`);
            renderPagination(res);
        });
    }

    // Pagination
    function renderPagination(res) {
        let html = '';
        if(res.prev_page_url) html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${res.current_page - 1})">&laquo;</a></li>`;
        else html += `<li class="page-item disabled"><span class="page-link">&laquo;</span></li>`;
        html += `<li class="page-item active"><span class="page-link">${res.current_page}</span></li>`;
        if(res.next_page_url) html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${res.current_page + 1})">&raquo;</a></li>`;
        else html += `<li class="page-item disabled"><span class="page-link">&raquo;</span></li>`;
        $('#pagination').html(html);
    }
    window.changePage = function(page) { currentPage = page; fetchData(); };
    
    // Search
    $('#searchInput').on('keyup', function() { search = $(this).val(); currentPage = 1; fetchData(); });

    // Actions
    window.updateStatus = function(id) {
        $('#status-form').attr('action', routes.update.replace(':id', id)).submit();
    };
    
    window.deleteReview = function(id) {
        Swal.fire({
            title: 'Are you sure?', text: "You won't be able to revert this!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-form').attr('action', routes.delete.replace(':id', id)).submit();
            }
        });
    };

    // Bulk Delete
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        toggleBulkBtn();
    });
    
    $(document).on('change', '.row-checkbox', toggleBulkBtn);

    function toggleBulkBtn() {
        if ($('.row-checkbox:checked').length > 0) $('#bulkDeleteBtn').removeClass('d-none');
        else $('#bulkDeleteBtn').addClass('d-none');
    }

    window.bulkDelete = function() {
        let ids = [];
        $('.row-checkbox:checked').each(function() { ids.push($(this).val()); });

        Swal.fire({
            title: 'Delete selected reviews?', text: "This cannot be undone!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routes.bulkDelete, type: 'DELETE',
                    data: { ids: ids, _token: routes.token },
                    success: function(res) {
                        Swal.fire('Deleted!', res.message, 'success');
                        fetchData();
                        $('#bulkDeleteBtn').addClass('d-none');
                        $('#selectAll').prop('checked', false);
                    },
                    error: function() { Swal.fire('Error!', 'Could not delete reviews.', 'error'); }
                });
            }
        });
    }

    // Initial Load
    fetchData();
</script>
@endsection