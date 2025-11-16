<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Solution</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('graphicDesign.solution.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="Enter title" required>
                    @error('title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="addIcon" class="form-label">Icon <span class="text-danger">*</span></label>
                    <input type="file" name="icon" id="addIcon" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Required: 80x80 px. Max: 256KB</small>
                    <img id="addIconPreview" src="#" alt="Icon Preview" class="img-thumbnail mt-2" style="display:none; width: 80px; height: 80px; object-fit: contain;">
                     @error('icon') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label for="addDescription" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="addDescription" rows="3" class="form-control" placeholder="Enter short description" required></textarea>
                    @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Solution</button></div>
        </form>
      </div>
    </div>
</div>
<script>
    document.getElementById('addIcon').addEventListener('change', e => {
        const preview = document.getElementById('addIconPreview');
        if (e.target.files[0]) {
            const reader = new FileReader();
            reader.onloadend = () => { preview.src = reader.result; preview.style.display = 'block'; };
            reader.readAsDataURL(e.target.files[0]);
        } else { preview.src = '#'; preview.style.display = 'none'; }
    });
    document.getElementById('addModal').addEventListener('hidden.bs.modal', () => {
        document.getElementById('addForm').reset();
        document.getElementById('addIconPreview').src = '#';
        document.getElementById('addIconPreview').style.display = 'none';
    });
</script>