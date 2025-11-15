<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        
        <form id="editUserForm" class="modal-content" method="POST" action="">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">Edit Officer Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Name</label>
                    <input type="text" id="editName" name="name" class="form-control">
                </div>
               
                <div class="mb-3">
                    <label for="editParentId" class="form-label">Parent Category</label>
                    <select name="parent_id" id="editParentId" class="form-select">
                         <option value="">-- None (Top Level) --</option>
                         @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                         @endforeach
                    </select>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>