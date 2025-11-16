<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Service</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('webSolution.providing.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3"><label for="addTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="Enter title" required></div>
                <div class="mb-3"><label for="addImage" class="form-label">Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="addImage" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Required: 300x200 px. Max: 512KB</small>
                    <img id="addImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; width: 150px; height: 100px; object-fit: cover;"></div>
                <div class="mb-3"><label for="addButtonText" class="form-label">Button Text <span class="text-danger">*</span></label>
                    <input type="text" name="button_text" id="addButtonText" class="form-control" value="Order Now" required></div>
                <div class="mb-3"><label for="addButtonLink" class="form-label">Button Link <span class="text-danger">*</span></label>
                    <input type="text" name="button_link" id="addButtonLink" class="form-control" value="#" required></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Service</button></div>
        </form>
      </div>
    </div>
</div>
<script>
    document.getElementById('addImage').addEventListener('change', e => { const p = document.getElementById('addImagePreview');
        if (e.target.files[0]) { const r = new FileReader(); r.onloadend = () => { p.src = r.result; p.style.display = 'block'; }; r.readAsDataURL(e.target.files[0]); }
        else { p.src = '#'; p.style.display = 'none'; }
    });
    document.getElementById('addModal').addEventListener('hidden.bs.modal', () => { document.getElementById('addForm').reset(); document.getElementById('addImagePreview').style.display = 'none'; });
</script>