<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Package</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('facebookPage.package.store') }}">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="addTitle" class="form-label">Package Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="addTitle" class="form-control" placeholder="e.g., Basic Package" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="addPrice" class="form-label">Price (BDT) <span class="text-danger">*</span></label>
                        <input type="text" name="price" id="addPrice" class="form-control" placeholder="e.g., 999" required>
                    </div>
                </div>
                <div id="features-container-add">
                    <label class="form-label">Features <span class="text-danger">*</span></label>
                    <div class="input-group mb-2">
                        <input type="text" name="features[]" class="form-control" placeholder="Feature description" required>
                        <button class="btn btn-outline-danger" type="button" onclick="removeFeatureInput(this)">&times;</button>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addFeatureBtn-add">Add Feature</button>
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
<script>
    document.getElementById('addFeatureBtn-add').addEventListener('click', function() {
        const container = document.getElementById('features-container-add');
        const inputGroup = document.createElement('div');
        inputGroup.className = 'input-group mb-2';
        inputGroup.innerHTML = `
            <input type="text" name="features[]" class="form-control" placeholder="Feature description" required>
            <button class="btn btn-outline-danger" type="button" onclick="removeFeatureInput(this)">&times;</button>
        `;
        container.appendChild(inputGroup);
    });
    function removeFeatureInput(button) {
        button.closest('.input-group').remove();
    }
    document.getElementById('addModal').addEventListener('hidden.bs.modal', () => {
        document.getElementById('addForm').reset();
        const container = document.getElementById('features-container-add');
        while (container.children.length > 2) { // Keep label and first input
            container.removeChild(container.lastChild);
        }
    });
</script>