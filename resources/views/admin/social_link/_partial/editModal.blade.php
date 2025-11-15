{{-- resources/views/admin/social_link/_partial/editModal.blade.php --}}
<div class="modal fade" id="editSocialLinkModal" tabindex="-1" aria-labelledby="editSocialLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editSocialLinkModalLabel">Edit Social Link</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- This form will be populated by JS, but submitted normally --}}
        <form id="editSocialLinkForm" method="POST" action=""> {{-- Action set by JS --}}
            @csrf
            @method('PUT') {{-- Method spoofing for a standard PUT request --}}
            {{-- We don't need the hidden ID input, as it's in the action URL --}}

            <div class="modal-body">
                 <div class="mb-3">
                    <label for="editTitle" class="form-label">Platform Name <span class="text-danger">*</span></label>
                    <select name="title" id="editTitle" class="form-select" required>
                        <option value="" disabled>Select Platform...</option>
                        @foreach($socialMediaNames as $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                     {{-- Validation errors will be shown by Laravel redirect, not here --}}
                </div>
                 <div class="mb-3">
                    <label for="editLink" class="form-label">Link URL <span class="text-danger">*</span></label>
                    <input type="url" name="link" id="editLink" class="form-control" placeholder="https://..." required>
                     {{-- Validation errors will be shown by Laravel redirect, not here --}}
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              {{-- This is now a standard submit button, no AJAX --}}
              <button type="submit" class="btn btn-primary" id="editSubmitBtnSL">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>