{{-- resources/views/admin/project_category/_partial/editModal.blade.php --}}
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editCategoryModalLabel">Edit Project Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- MODIFIED: Set action to "" (will be set by JS) --}}
        <form id="editCategoryForm" method="POST" enctype="multipart/form-data" action="">
            @csrf
            @method('PUT')
            <input type="hidden" id="editCategoryId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Category Name <span class="text-danger">*</span></label>
                    {{-- MODIFIED: Added @error class --}}
                    <input type="text" id="editName" name="name" class="form-control @error('name', 'update') is-invalid @enderror" required>
                    {{-- MODIFIED: Added Blade error directive for 'update' bag --}}
                    @error('name', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="editImage" class="form-label">Category Image</label>
                    {{-- MODIFIED: Added @error class --}}
                    <input type="file" id="editImage" name="image" class="form-control @error('image', 'update') is-invalid @enderror" accept="image/*">
                    <small class="form-text text-muted">Size: 750x422 px, Max: 1MB. Leave blank to keep current.</small>
                    <div class="mt-2">
                        <img id="editImagePreview" src="#" alt="Image Preview" class="img-thumbnail" style="max-height: 150px; width: auto; display: none;">
                         <span id="editPreviewPlaceholderPC" class="text-muted" style="display:none;">No Image</span>
                    </div>
                     {{-- MODIFIED: Added Blade error directive for 'update' bag --}}
                     @error('image', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="editSubmitBtnPC">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>

{{-- Script for edit modal preview --}}
<script>
    document.getElementById('editImage').addEventListener('change', function(event) {
        const preview = document.getElementById('editImagePreview');
        const placeholder = document.getElementById('editPreviewPlaceholderPC');
        const file = event.target.files[0]; const reader = new FileReader();
        reader.onloadend = function() { preview.src = reader.result; preview.style.display = 'block'; placeholder.style.display = 'none'; }
        if (file) { reader.readAsDataURL(file); } else {
             const originalSrc = preview.dataset.originalSrc;
            if (originalSrc) { preview.src = originalSrc; preview.style.display = 'block'; placeholder.style.display = 'none';}
            else { preview.src = '#'; preview.style.display = 'none'; placeholder.style.display = 'block';}
        }
    });
    // Clear form/preview on hide
    var editModalElementPC = document.getElementById('editCategoryModal'); // Use unique ID
    editModalElementPC.addEventListener('hidden.bs.modal', function () {
         document.getElementById('editCategoryForm').reset();
         document.getElementById('editImagePreview').src = '#';
         document.getElementById('editImagePreview').style.display = 'none';
         document.getElementById('editPreviewPlaceholderPC').style.display = 'block';
         // Clear validation classes (Bootstrap 5)
         $('#editCategoryForm .is-invalid').removeClass('is-invalid');
         // Clear blade error messages (since they are outside the form inputs)
         $('#editCategoryForm .invalid-feedback.d-block').remove(); // This might be too aggressive
         // Safer: Let's rely on the 'hidden.bs.modal' listener in script.blade.php
    });
</script>