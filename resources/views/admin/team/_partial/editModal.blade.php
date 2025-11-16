{{-- resources/views/admin/team/_partial/editModal.blade.php --}}
<div class="modal fade" id="editTeamModal" tabindex="-1" aria-labelledby="editTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editTeamModalLabel">Edit Team Member</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <form id="editTeamForm" method="POST" enctype="multipart/form-data" action="">
            @csrf
            @method('PUT')
            <input type="hidden" id="editTeamId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" id="editName" name="name" class="form-control @error('name', 'update') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="editDesignation" class="form-label">Designation <span class="text-danger">*</span></label>
                    <input type="text" id="editDesignation" name="designation" class="form-control @error('designation', 'update') is-invalid @enderror" value="{{ old('designation') }}" required>
                    @error('designation', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="editImage" class="form-label">Image</label>
                    <input type="file" id="editImage" name="image" class="form-control @error('image', 'update') is-invalid @enderror" accept="image/*">
                    <small id="editImageHelpText" class="form-text text-muted">Required: 300x300 px. Max: 512KB. Leave blank to keep current.</small>
                    <div class="mt-2">
                        <img id="editImagePreview" src="#" alt="Image Preview" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover; display: none;">
                         <span id="editPreviewPlaceholder" class="text-muted" style="display:none;">No Image</span>
                    </div>
                    @error('image', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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

{{-- Script for edit modal preview --}}
<script>
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

        if (file) { reader.readAsDataURL(file); } 
        else {
             const originalSrc = preview.dataset.originalSrc;
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

    var editModalElement = document.getElementById('editTeamModal');
    editModalElement.addEventListener('hidden.bs.modal', function () {
         document.getElementById('editTeamForm').reset();
         const preview = document.getElementById('editImagePreview');
         preview.src = '#';
         preview.style.display = 'none';
         document.getElementById('editPreviewPlaceholder').style.display = 'block';
         
         $('#editTeamForm .is-invalid').removeClass('is-invalid');
         $('#editTeamForm .invalid-feedback.d-block').remove(); 
         $('#editSubmitBtn').prop('disabled', false).text('Save Changes');
    });
</script>