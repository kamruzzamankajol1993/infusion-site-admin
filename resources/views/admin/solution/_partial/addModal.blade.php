{{-- resources/views/admin/solution/_partial/addModal.blade.php --}}
<div class="modal fade" id="addSolutionModal" tabindex="-1" aria-labelledby="addSolutionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addSolutionModalLabel">Add New Solution</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addSolutionForm" method="post" action="{{ route('solution.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addName" class="form-label">Solution Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="addName" class="form-control" placeholder="Enter solution name" required>
                    @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="addImage" class="form-label">Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="addImage" class="form-control" accept="image/*" required>
                    {{-- *** UPDATED: Help text *** --}}
                    <small id="addImageHelpText" class="form-text text-muted">Required: 60x60 px. Max: 256KB</small>
                    {{-- *** UPDATED: Preview style *** --}}
                    <img id="addImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; width: 60px; height: 60px; object-fit: contain;">
                     @error('image') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Solution</button>
            </div>
        </form>
      </div>
    </div>
  </div>

{{-- Script specifically for add modal preview --}}
<script>
    // Image file input change
    document.getElementById('addImage').addEventListener('change', function(event) {
        const preview = document.getElementById('addImagePreview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    });

    // Clear preview/form on hide
    var addModalElement = document.getElementById('addSolutionModal');
    addModalElement.addEventListener('hidden.bs.modal', function () {
        document.getElementById('addSolutionForm').reset(); // Reset form
        document.getElementById('addImagePreview').src = '#';
        document.getElementById('addImagePreview').style.display = 'none';
    });
</script>