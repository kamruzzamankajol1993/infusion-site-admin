@extends('admin.master.master')
@section('title', 'Create Flash Sale')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{-- NEW: Flatpickr Confirm Plugin CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.css">
<style>
    .select2-container--bootstrap-5 .select2-selection {
        padding: 0.475rem 1rem;
        min-height: calc(1.5em + 0.95rem + 2px);
    }
</style>
@endsection

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="py-4">
             <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Create New Flash Sale</h2>
                <a href="{{ route('flash-sales.index') }}" class="btn btn-outline-secondary">Back to List</a>
            </div>
        </div>

        <form action="{{ route('flash-sales.store') }}" method="POST">
            @csrf
            
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Sale Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="e.g., Eid Special Flash Sale" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date & Time <span class="text-danger">*</span></label>
                            <input type="text" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required placeholder="Select start time">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date & Time <span class="text-danger">*</span></label>
                            <input type="text" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}" required placeholder="Select end time">
                        </div>
                         <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" value="1" checked>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Sale Products</h5>
                    <div style="width: 50%;">
                        <select id="product-search" class="form-control" style="width: 100%;"></select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th style="width: 140px;">Original Price</th>
                                    <th style="width: 140px;">Flash Price</th>
                                    <th style="width: 120px;">Quantity</th>
                                    <th style="width: 50px;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="selected-products-table">
                                <tr id="no-products-row">
                                    <td colspan="5" class="text-center text-muted">No products added yet.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i data-feather="save" class="me-1" style="width: 18px;"></i> Save Flash Sale
                </button>
            </div>
        </form>
    </div>
</main>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
{{-- NEW: Flatpickr Confirm Plugin JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/confirmDate/confirmDate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    feather.replace();

    // --- MODIFIED: Flatpickr Initialization with Confirm Plugin ---
    const flatpickrConfig = {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        altInput: true,
        altFormat: "M j, Y h:i K",
        plugins: [
            new confirmDatePlugin({
                confirmText: "Confirm", // Text for the confirm button
                showAlways: true,      // Always show the button
                theme: "light"         // Theme
            })
        ]
    };

    const startDatePicker = flatpickr("#start_date", {
        ...flatpickrConfig, // Spread the base config
        onChange: (selectedDates, dateStr) => endDatePicker.set('minDate', dateStr)
    });

    const endDatePicker = flatpickr("#end_date", flatpickrConfig);

    // --- Select2 Product Search ---
    $('#product-search').select2({
        theme: 'bootstrap-5',
        placeholder: 'Type product name or SKU...',
        minimumInputLength: 2,
        ajax: {
            url: '{{ route("flash-sales.search-products") }}',
            dataType: 'json',
            delay: 250,
            processResults: (data) => ({
                results: $.map(data, (item) => ({ 
                    id: item.id, 
                    text: `${item.name} (SKU: ${item.sku || 'N/A'})`, 
                    price: item.selling_price 
                }))
            }),
            cache: true
        }
    });

    // --- Add Product to Table ---
    $('#product-search').on('select2:select', function (e) {
        const data = e.params.data;
        if ($(`#product-row-${data.id}`).length > 0) {
            Swal.fire('Already Added', 'This product is already in the list.', 'warning');
            $(this).val(null).trigger('change');
            return;
        }
        $('#no-products-row').hide();
        const rowHtml = `
            <tr id="product-row-${data.id}">
                <td>${data.text}<input type="hidden" name="products[${data.id}][id]" value="${data.id}"></td>
                <td>৳ ${parseFloat(data.price || 0).toFixed(2)}</td>
                <td><input type="number" name="products[${data.id}][flash_price]" class="form-control form-control-sm" required step="0.01" min="0" placeholder="0.00"></td>
                <td><input type="number" name="products[${data.id}][quantity]" class="form-control form-control-sm" required min="1" placeholder="0"></td>
                <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-product-btn"><i data-feather="trash-2" style="width:16px;"></i></button></td>
            </tr>`;
        $('#selected-products-table').append(rowHtml);
        feather.replace();
        $(this).val(null).trigger('change');
    });

    // --- Remove Product from Table ---
    $(document).on('click', '.remove-product-btn', function() {
        $(this).closest('tr').remove();
        if ($('#selected-products-table tr:not(#no-products-row)').length === 0) {
            $('#no-products-row').show();
        }
    });
});
</script>
@endsection