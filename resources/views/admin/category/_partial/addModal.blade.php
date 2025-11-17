<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addModalLabel">Add New Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addForm" method="post" action="{{ route('category.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addName" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="addName" class="form-control" placeholder="Enter category name" value="{{ old('name') }}" required>
                    @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="addParentId" class="form-label">Parent Category</label>
                    <select name="parent_id" id="addParentId" class="form-select">
                        <option value="">-- None (Top Level) --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="addStatus" class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" id="addStatus" class="form-select" required>
                        <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
      </div>
    </div>
  </div>