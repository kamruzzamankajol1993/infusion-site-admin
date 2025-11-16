{{-- resources/views/admin/team/_partial/addModal.blade.php --}}
<div class="modal fade" id="addTeamModal" tabindex="-1" aria-labelledby="addTeamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addTeamModalLabel">Add New Team Member</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addTeamForm" method="post" action="{{ route('team.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addName" class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="addName" class="form-control" placeholder="Enter name" required>
                    @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                 <div class="mb-3">
                    <label for="addDesignation" class="form-label">Designation <span class="text-danger">*</span></label>
                    <input type="text" name="designation" id="addDesignation" class="form-control" placeholder="Enter designation (e.g., CEO)" required>
                    @error('designation') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="addImage" class="form-label">Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="addImage" class="form-control" accept="image/*" required>
                    <small id="addImageHelpText" class="form-text text-muted">Required: 300x300 px. Max: 512KB</small>
                    <img id="addImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; width: 100px; height: 100px; object-fit: cover;">
                     @error('image') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Member</button>
            </div>
        </form>
      </div>
    </div>
  </div>

{{-- Script for add modal preview --}}
<script>
    document.getElementById('addImage').addEventListener('change', function(event) {
        const preview = document.getElementById('addImagePreview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
        }
        if (file) { reader.readAsDataURL(file); } 
        else { preview.src = '#'; preview.style.display = 'none'; }
    });

    var addModalElement = document.getElementById('addTeamModal');
    addModalElement.addEventListener('hidden.bs.modal', function () {
        document.getElementById('addTeamForm').reset();
        document.getElementById('addImagePreview').src = '#';
        document.getElementById('addImagePreview').style.display = 'none';
    });
</script>