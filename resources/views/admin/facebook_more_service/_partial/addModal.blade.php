<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Service Card</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('facebookPage.service.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addImage" class="form-label">Image (Icon) <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="addImage" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Required: 80x80 px. Max: 256KB</small>
                    <img id="addImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; width: 80px; height: 80px; object-fit: contain;">
                </div>
                <div class="mb-3">
                    <label for="addTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="Enter title" required>
                </div>
                <div class="mb-3">
                    <label for="addDescription" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="addDescription" rows="3" class="form-control" placeholder="Enter short description" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="addLinkText" class="form-label">Link Text <span class="text-danger">*</span></label>
                        <input type="text" name="link_text" id="addLinkText" class="form-control" value="Buy Now &rarr;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="addLinkUrl" class="form-label">Link URL <span class="text-danger">*</span></label>
                        <input type="url" name="link_url" id="addLinkUrl" class="form-control" value="#" required>
                    </div>
                </div>
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
    document.getElementById('addModal').addEventListener('hidden.bs.modal', () => { 
        document.getElementById('addForm').reset(); 
        document.getElementById('addImagePreview').style.display = 'none';
    }); 
</script>