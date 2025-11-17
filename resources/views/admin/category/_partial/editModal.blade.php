<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" id="editName" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="editParentId" class="form-label">Parent Category</label>
                    <select name="parent_id" id="editParentId" class="form-select">
                        <option value="">-- None (Top Level) --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="editStatus" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="editStatus" class="form-select" required>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status', 'update') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="editSubmitBtn">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>