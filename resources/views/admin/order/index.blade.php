@extends('admin.master.master')

@section('title')
Order List | {{ $ins_name }}
@endsection

@section('css')
<style>
    .order-status-select {
        min-width: 150px;
    }
    .badge-pending { background-color: #ffc107; color: #000; }
    .badge-processing { background-color: #17a2b8; color: #fff; }
    .badge-shipped { background-color: #007bff; color: #fff; }
    .badge-delivered { background-color: #28a745; color: #fff; }
    .badge-cancelled { background-color: #dc3545; color: #fff; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Ecommerce</li>
                <li class="breadcrumb-item active">Orders</li>
            </ol>
        </nav>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Manage Orders</h5>
            
            <div class="d-flex gap-2">
                {{-- Status Filter --}}
                <select id="statusFilter" class="form-select form-select-sm order-status-select">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                
                {{-- Search --}}
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" class="form-control" id="searchInput" placeholder="Search Order #, Name...">
                    <span class="input-group-text"><i data-feather="search" style="width: 14px;"></i></span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
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
@endsection

@section('script')
<script>
    let currentPage = 1;
    let status = 'all';
    let search = '';

    const routes = {
        fetch: "{{ route('ajax.order.data') }}",
        show: "{{ route('order.show', ':id') }}"
    };

    // Function to map status to badge class
    function getStatusBadge(status) {
        switch(status) {
            case 'pending': return '<span class="badge badge-pending">Pending</span>';
            case 'processing': return '<span class="badge badge-processing">Processing</span>';
            case 'shipped': return '<span class="badge badge-shipped">Shipped</span>';
            case 'delivered': return '<span class="badge badge-delivered">Delivered</span>';
            case 'cancelled': return '<span class="badge badge-cancelled">Cancelled</span>';
            default: return `<span class="badge bg-secondary">${status}</span>`;
        }
    }

    function fetchData() {
        $.get(routes.fetch, { page: currentPage, status: status, search: search }, function(res) {
            let rows = ''; 
            if (!res.data || res.data.length === 0) {
                $('#tableBody').html('<tr><td colspan="7" class="text-center text-muted py-4">No orders found.</td></tr>');
                $('#tableRowCount').text('Showing 0 records');
                $('#pagination').empty();
                return;
            }
            
            res.data.forEach(item => {
                let paymentBadge = item.payment_status === 'paid' 
                    ? '<span class="badge bg-success">PAID</span>' 
                    : '<span class="badge bg-warning text-dark">UNPAID</span>';

                rows += `<tr>
                    <td class="fw-bold">#${item.order_number}</td>
                    <td>
                        <div class="fw-bold">${item.first_name} ${item.last_name || ''}</div>
                        <div class="small text-muted">${item.phone || ''}</div>
                    </td>
                    <td>৳${parseFloat(item.grand_total).toFixed(2)}</td>
                    <td>${paymentBadge} <small class="text-muted">(${item.payment_method})</small></td>
                    <td>${getStatusBadge(item.order_status)}</td>
                    <td>${new Date(item.created_at).toLocaleDateString()}</td>
                    <td class="text-end">
                        <a href="${routes.show.replace(':id', item.id)}" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                    </td>
                </tr>`;
            });
            
            $('#tableBody').html(rows);
            $('#tableRowCount').text(`Showing ${((res.current_page-1)*res.per_page)+1} to ${Math.min(res.current_page*res.per_page, res.total)} of ${res.total} orders`);
            renderPagination(res);
        });
    }

    // Pagination Render (Simple version)
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
    
    $('#statusFilter').on('change', function() { status = $(this).val(); currentPage = 1; fetchData(); });
    $('#searchInput').on('keyup', function() { search = $(this).val(); currentPage = 1; fetchData(); });

    // Initial Load
    fetchData();
</script>
@endsection