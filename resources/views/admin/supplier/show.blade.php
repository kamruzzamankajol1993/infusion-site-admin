@extends('admin.master.master')
@section('title', 'Supplier Details')
@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h2 class="mb-0">Supplier Details</h2>
            <div>
                <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-info text-white">
                    <i data-feather="edit-2" class="me-1" style="width:18px; height:18px;"></i> Edit Supplier
                </a>
                <a href="{{ route('supplier.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <div class="avatar avatar-xxl mb-3">
                            <div class="avatar-initial rounded-circle bg-light-primary d-flex justify-content-center align-items-center">
                                <i data-feather="truck" class="text-primary" style="width: 36px; height: 36px;"></i>
                            </div>
                        </div>
                        <h4 class="mb-1">{{ $supplier->company_name }}</h4>
                        <p class="text-muted">{{ $supplier->contact_person }}</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center">
                            <i data-feather="phone" class="me-2 text-muted"></i>
                            <span>{{ $supplier->phone }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i data-feather="mail" class="me-2 text-muted"></i>
                            <span>{{ $supplier->email ?? 'Not provided' }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i data-feather="map-pin" class="me-2 text-muted"></i>
                            <span>{{ $supplier->address ?? 'Not provided' }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i data-feather="hash" class="me-2 text-muted"></i>
                            <span>VAT/Tax No: {{ $supplier->vat_number ?? 'N/A' }}</span>
                        </li>
                    </ul>
                    <div class="card-footer text-center">
                        <div>
                            <strong>Status:</strong>
                            @if($supplier->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </div>
                        <small class="text-muted">Member Since: {{ $supplier->created_at->format('d M, Y') }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Purchase History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Purchase No.</th>
                                        <th>Date</th>
                                        <th class="text-end">Total Amount</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($supplier->purchases as $purchase)
                                    <tr>
                                        <td>{{ $purchase->purchase_no }}</td>
                                        <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</td>
                                        <td class="text-end">৳{{ number_format($purchase->total_amount, 2) }}</td>
                                        <td class="text-center">
                                            @if($purchase->payment_status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($purchase->payment_status == 'partial')
                                                 <span class="badge bg-warning">Partial</span>
                                            @else
                                                <span class="badge bg-danger">Due</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('purchase.show', $purchase->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No purchase history found for this supplier.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection