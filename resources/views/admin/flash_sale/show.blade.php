@extends('admin.master.master')
@section('title', 'Flash Sale Details')

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Flash Sale Details</h2>
                    <p class="text-muted">{{ $flashSale->title }}</p>
                </div>
                <div>
                     <a href="{{ route('flash-sales.edit', $flashSale->id) }}" class="btn btn-info">
                        <i data-feather="edit-2" class="me-1" style="width: 16px;"></i> Edit Sale
                    </a>
                    <a href="{{ route('flash-sales.index') }}" class="btn btn-outline-secondary">Back to List</a>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Campaign Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Title:</strong>
                        <p>{{ $flashSale->title }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Starts On:</strong>
                        <p>{{ $flashSale->start_date->format('F j, Y, g:i A') }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Ends On:</strong>
                        <p>{{ $flashSale->end_date->format('F j, Y, g:i A') }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong>Status:</strong>
                        <p>
                            @if($flashSale->end_date < now())
                                <span class="badge bg-secondary fs-6">Expired</span>
                            @elseif($flashSale->start_date > now())
                                <span class="badge bg-info fs-6">Scheduled</span>
                            @else
                                <span class="badge bg-success fs-6">Active</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
             <div class="card-header">
                <h5 class="card-title mb-0">Products in this Sale</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Original Price</th>
                                <th>Flash Price</th>
                                <th>Sale Stock</th>
                                <th>Progress (Sold/Stock)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($flashSale->products as $product)
                                @php
                                    $sold = $product->pivot->sold;
                                    $quantity = $product->pivot->quantity;
                                    $progress = ($quantity > 0) ? ($sold / $quantity) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $product->name }}</strong>
                                        <br>
                                        <small class="text-muted">SKU: {{ $product->sku }}</small>
                                    </td>
                                    <td><del>৳{{ number_format($product->selling_price, 2) }}</del></td>
                                    <td><strong class="text-danger">৳{{ number_format($product->pivot->flash_price, 2) }}</strong></td>
                                    <td>{{ $quantity }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ $sold }} / {{ $quantity }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">No products are assigned to this flash sale.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection