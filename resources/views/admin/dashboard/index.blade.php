@extends('admin.master.master')

@section('title')
Dashboard | {{ $ins_name }}
@endsection

@section('css')
<style>
    .card-stat {
        border: none;
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: transform 0.2s;
    }
    .card-stat:hover {
        transform: translateY(-5px);
    }
    .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }
    /* Colors */
    .bg-purple { background-color: #6f42c1; }
    .bg-blue { background-color: #0d6efd; }
    .bg-success-custom { background-color: #198754; }
    .bg-orange { background-color: #fd7e14; }
    
    .text-revenue { color: #198754; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 1. Key Ecommerce Metrics --}}
    <div class="row g-4 mb-4">
        {{-- Total Revenue --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-stat h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Revenue</p>
                            <h3 class="fw-bold text-revenue">৳{{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                        <div class="icon-box bg-success-custom">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-stat h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Total Orders</p>
                            <h3 class="fw-bold text-dark">{{ number_format($totalOrders) }}</h3>
                        </div>
                        <div class="icon-box bg-blue">
                            <i class="bi bi-cart-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Customers --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-stat h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Customers</p>
                            <h3 class="fw-bold text-dark">{{ number_format($totalCustomers) }}</h3>
                        </div>
                        <div class="icon-box bg-purple">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Products --}}
        <div class="col-xl-3 col-md-6">
            <div class="card card-stat h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">Products</p>
                            <h3 class="fw-bold text-dark">{{ number_format($totalProducts) }}</h3>
                        </div>
                        <div class="icon-box bg-orange">
                            <i class="bi bi-box-seam"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Action Items & Charts --}}
    <div class="row g-4 mb-4">
        {{-- Pending Actions --}}
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Attention Needed</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('order.index') }}?status=pending" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-hourglass-split text-warning me-2"></i> Pending Orders</span>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $pendingOrders }}</span>
                    </a>
                    <a href="{{ route('review.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-star text-primary me-2"></i> Pending Reviews</span>
                        <span class="badge bg-primary rounded-pill">{{ $pendingReviews }}</span>
                    </a>
                    <a href="{{ route('contactUs.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-envelope text-info me-2"></i> Messages</span>
                        <span class="badge bg-info rounded-pill">{{ $unreadMessages }}</span>
                    </a>
                    <a href="{{ route('product.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-exclamation-triangle text-danger me-2"></i> Low Stock Products</span>
                        <span class="badge bg-danger rounded-pill">{{ $lowStockProducts }}</span>
                    </a>
                </div>
                <div class="card-footer bg-white">
                    <small class="text-muted">Content Stats:</small>
                    <div class="d-flex justify-content-between mt-2">
                        <small>Active Banners: <strong>{{ $activeBanners }}</strong></small>
                        <small>Total Packages: <strong>{{ $totalPackages }}</strong></small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue Chart --}}
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Monthly Revenue (Paid Orders)</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="280"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Recent Orders Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Recent Orders</h5>
            <a href="{{ route('order.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td class="fw-bold">#{{ $order->order_number }}</td>
                        <td>
                            <div>{{ $order->first_name }} {{ $order->last_name }}</div>
                            <small class="text-muted">{{ $order->phone }}</small>
                        </td>
                        <td>৳{{ number_format($order->grand_total, 2) }}</td>
                        <td>
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-secondary">Unpaid</span>
                            @endif
                        </td>
                        <td>
                            @if($order->order_status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($order->order_status == 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-warning text-dark">{{ ucfirst($order->order_status) }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('order.show', $order->id) }}" class="btn btn-sm btn-light border">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No recent orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Data from Controller
        const labels = @json($chartLabels);
        const data = @json($chartValues);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue (৳)',
                    data: data,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4] }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endsection