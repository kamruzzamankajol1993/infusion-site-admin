{{-- resources/views/admin/media/_partial/editModal.blade.php --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit Media Item</h1>
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
                    <label for="editYouTubeLink" class="form-label">YouTube Link <span class="text-danger">*</span></label>
                    <input type="url" id="editYouTubeLink" name="youtube_link" class="form-control @error('youtube_link', 'update') is-invalid @enderror" value="{{ old('youtube_link') }}" required>
                    @error('youtube_link', 'update') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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

<script>
    var editModalElement = document.getElementById('editModal');
    editModalElement.addEventListener('hidden.bs.modal', function () {
         document.getElementById('editForm').reset();
         $('#editForm .is-invalid').removeClass('is-invalid');
         $('#editForm .invalid-feedback.d-block').remove();
    });
</script>