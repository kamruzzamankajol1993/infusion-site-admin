{{-- resources/views/admin/training_category/_partial/addModal.blade.php --}}
<div class="modal fade" id="addCategoryModalTC" tabindex="-1" aria-labelledby="addCategoryModalLabelTC" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addCategoryModalLabelTC">Add New Training Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addCategoryFormTC" method="post" action="{{ route('trainingCategory.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addNameTC" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="addNameTC" class="form-control" placeholder="Enter category name" required>
                    @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="addImageTC" class="form-label">Category Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="addImageTC" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Size: 750px (Width) x 422px (Height), Max: 1MB</small>
                    <img id="addImagePreviewTC" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; max-height: 150px; width: auto;">
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

<script>
    document.getElementById('addImageTC').addEventListener('change', function(event) {
        const preview = document.getElementById('addImagePreviewTC');
        const file = event.target.files[0]; const reader = new FileReader();
        reader.onloadend = function() { preview.src = reader.result; preview.style.display = 'block'; }
        if (file) { reader.readAsDataURL(file); } else { preview.src = '#'; preview.style.display = 'none'; }
    });
    var addModalElementTC = document.getElementById('addCategoryModalTC');
    addModalElementTC.addEventListener('hidden.bs.modal', function () {
        document.getElementById('addCategoryFormTC').reset();
        document.getElementById('addImagePreviewTC').src = '#';
        document.getElementById('addImagePreviewTC').style.display = 'none';
    });
</script>