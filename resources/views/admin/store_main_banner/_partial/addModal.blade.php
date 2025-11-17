<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Main Banner</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('storeMainBanner.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addImage" class="form-label">Banner Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="addImage" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Required: 800x424 px. Max: 2MB</small>
                    <img id="addImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; max-width: 100%; height: auto;">
                </div>
                <div class="mb-3">
                    <label for="addLink" class="form-label">Link <span class="text-danger">*</span></label>
                    <input type="url" name="link" id="addLink" class="form-control" value="#" placeholder="https://example.com" required>
                </div>
                <div class="mb-3">
                    <label for="addStatus" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="addStatus" class="form-select" required>
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Banner</button></div>
        </form>
      </div>
    </div>
</div>
<script>
    document.getElementById('addImage').addEventListener('change', e => { const p = document.getElementById('addImagePreview');
        if (e.target.files[0]) { const r = new FileReader(); r.onloadend = () => { p.src = r.result; p.style.display = 'block'; }; r.readAsDataURL(e.target.files[0]); }
        else { p.src = '#'; p.style.display = 'none'; }
    });
    document.getElementById('addModal').addEventListener('hidden.bs.modal', () => {
        document.getElementById('addForm').reset();
        document.getElementById('addImagePreview').style.display = 'none';
    });
</script>