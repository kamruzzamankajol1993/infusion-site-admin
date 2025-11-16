<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="editModalLabel">Edit "Include" Item</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT') <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="editTitle" name="title" class="form-control @error('title', 'update') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="editIconName" class="form-label">Icon Name <span class="text-danger">*</span></label>
                    <input type="text" id="editIconName" name="icon_name" class="form-control @error('icon_name', 'update') is-invalid @enderror" value="{{ old('icon_name') }}" required>
                    <small class="form-text text-muted">Find icon names from <a href="https://icon-sets.iconify.design/" target="_blank">Iconify</a></small>
                    @error('icon_name', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
    document.getElementById('editModal').addEventListener('hidden.bs.modal', () => {
         document.getElementById('editForm').reset();
         $('#editForm .is-invalid').removeClass('is-invalid');
         $('#editForm .invalid-feedback.d-block').remove();
    });
</script>