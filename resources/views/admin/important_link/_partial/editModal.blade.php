<div class="modal fade" id="editLinkModal" tabindex="-1" aria-labelledby="editLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editLinkModalLabel">Edit Important Link</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editLinkForm" method="POST" action=""> {{-- Action set by JS --}}
            @csrf
            @method('PUT')
            <input type="hidden" id="editLinkId" name="link_id"> {{-- Optional: Keep track if needed --}}
            <div class="modal-body">
                 <div class="mb-3">
                    <label for="editTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="editTitle" class="form-control" placeholder="Enter link title" required>
                    <div class="invalid-feedback">Title is required.</div>
                </div>
                 <div class="mb-3">
                    <label for="editLink" class="form-label">Link (URL) <span class="text-danger">*</span></label>
                    <input type="url" name="link" id="editLink" class="form-control" placeholder="https://example.com" required>
                    <div class="invalid-feedback">Please enter a valid URL.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>