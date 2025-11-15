<div class="modal fade" id="addLinkModal" tabindex="-1" aria-labelledby="addLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addLinkModalLabel">Add New Important Link</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addLinkForm" method="post" action="{{ route('importantLink.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="Enter link title" required>
                    {{-- Basic client-side feedback --}}
                    <div class="invalid-feedback">Title is required.</div>
                </div>
                 <div class="mb-3">
                    <label for="addLink" class="form-label">Link (URL) <span class="text-danger">*</span></label>
                    <input type="url" name="link" id="addLink" class="form-control" placeholder="https://example.com" required>
                     {{-- Basic client-side feedback --}}
                    <div class="invalid-feedback">Please enter a valid URL (starting with http:// or https://).</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Link</button>
            </div>
        </form>
      </div>
    </div>
  </div>