{{-- resources/views/admin/why_choose_us/_partial/addModal.blade.php --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> {{-- Made modal large --}}
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addModalLabel">Add New "Why Choose Us" Item</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addForm" method="post" action="{{ route('why-choose-us.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="Enter title" required>
                    @error('title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="addImage" class="form-label">Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="addImage" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Required: 300px (Width) x 400px (Height). Max: 1MB</small>
                    <img id="addImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; width: 150px; height: 200px; object-fit: cover;">
                     @error('image') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="addDescription" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="addDescription" class="form-control summernote" required>{{ old('description') }}</textarea>
                    <div id="addDescription-error" class="form-error"></div>
                    @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Item</button>
            </div>
        </form>
      </div>
    </div>
  </div>

{{-- Script for add modal preview & summernote cleanup --}}
<script>
    document.getElementById('addImage').addEventListener('change', function(event) {
        const preview = document.getElementById('addImagePreview');
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onloadend = () => { preview.src = reader.result; preview.style.display = 'block'; };
        if (file) { reader.readAsDataURL(file); } else { preview.src = '#'; preview.style.display = 'none'; }
    });

    var addModalElement = document.getElementById('addModal');
    addModalElement.addEventListener('hidden.bs.modal', function () {
        document.getElementById('addForm').reset();
        document.getElementById('addImagePreview').src = '#';
        document.getElementById('addImagePreview').style.display = 'none';
        $('#addDescription').summernote('code', ''); // Clear summernote
        $('#addForm .is-invalid').removeClass('is-invalid');
        $('#addForm .form-error').empty();
    });
</script>