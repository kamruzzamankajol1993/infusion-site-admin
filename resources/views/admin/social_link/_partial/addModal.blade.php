{{-- resources/views/admin/social_link/_partial/addModal.blade.php --}}
<div class="modal fade" id="addSocialLinkModal" tabindex="-1" aria-labelledby="addSocialLinkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addSocialLinkModalLabel">Add New Social Link</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- Use standard form post for create --}}
        <form id="addSocialLinkForm" method="post" action="{{ route('socialLink.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addTitle" class="form-label">Platform Name <span class="text-danger">*</span></label>
                    <select name="title" id="addTitle" class="form-select" required>
                        <option value="" disabled selected>Select Platform...</option>
                        @foreach($socialMediaNames as $name)
                            <option value="{{ $name }}" {{ old('title') == $name ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('title') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                </div>
                 <div class="mb-3">
                    <label for="addLink" class="form-label">Link URL <span class="text-danger">*</span></label>
                    <input type="url" name="link" id="addLink" class="form-control" placeholder="https://..." required value="{{ old('link') }}">
                     @error('link') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
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