<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Review Platform</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('ukCompany.review-platform.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3"><label for="addName" class="form-label">Platform Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="addName" class="form-control" placeholder="e.g., Google" required></div>
                <div class="mb-3"><label for="addImage" class="form-label">Logo Image <span class="text-danger">*</span></label>
                    <input type="file" name="image" id="addImage" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">Required: 200x60 px. Max: 256KB</small>
                    <img id="addImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; width: 200px; height: 60px; object-fit: contain;"></div>
                <div class="mb-3"><label for="addRatingText" class="form-label">Rating Text <span class="text-danger">*</span></label>
                    <input type="text" name="rating_text" id="addRatingText" class="form-control" placeholder="e.g., Rated 5.0 Out Of 5.0" required></div>
                <div class="mb-3"><label for="addReviewLink" class="form-label">Review Link <span class="text-danger">*</span></label>
                    <input type="url" name="review_link" id="addReviewLink" class="form-control" value="#" required></div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Platform</button></div>
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
        document.getElementById('addImagePreview').src = '#';
        document.getElementById('addImagePreview').style.display = 'none';
    });
</script>