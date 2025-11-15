{{-- resources/views/admin/country/_partial/editModal.blade.php --}}
<div class="modal fade" id="editCountryModal" tabindex="-1" aria-labelledby="editCountryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editCountryModalLabel">Edit Country</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- MODIFIED: Use standard form post with empty action --}}
        <form id="editCountryForm" method="POST" action="">
            @csrf
            @method('PUT') {{-- Method spoofing for PUT request --}}
            <input type="hidden" id="editCountryId" name="edit_id"> {{-- Changed name to avoid conflict, or just rely on action URL --}}
            
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Country Name <span class="text-danger">*</span></label>
                    {{-- MODIFIED: Added old() and @error class --}}
                    <input type="text" id="editName" name="name" class="form-control @error('name', 'update') is-invalid @enderror" value="{{ old('name') }}" required>
                    {{-- MODIFIED: Replaced AJAX div with Blade @error --}}
                    @error('name', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ADDED: ISO3 Field --}}
                <div class="mb-3">
                    <label for="editIso3" class="form-label">ISO3 Code <span class="text-danger">*</span></label>
                    <input type="text" id="editIso3" name="iso3" class="form-control @error('iso3', 'update') is-invalid @enderror" value="{{ old('iso3') }}" required maxlength="3">
                    @error('iso3', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                {{-- END ADDED BLOCK --}}

                <div class="mb-3">
                    <label for="editStatus" class="form-label">Status <span class="text-danger">*</span></label>
                    {{-- MODIFIED: Added @error class --}}
                    <select id="editStatus" name="status" class="form-select @error('status', 'update') is-invalid @enderror" required>
                        {{-- MODIFIED: Added old() checks --}}
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                     {{-- MODIFIED: Replaced AJAX div with Blade @error --}}
                     @error('status', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                 {{-- MODIFIED: General error display --}}
                 @error('error', 'update')
                    <div class="alert alert-danger">{{ $message }}</div>
                 @enderror
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="editSubmitBtn">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>