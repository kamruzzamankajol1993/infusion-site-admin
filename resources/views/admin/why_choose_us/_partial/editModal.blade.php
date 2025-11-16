{{-- resources/views/admin/why_choose_us/_partial/editModal.blade.php --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> {{-- Made modal large --}}
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit "Why Choose Us" Item</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <form id="editForm" method="POST" enctype="multipart/form-data" action="">
            @csrf
            @method('PUT')
            <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" id="editTitle" name="title" class="form-control @error('title', 'update') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="editImage" class="form-label">Image</label>
                    <input type="file" id="editImage" name="image" class="form-control @error('image', 'update') is-invalid @enderror" accept="image/*">
                    <small class="form-text text-muted">Required: 300x400 px. Max: 1MB. Leave blank to keep current.</small>
                    <div class="mt-2">
                        <img id="editImagePreview" src="#" alt="Image Preview" class="img-thumbnail" style="width: 150px; height: 200px; object-fit: cover; display: none;">
                         <span id="editPreviewPlaceholder" class="text-muted" style="display:none;">No Image</span>
                    </div>
                    @error('image', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="editDescription" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea id="editDescription" name="description" class="form-control summernote" required>{{ old('description') }}</textarea>
                    <div id="editDescription-error" class="form-error"></div>
                    @error('description', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="editSubmitBtn">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>

{{-- Script for edit modal preview & summernote cleanup --}}
<script>
    document.getElementById('editImage').addEventListener('change', function(event) {
        const preview = document.getElementById('editImagePreview');
        const placeholder = document.getElementById('editPreviewPlaceholder');
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onloadend = () => { preview.src = reader.result; preview.style.display = 'block'; placeholder.style.display = 'none'; };
        if (file) { reader.readAsDataURL(file); } 
        else {
             const originalSrc = preview.dataset.originalSrc;
            if (originalSrc) { preview.src = originalSrc; preview.style.display = 'block'; placeholder.style.display = 'none'; } 
            else { preview.src = '#'; preview.style.display = 'none'; placeholder.style.display = 'block'; }
        }
    });

    var editModalElement = document.getElementById('editModal');
    editModalElement.addEventListener('hidden.bs.modal', function () {
         document.getElementById('editForm').reset();
         const preview = document.getElementById('editImagePreview');
         preview.src = '#'; preview.style.display = 'none';
         document.getElementById('editPreviewPlaceholder').style.display = 'block';
         $('#editDescription').summernote('code', ''); // Clear summernote
         $('#editForm .is-invalid').removeClass('is-invalid');
         $('#editForm .form-error').empty();
         $('#editForm .invalid-feedback.d-block').remove();
    });
</script>