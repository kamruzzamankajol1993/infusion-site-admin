<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="editModalLabel">Edit Testimonial</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf @method('PUT') <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3"><label for="editName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="editName" class="form-control" required></div>
                        <div class="mb-3"><label for="editDesignation" class="form-label">Designation</label>
                            <input type="text" name="designation" id="editDesignation" class="form-control"></div>
                        <div class="mb-3"><label for="editRating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <select name="rating" id="editRating" class="form-select" required>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select></div>
                    </div>
                    <div class="col-md-4">
                        <label for="editImage" class="form-label">Image</label>
                        <input type="file" id="editImage" name="image" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Recommended: 100x100 px. Leave blank to keep current.</small>
                        <div class="mt-2 text-center">
                            <img id="editImagePreview" src="#" alt="Image Preview" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; display: none;">
                            <span id="editPreviewPlaceholder" class="text-muted" style="display:none;">No Image</span>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="editQuote" class="form-label">Quote <span class="text-danger">*</span></label>
                    <textarea name="quote" id="editQuote" rows="4" class="form-control" required></textarea>
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