<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editForm" class="modal-content">
            <input type="hidden" id="editId">
            <div class="modal-header">
                <h5 class="modal-title">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Name</label>
                    <input type="text" id="editName" name="name" class="form-control">
                </div>

               <div class="mb-3">
    <label for="editParentIds" class="form-label">Parent Category</label>
    <select name="parent_ids[]" id="editParentIds" class="form-control" multiple style="display: none;">
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>
    {{-- This is the new custom component that the user will see --}}
    <div class="custom-select-container" data-target-select="#editParentIds"></div>
</div>

                <div class="mb-3">
                    <label for="editDescription" class="form-label">Description</label>
                    <textarea id="editDescription" name="description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="editImage" class="form-label">Image</label>
                    <input type="file" id="editImage" name="image" class="form-control" accept="image/webp">
                    <img id="imagePreview" src="" alt="Image Preview" class="img-thumbnail mt-2" style="max-width: 100px; display: none;">
                    <span class="text-danger" style="font-size: 12px;">image width: 50px and height: 50px, type: webp</span>
                </div>
  <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" role="switch" id="edit_is_featured" name="is_featured" value="1">
        <label class="form-check-label" for="edit_is_featured">Set as Featured Category</label>
    </div>
                <div class="mb-3">
                    <label for="editStatus" class="form-label">Status</label>
                    <select id="editStatus" name="status" class="form-control select2-basic">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>