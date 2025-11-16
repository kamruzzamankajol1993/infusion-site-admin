<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="editModalLabel">Edit Package</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT') <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="editTitle" class="form-label">Package Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="editTitle" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="editPrice" class="form-label">Price (BDT) <span class="text-danger">*</span></label>
                        <input type="text" name="price" id="editPrice" class="form-control" required>
                    </div>
                </div>
                <div id="features-container-edit">
                    <label class="form-label">Features <span class="text-danger">*</span></label>
                    {{-- JS will populate this --}}
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addFeatureBtn-edit">Add Feature</button>
                <hr>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="editButtonText" class="form-label">Button Text <span class="text-danger">*</span></label>
                        <input type="text" name="button_text" id="editButtonText" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="editButtonLink" class="form-label">Button Link <span class="text-danger">*</span></label>
                        <input type="url" name="button_link" id="editButtonLink" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Changes</button></div>
        </form>
      </div>
    </div>
</div>
<script>
    document.getElementById('addFeatureBtn-edit').addEventListener('click', function() {
        const container = document.getElementById('features-container-edit');
        const inputGroup = document.createElement('div');
        inputGroup.className = 'input-group mb-2';
        inputGroup.innerHTML = `
            <input type="text" name="features[]" class="form-control" placeholder="Feature description" required>
            <button class="btn btn-outline-danger" type="button" onclick="removeFeatureInput(this)">&times;</button>
        `;
        container.appendChild(inputGroup);
    });
    // This function is global for both modals
    function removeFeatureInput(button) {
        button.closest('.input-group').remove();
    }
    document.getElementById('editModal').addEventListener('hidden.bs.modal', () => {
         document.getElementById('editForm').reset();
         $('#editForm .is-invalid').removeClass('is-invalid');
         $('#editForm .invalid-feedback.d-block').remove();
         // Clear dynamic features
         $('#features-container-edit').find('.input-group').remove();
    });
</script>