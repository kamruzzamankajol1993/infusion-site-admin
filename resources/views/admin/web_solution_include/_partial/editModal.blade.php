<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="editModalLabel">Edit "Include" Item</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf @method('PUT') <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="editTitle" name="title" class="form-control @error('title', 'update') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="editImage" class="form-label">Image</label>
                    <input type="file" id="editImage" name="image" class="form-control @error('image', 'update') is-invalid @enderror" accept="image/*">
                    <small class="form-text text-muted">Required: 80x80 px. Max: 256KB. Leave blank to keep current.</small>
                    <div class="mt-2">
                        <img id="editImagePreview" src="#" alt="Image Preview" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: contain; display: none;">
                         <span id="editPreviewPlaceholder" class="text-muted" style="display:none;">No Image</span>
                    </div>
                    @error('image', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="editDescription" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea id="editDescription" name="description" rows="3" class="form-control @error('description', 'update') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
         const p = document.getElementById('editImagePreview'); p.src = '#'; p.style.display = 'none';
         document.getElementById('editPreviewPlaceholder').style.display = 'block';
         $('#editForm .is-invalid').removeClass('is-invalid'); $('#editForm .invalid-feedback.d-block').remove();
    });
</script>