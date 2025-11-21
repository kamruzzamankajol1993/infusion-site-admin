@extends('admin.master.master')

@section('title')
Order #{{ $order->order_number }} | {{ $ins_name }}
@endsection

@section('body')
<div class="container-fluid px-4 py-4">
    
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0">Order Details: #{{ $order->order_number }}</h4>
            <small class="text-muted">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</small>
        </div>
        <div class="btn-group">
            <a href="{{ route('order.print.a4', $order->id) }}" target="_blank" class="btn btn-outline-dark">
                <i class="bi bi-printer"></i> Print A4
            </a>
            <a href="{{ route('order.print.pos', $order->id) }}" target="_blank" class="btn btn-outline-dark">
                <i class="bi bi-receipt"></i> POS Receipt
            </a>
            <a href="{{ route('order.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    @include('flash_message')

    <div class="row">
        {{-- Left Column: Items & Info --}}
        <div class="col-lg-8">
            
            {{-- Order Items --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white"><h5 class="card-title mb-0">Order Items</h5></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-end">Unit Price</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr class="border-bottom">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{-- Product Snapshot Name --}}
                                            <div>
                                                <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                @if($item->variation_name)
                                                    <small class="text-muted">Variation: {{ $item->variation_name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">৳{{ number_format($item->price, 2) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end fw-bold">৳{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless mb-0 text-end">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="fw-bold">৳{{ number_format($order->sub_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Tax:</td>
                                    <td>৳{{ number_format($order->tax, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Shipping:</td>
                                    <td>৳{{ number_format($order->shipping_cost, 2) }}</td>
                                </tr>
                                @if($order->discount > 0)
                                <tr>
                                    <td>Discount:</td>
                                    <td class="text-danger">- ৳{{ number_format($order->discount, 2) }}</td>
                                </tr>
                                @endif
                                <tr class="border-top">
                                    <td class="fs-5 fw-bold">Total:</td>
                                    <td class="fs-5 fw-bold text-primary">৳{{ number_format($order->grand_total, 2) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Customer & Shipping --}}
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white"><h6 class="mb-0">Billing Details</h6></div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                            <p class="mb-1"><strong>Phone:</strong> {{ $order->phone }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white"><h6 class="mb-0">Shipping Address</h6></div>
                        <div class="card-body">
                            <p class="mb-1">{{ $order->shipping_address }}</p>
                            <p class="mb-1">{{ $order->city }}</p>
                            <p class="mb-1">{{ $order->zip_code }}</p>
                            @if($order->notes)
                                <hr>
                                <small class="text-muted"><strong>Note:</strong> {{ $order->notes }}</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column: Actions --}}
        <div class="col-lg-4">
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white"><h5 class="card-title mb-0">Order Management</h5></div>
                <div class="card-body">
                    <form action="{{ route('order.update-status', $order->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Order Status</label>
                            <select name="order_status" class="form-select">
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0">Payment Info</h6></div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Method:</span>
                        <span class="fw-bold text-uppercase">{{ $order->payment_method }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Status:</span>
                        @if($order->payment_status == 'paid')
                            <span class="badge bg-success">PAID</span>
                        @else
                            <span class="badge bg-warning text-dark">UNPAID</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection