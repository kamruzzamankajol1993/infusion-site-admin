<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Testimonial</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('ukCompany.testimonial.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3"><label for="addName" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="addName" class="form-control" placeholder="Enter name" required></div>
                        <div class="mb-3"><label for="addDesignation" class="form-label">Designation</label>
                            <input type="text" name="designation" id="addDesignation" class="form-control" placeholder="e.g., CEO, Founder"></div>
                        <div class="mb-3"><label for="addRating" class="form-label">Rating <span class="text-danger">*</span></label>
                            <select name="rating" id="addRating" class="form-select" required>
                                <option value="5" selected>5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select></div>
                    </div>
                    <div class="col-md-4">
                        <label for="addImage" class="form-label">Image</label>
                        <input type="file" name="image" id="addImage" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Recommended: 100x100 px. Max: 512KB</small>
                        <img id="addImagePreview" src="#" alt="Image Preview" class="img-thumbnail mt-2" style="display:none; width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="addQuote" class="form-label">Quote <span class="text-danger">*</span></label>
                    <textarea name="quote" id="addQuote" rows="4" class="form-control" placeholder="Enter testimonial text" required></textarea>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Testimonial</button></div>
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