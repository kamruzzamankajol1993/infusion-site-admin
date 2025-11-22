<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="editModalLabel">Edit Service Card</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf @method('PUT') <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editImage" class="form-label">Image (Icon)</label>
                    <input type="file" id="editImage" name="image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Required: 80x80 px. Max: 256KB. Leave blank to keep current.</small>
                    <div class="mt-2">
                        <img id="editImagePreview" src="#" alt="Image Preview" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: contain; display: none;">
                         <span id="editPreviewPlaceholder" class="text-muted" style="display:none;">No Image</span>
                    </div>
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
    document.getElementById('editImage').addEventListener('change', e => {
        const p = document.getElementById('editImagePreview'), h = document.getElementById('editPreviewPlaceholder');
        if (e.target.files[0]) { const r = new FileReader(); r.onloadend = () => { p.src = r.result; p.style.display = 'block'; h.style.display = 'none'; }; r.readAsDataURL(e.target.files[0]); }
        else { const o = p.dataset.originalSrc; if (o) { p.src = o; p.style.display = 'block'; h.style.display = 'none'; } else { p.src = '#'; p.style.display = 'none'; h.style.display = 'block'; }}
    });
    document.getElementById('editModal').addEventListener('hidden.bs.modal', () => {
         document.getElementById('editForm').reset();
         document.getElementById('editImagePreview').style.display = 'none';
         document.getElementById('editPreviewPlaceholder').style.display = 'block';
         $('#editForm .is-invalid').removeClass('is-invalid');
         $('#editForm .invalid-feedback.d-block').remove();
    });
</script>