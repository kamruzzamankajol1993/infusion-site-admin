{{-- resources/views/admin/why_us/_partial/editModal.blade.php --}}
<div class="modal fade" id="editWhyUsModal" tabindex="-1" aria-labelledby="editWhyUsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editWhyUsModalLabel">Edit "Why Us" Item</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <form id="editWhyUsForm" method="POST" enctype="multipart/form-data" action="">
            @csrf
            @method('PUT') {{-- Method spoofing --}}
            <input type="hidden" id="editWhyUsId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="editName" name="name" class="form-control @error('name', 'update') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="editImage" class="form-label">Image (Icon)</label>
                    <input type="file" id="editImage" name="image" class="form-control @error('image', 'update') is-invalid @enderror" accept="image/*">
                    <small id="editImageHelpText" class="form-text text-muted">Required: 60x60 px. Max: 256KB. Leave blank to keep current.</small>
                    {{-- Preview for current/new image --}}
                    <div class="mt-2">
                        <img id="editImagePreview" src="#" alt="Image Preview" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: contain; display: none;">
                         <span id="editPreviewPlaceholder" class="text-muted" style="display:none;">No Image</span>
                    </div>
                    @error('image', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
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

{{-- Script specifically for edit modal preview --}}
<script>
    // Image file input change
    document.getElementById('editImage').addEventListener('change', function(event) {
        const preview = document.getElementById('editImagePreview');
        const placeholder = document.getElementById('editPreviewPlaceholder');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
             placeholder.style.display = 'none';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
             // If deselected, try showing original if available (set by main script)
             const originalSrc = preview.dataset.originalSrc; // Use data attribute
            if (originalSrc) {
                 preview.src = originalSrc;
                 preview.style.display = 'block';
                 placeholder.style.display = 'none';
            } else {
                preview.src = '#';
                preview.style.display = 'none';
                 placeholder.style.display = 'block';
            }
        }
    });

     // Clear preview/form on hide
    var editModalElement = document.getElementById('editWhyUsModal');
    editModalElement.addEventListener('hidden.bs.modal', function () {
         document.getElementById('editWhyUsForm').reset();
         const preview = document.getElementById('editImagePreview');
         preview.src = '#';
         preview.style.display = 'none';
         document.getElementById('editPreviewPlaceholder').style.display = 'block';
         
         // Clear validation errors
         $('#editWhyUsForm .is-invalid').removeClass('is-invalid');
         $('#editWhyUsForm .invalid-feedback.d-block').remove(); 
         
         $('#editSubmitBtn').prop('disabled', false).text('Save Changes');
    });
</script>