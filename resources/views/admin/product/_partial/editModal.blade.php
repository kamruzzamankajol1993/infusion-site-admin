<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl"> {{-- Extra Large Modal --}}
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="editModalLabel">Edit Product</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="row">
                    {{-- Left Column: Details --}}
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editName" class="form-control" value="{{ old('name') }}" required>
                            @error('name', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Description</label>
                            <textarea name="description" id="editDescription" class="form-control summernote">{{ old('description') }}</textarea>
                            @error('description', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        {{-- Pricing --}}
                        <h6 class="mt-4 text-primary">Pricing</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="editSellingPrice" class="form-label">Selling Price (BDT) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="selling_price" id="editSellingPrice" class="form-control" value="{{ old('selling_price') }}" required>
                                @error('selling_price', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="editDiscountPrice" class="form-label">Discount Price (BDT)</label>
                                <input type="number" step="0.01" name="discount_price" id="editDiscountPrice" class="form-control" value="{{ old('discount_price') }}">
                                <small class="form-text text-muted">Must be less than selling price.</small>
                                @error('discount_price', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="editBuyingPrice" class="form-label">Buying Price (BDT)</label>
                                <input type="number" step="0.01" name="buying_price" id="editBuyingPrice" class="form-control" value="{{ old('buying_price') }}">
                                @error('buying_price', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Packages/Variations --}}
                        <h6 class="mt-4 text-primary">Product Packages / Variations</h6>
                        <p class="text-muted small">Leave blank if the product has no variations. The "Additional Price" will be added to the main "Selling Price".</p>
                        <div id="packages-container-edit">
                            {{-- JS will populate this --}}
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addPackageBtn-edit">
                            <i data-feather="plus" style="width:16px;"></i> Add Package
                        </button>

                    </div>
                    
                    {{-- Right Column: Organize --}}
                    <div class="col-lg-4">
                        <div class="card bg-light border">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="editImage" class="form-label">Product Image</label>
                                    <input type="file" name="image" id="editImage" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">Leave blank to keep current image.</small>
                                    <div class="image-preview-box mt-2">
                                        <img id="editImagePreview" src="#" alt="Preview" style="display:none;">
                                    </div>
                                    @error('image', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="editCategory" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select name="category_id" id="editCategory" class="form-select" required>
                                        <option value="" disabled>Select a category...</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <h6 class="mt-4 text-primary">Stock & Status</h6>
                                <div class="mb-3">
                                    <label for="editSku" class="form-label">SKU (Optional)</label>
                                    <input type="text" name="sku" id="editSku" class="form-control" value="{{ old('sku') }}">
                                    @error('sku', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="editStock" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="stock_quantity" id="editStock" class="form-control" value="{{ old('stock_quantity') }}" required>
                                    @error('stock_quantity', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="status" value="1" id="editStatus" {{ old('status') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="editStatus">Active (Visible on store)</label>
                                </div>

                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_top_selling_product" value="1" id="editTopSelling" {{ old('is_top_selling_product') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="editTopSelling">Top Selling Product</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
</div>

{{-- This template is used by JS to add new package rows --}}
<template id="package-template-edit">
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
    // Edit modal script
    let editPackageCounter = 0;
    document.getElementById('addPackageBtn-edit').addEventListener('click', function() {
        const container = document.getElementById('packages-container-edit');
        const template = document.getElementById('package-template-edit');
        let newRowHtml = template.innerHTML.replace(/NEW_INDEX/g, `new_${editPackageCounter}`);
        const newRow = document.createElement('div');
        newRow.innerHTML = newRowHtml;
        container.appendChild(newRow.firstElementChild);
        editPackageCounter++;
    });

    document.getElementById('editImage').addEventListener('change', e => { 
        const p = document.getElementById('editImagePreview');
        if (e.target.files[0]) { 
            const r = new FileReader(); 
            r.onloadend = () => { p.src = r.result; p.style.display = 'block'; }; 
            r.readAsDataURL(e.target.files[0]); 
        } else { 
            p.src = p.dataset.originalSrc || '#'; // Revert to original on deselection
            p.style.display = p.dataset.originalSrc ? 'block' : 'none';
        }
    });

    document.getElementById('editModal').addEventListener('hidden.bs.modal', () => {
         document.getElementById('editForm').reset();
         $('#editDescription').summernote('code', '');
         document.getElementById('packages-container-edit').innerHTML = '';
         document.getElementById('editImagePreview').style.display = 'none';
         $('#editForm .is-invalid').removeClass('is-invalid');
         $('#editForm .text-danger').remove();
         editPackageCounter = 0;
    });
</script>