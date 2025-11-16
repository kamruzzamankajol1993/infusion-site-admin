<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="editModalLabel">Edit Service Card</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT') <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editIconName" class="form-label">Icon Name <span class="text-danger">*</span></label>
                    <input type="text" id="editIconName" name="icon_name" class="form-control" required>
                    <small class="form-text text-muted">Find icon names from <a href="https://icon-sets.iconify.design/" target="_blank">Iconify</a></small>
                </div>
                <div class="mb-3">
                    <label for="editTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="editTitle" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="editDescription" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea id="editDescription" name="description" rows="3" class="form-control" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="editLinkText" class="form-label">Link Text <span class="text-danger">*</span></label>
                        <input type="text" id="editLinkText" name="link_text" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="editLinkUrl" class="form-label">Link URL <span class="text-danger">*</span></label>
                        <input type="url" id="editLinkUrl" name="link_url" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Changes</button></div>
        </form>
      </div>
    </div>
</div>
<script>
    document.getElementById('editModal').addEventListener('hidden.bs.modal', () => {
         document.getElementById('editForm').reset();
         $('#editForm .is-invalid').removeClass('is-invalid');
         $('#editForm .invalid-feedback.d-block').remove();
    });
</script>