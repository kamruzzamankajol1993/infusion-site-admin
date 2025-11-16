<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New UK Pricing Package</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('ukCompany.package.store') }}">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="addTitle" class="form-label">Package Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="addTitle" class="form-control" placeholder="e.g., Digital" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="addPrice" class="form-label">Price (BDT) <span class="text-danger">*</span></label>
                        <input type="text" name="price" id="addPrice" class="form-control" placeholder="e.g., 3,999" required>
                    </div>
                </div>
                
                <div id="features-container-add">
                    <label class="form-label">Features <span class="text-danger">*</span></label>
                    {{-- JS will add first item --}}
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addFeatureBtn-add">
                    <i data-feather="plus" style="width:16px;"></i> Add Feature
                </button>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="addButtonText" class="form-label">Button Text <span class="text-danger">*</span></label>
                        <input type="text" name="button_text" id="addButtonText" class="form-control" value="Order Now" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="addButtonLink" class="form-label">Button Link <span class="text-danger">*</span></label>
                        <input type="url" name="button_link" id="addButtonLink" class="form-control" value="#" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Package</button></div>
        </form>
      </div>
    </div>
</div>

{{-- This template is used by JS to add new rows --}}
<template id="feature-template-add">
    <div class="input-group mb-2 feature-row">
        <div class="input-group-text">
            <input class="form-check-input mt-0" type="checkbox" name="features_included[]" value="on" checked>
        </div>
        <input type="text" name="features_text[]" class="form-control" placeholder="Feature description" required>
        <button class="btn btn-outline-danger" type="button" onclick="removeFeatureInput(this)">&times;</button>
    </div>
</template>

<script>
    // Global function to remove a feature row
    function removeFeatureInput(button) {
        button.closest('.feature-row').remove();
    }

    // Add modal script
    document.getElementById('addFeatureBtn-add').addEventListener('click', function() {
        const container = document.getElementById('features-container-add');
        const template = document.getElementById('feature-template-add');
        container.appendChild(template.content.cloneNode(true));
    });

    document.getElementById('addModal').addEventListener('shown.bs.modal', () => {
        // Add one feature row by default if none exist
        const container = document.getElementById('features-container-add');
        if (container.querySelectorAll('.feature-row').length === 0) {
            document.getElementById('addFeatureBtn-add').click();
        }
    });

    document.getElementById('addModal').addEventListener('hidden.bs.modal', () => {
        document.getElementById('addForm').reset();
        document.getElementById('features-container-add').innerHTML = '<label class="form-label">Features <span class="text-danger">*</span></label>';
    });
</script>