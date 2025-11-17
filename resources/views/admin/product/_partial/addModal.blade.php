<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> {{-- Extra Large Modal --}}
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Product</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('product.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    {{-- Left Column: Details --}}
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="addName" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="addName" class="form-control" placeholder="Enter product name" value="{{ old('name') }}" required>
                            @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="addDescription" class="form-label">Description</label>
                            <textarea name="description" id="addDescription" class="form-control summernote">{{ old('description') }}</textarea>
                            @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Pricing --}}
                        <h6 class="mt-4 text-primary">Pricing</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="addSellingPrice" class="form-label">Selling Price (BDT) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="selling_price" id="addSellingPrice" class="form-control" placeholder="e.g., 120.00" value="{{ old('selling_price') }}" required>
                                @error('selling_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="addDiscountPrice" class="form-label">Discount Price (BDT)</label>
                                <input type="number" step="0.01" name="discount_price" id="addDiscountPrice" class="form-control" placeholder="e.g., 100.00" value="{{ old('discount_price') }}">
                                <small class="form-text text-muted">Must be less than selling price.</small>
                                @error('discount_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="addBuyingPrice" class="form-label">Buying Price (BDT)</label>
                                <input type="number" step="0.01" name="buying_price" id="addBuyingPrice" class="form-control" placeholder="Optional" value="{{ old('buying_price') }}">
                                @error('buying_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Packages/Variations --}}
                        <h6 class="mt-4 text-primary">Product Packages / Variations</h6>
                        <p class="text-muted small">Leave blank if the product has no variations. The "Additional Price" will be added to the main "Selling Price".</p>
                        <div id="packages-container-add">
                            {{-- JS will add rows here --}}
                            @if(old('packages'))
                                @foreach(old('packages') as $i => $pkg)
                                <div class="row g-2 mb-2 package-row">
                                    <div class="col-6">
                                        <input type="text" name="packages[{{ $i }}][variation_name]" class="form-control" placeholder="Variation Name (e.g., 500g, Red)" value="{{ $pkg['variation_name'] }}" required>
                                    </div>
                                    <div class="col-5">
                                        <input type="number" step="0.01" name="packages[{{ $i }}][additional_price]" class="form-control" placeholder="Additional Price (e.g., 50.00)" value="{{ $pkg['additional_price'] }}" required>
                                    </div>
                                    <div class="col-1">
                                        <button class="btn btn-outline-danger" type="button" onclick="removePackageInput(this)">&times;</button>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addPackageBtn-add">
                            <i data-feather="plus" style="width:16px;"></i> Add Package
                        </button>

                    </div>
                    
                    {{-- Right Column: Organize --}}
                    <div class="col-lg-4">
                        <div class="card bg-light border">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="addImage" class="form-label">Product Image <span class="text-danger">*</span></label>
                                    <input type="file" name="image" id="addImage" class="form-control" accept="image/*" required>
                                    <div class="image-preview-box mt-2">
                                        <img id="addImagePreview" src="#" alt="Preview" style="display:none;">
                                    </div>
                                    @error('image') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="addCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" id="addCategory" class="form-select" required>
                                        <option value="" disabled selected>Select a category...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <h6 class="mt-4 text-primary">Stock & Status</h6>
                                <div class="mb-3">
                                    <label for="addSku" class="form-label">SKU (Optional)</label>
                                    <input type="text" name="sku" id="addSku" class="form-control" placeholder="e.g., VCC-BIN-001" value="{{ old('sku') }}">
                                    @error('sku') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="addStock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="stock_quantity" id="addStock" class="form-control" placeholder="e.g., 100" value="{{ old('stock_quantity', 0) }}" required>
                                    @error('stock_quantity') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" id="addStatus" {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="addStatus">Active (Visible on store)</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_top_selling_product" value="1" id="addTopSelling" {{ old('is_top_selling_product') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="addTopSelling">Top Selling Product</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </form>
      </div>
    </div>
</div>

{{-- This template is used by JS to add new package rows --}}
<template id="package-template-add">
    <div class="row g-2 mb-2 package-row">
        <div class="col-6">
            <input type="text" name="packages[NEW_INDEX][variation_name]" class="form-control" placeholder="Variation Name (e.g., 500g, Red)" required>
        </div>
        <div class="col-5">
            <input type="number" step="0.01" name="packages[NEW_INDEX][additional_price]" class="form-control" placeholder="Additional Price (e.g., 50.00)" value="0" required>
        </div>
        <div class="col-1 d-flex align-items-center">
            <button class="btn btn-outline-danger btn-sm" type="button" onclick="removePackageInput(this)">&times;</button>
        </div>
    </div>
</template>

<script>
    // Global function to remove a package row
    function removePackageInput(button) {
        button.closest('.package-row').remove();
    }

    // Add modal script
    let addPackageCounter = {{ old('packages') ? count(old('packages')) : 0 }};
    document.getElementById('addPackageBtn-add').addEventListener('click', function() {
        const container = document.getElementById('packages-container-add');
        const template = document.getElementById('package-template-add');
        let newRowHtml = template.innerHTML.replace(/NEW_INDEX/g, addPackageCounter);
        const newRow = document.createElement('div');
        newRow.innerHTML = newRowHtml;
        container.appendChild(newRow.firstElementChild);
        addPackageCounter++;
    });

    document.getElementById('addImage').addEventListener('change', e => { 
        const p = document.getElementById('addImagePreview');
        if (e.target.files[0]) { 
            const r = new FileReader(); 
            r.onloadend = () => { p.src = r.result; p.style.display = 'block'; }; 
            r.readAsDataURL(e.target.files[0]); 
        } else { p.src = '#'; p.style.display = 'none'; }
    });
    
    document.getElementById('addModal').addEventListener('hidden.bs.modal', () => {
        document.getElementById('addForm').reset();
        $('#addDescription').summernote('code', '');
        document.getElementById('packages-container-add').innerHTML = '';
        document.getElementById('addImagePreview').style.display = 'none';
        $('#addForm .is-invalid').removeClass('is-invalid');
        $('#addForm .text-danger').remove();
    });
</script>