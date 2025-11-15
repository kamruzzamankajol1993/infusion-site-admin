{{-- resources/views/admin/country/_partial/addModal.blade.php --}}
<div class="modal fade" id="addCountryModal" tabindex="-1" aria-labelledby="addCountryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addCountryModalLabel">Add New Country</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- Use standard form post for create --}}
        <form id="addCountryForm" method="post" action="{{ route('country.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addName" class="form-label">Country Name <span class="text-danger">*</span></label>
                    {{-- MODIFIED: Added error class and old value --}}
                    <input type="text" name="name" id="addName" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter country name" required>
                    @error('name')
                       <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- ADDED: ISO3 Field --}}
                <div class="mb-3">
                    <label for="addIso3" class="form-label">ISO3 Code <span class="text-danger">*</span></label>
                    <input type="text" name="iso3" id="addIso3" class="form-control @error('iso3') is-invalid @enderror" value="{{ old('iso3') }}" placeholder="E.g., USA" required maxlength="3">
                    @error('iso3')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                {{-- END ADDED BLOCK --}}

                 {{-- Status (optional, defaults to Active) --}}
                 {{--
                 <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="addStatus" name="status" value="1" checked>
                    <label class="form-check-label" for="addStatus">Active</label>
                 </div>
                 --}}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Country</button>
            </div>
        </form>
      </div>
    </div>
  </div>