{{-- resources/views/admin/notice_category/_partial/addModal.blade.php --}}
<div class="modal fade" id="addCategoryModalNC" tabindex="-1" aria-labelledby="addCategoryModalLabelNC" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addCategoryModalLabelNC">Add New Notice Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addCategoryFormNC" method="post" action="{{ route('noticeCategory.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addNameNC" class="form-label">Category Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="addNameNC" class="form-control" placeholder="Enter category name" required>
                    @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                {{-- No Status Field --}}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
      </div>
    </div>
  </div>