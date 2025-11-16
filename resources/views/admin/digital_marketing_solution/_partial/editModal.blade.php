<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="editModalLabel">Edit Solution</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="editForm" method="POST" action="" enctype="multipart/form-data">
            @csrf @method('PUT') <input type="hidden" id="editId">
            <div class="modal-body">
                 <div class="mb-3">
                    <label for="editTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="editTitle" name="title" class="form-control @error('title', 'update') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="editIcon" class="form-label">Icon</label>
                    <input type="file" id="editIcon" name="icon" class="form-control @error('icon', 'update') is-invalid @enderror" accept="image/*">
                    <small class="form-text text-muted">Required: 80x80 px. Max: 256KB. Leave blank to keep current.</small>
                    <div class="mt-2">
                        <img id="editIconPreview" src="#" alt="Icon Preview" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: contain; display: none;">
                         <span id="editPreviewPlaceholder" class="text-muted" style="display:none;">No Icon</span>
                    </div>
                    @error('icon', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
    document.getElementById('editIcon').addEventListener('change', e => {
        const preview = document.getElementById('editIconPreview');
        const placeholder = document.getElementById('editPreviewPlaceholder');
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onloadend = () => { preview.src = reader.result; preview.style.display = 'block'; placeholder.style.display = 'none'; };
            reader.readAsDataURL(e.target.files[0]);
        } else {
             const originalSrc = preview.dataset.originalSrc;
            if (originalSrc) { preview.src = originalSrc; preview.style.display = 'block'; placeholder.style.display = 'none'; } 
            else { preview.src = '#'; preview.style.display = 'none'; placeholder.style.display = 'block'; }
        }
    });
    document.getElementById('editModal').addEventListener('hidden.bs.modal', () => {
         document.getElementById('editForm').reset();
         const preview = document.getElementById('editIconPreview');
         preview.src = '#'; preview.style.display = 'none';
         document.getElementById('editPreviewPlaceholder').style.display = 'block';
         $('#editForm .is-invalid').removeClass('is-invalid');
         $('#editForm .invalid-feedback.d-block').remove();
    });
</script>