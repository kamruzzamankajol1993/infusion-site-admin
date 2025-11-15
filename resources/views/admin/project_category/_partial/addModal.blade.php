{{-- resources/views/admin/project_category/_partial/addModal.blade.php --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addCategoryModalLabel">Add New Project Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addCategoryForm" method="post" action="{{ route('projectCategory.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addName" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="addName" class="form-control" placeholder="Enter category name" required>
                    @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="addImage" class="form-label">Category Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="addImage" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Size: 750px (Width) x 422px (Height), Max: 1MB</small>
                    {{-- Basic Preview --}}
                    <img id="addImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; max-height: 150px; width: auto;">
                     @error('image') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
      </div>
    </div>
  </div>

{{-- Script for add modal preview --}}
<script>
    document.getElementById('addImage').addEventListener('change', function(event) {
        const preview = document.getElementById('addImagePreview');
        const file = event.target.files[0]; const reader = new FileReader();
        reader.onloadend = function() { preview.src = reader.result; preview.style.display = 'block'; }
        if (file) { reader.readAsDataURL(file); } else { preview.src = '#'; preview.style.display = 'none'; }
    });
    // Clear preview on modal close
    var addModalElementPC = document.getElementById('addCategoryModal'); // Use unique ID
    addModalElementPC.addEventListener('hidden.bs.modal', function () {
        document.getElementById('addCategoryForm').reset();
        document.getElementById('addImagePreview').src = '#';
        document.getElementById('addImagePreview').style.display = 'none';
    });
</script>