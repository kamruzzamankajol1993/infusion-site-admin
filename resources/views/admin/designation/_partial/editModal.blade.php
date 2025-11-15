<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        {{-- MODIFIED: Use standard form post with empty action --}}
        <form id="editUserForm" class="modal-content" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" id="editUserId">
            <div class="modal-header">
                <h5 class="modal-title">Edit Designation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Name</label>
                    {{-- MODIFIED: Added old() and @error class --}}
                    <input type="text" id="editName" name="name" class="form-control @error('name', 'update') is-invalid @enderror" value="{{ old('name') }}" required>
                    {{-- MODIFIED: Added Blade @error directive for 'update' bag --}}
                    @error('name', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- MODIFIED: General error display --}}
                @error('error', 'update')
                   <div class="alert alert-danger">{{ $message }}</div>
                @enderror
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>