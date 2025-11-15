{{-- resources/views/admin/training_category/_partial/editModal.blade.php --}}
<div class="modal fade" id="editCategoryModalTC" tabindex="-1" aria-labelledby="editCategoryModalLabelTC" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editCategoryModalLabelTC">Edit Training Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editCategoryFormTC" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="editCategoryIdTC">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editNameTC" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" id="editNameTC" name="name" class="form-control" required>
                    <div id="editNameErrorTC" class="invalid-feedback"></div>
                </div>
                <div class="mb-3">
                    <label for="editImageTC" class="form-label">Category Image</label>
                    <input type="file" id="editImageTC" name="image" class="form-control" accept="image/*">
                    <small class="form-text text-muted">Size: 750x422 px, Max: 1MB. Leave blank to keep current.</small>
                    <div class="mt-2">
                        <img id="editImagePreviewTC" src="#" alt="Image Preview" class="img-thumbnail" style="max-height: 150px; width: auto; display: none;">
                         <span id="editPreviewPlaceholderTC" class="text-muted" style="display:none;">No Image</span>
                    </div>
                     <div id="editImageErrorTC" class="invalid-feedback"></div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="editSubmitBtnTC">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>

<script>
    document.getElementById('editImageTC').addEventListener('change', function(event) {
        const preview = document.getElementById('editImagePreviewTC');
        const placeholder = document.getElementById('editPreviewPlaceholderTC');
        const file = event.target.files[0]; const reader = new FileReader();
        reader.onloadend = function() { preview.src = reader.result; preview.style.display = 'block'; placeholder.style.display = 'none'; }
        if (file) { reader.readAsDataURL(file); } else {
             const originalSrc = preview.dataset.originalSrc;
            if (originalSrc) { preview.src = originalSrc; preview.style.display = 'block'; placeholder.style.display = 'none';}
            else { preview.src = '#'; preview.style.display = 'none'; placeholder.style.display = 'block';}
        }
    });
    var editModalElementTC = document.getElementById('editCategoryModalTC');
    editModalElementTC.addEventListener('hidden.bs.modal', function () {
         document.getElementById('editCategoryFormTC').reset();
         document.getElementById('editImagePreviewTC').src = '#';
         document.getElementById('editImagePreviewTC').style.display = 'none';
         document.getElementById('editPreviewPlaceholderTC').style.display = 'block';
         $('#editCategoryFormTC .is-invalid').removeClass('is-invalid');
         $('#editCategoryFormTC .invalid-feedback').text('');
         $('#editSubmitBtnTC').prop('disabled', false).text('Save Changes');
    });
</script>