{{-- resources/views/admin/notice_category/_partial/editModal.blade.php --}}
<div class="modal fade" id="editCategoryModalNC" tabindex="-1" aria-labelledby="editCategoryModalLabelNC" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editCategoryModalLabelNC">Edit Notice Category</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        {{-- 1. Changed ID, action will be set by JS --}}
        <form id="editCategoryForm" method="POST" action=""> 
            @csrf
            @method('PUT')
            
            {{-- 2. Changed ID --}}
            <input type="hidden" id="editCategoryId" name="id"> 
            
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Category Name <span class="text-danger">*</span></label>
                    
                    {{-- 3. Changed ID, added old('name') for validation failure --}}
                    <input type="text" id="editName" name="name" class="form-control" value="{{ old('name') }}" required>
                    
                    {{-- 4. Replaced AJAX error div with standard Laravel validation error --}}
                    @error('name') 
                        <div class="text-danger mt-1">{{ $message }}</div> 
                    @enderror
                </div>
                 {{-- No Status Field --}}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              
              {{-- 5. Removed ID, this is now a standard submit button --}}
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>