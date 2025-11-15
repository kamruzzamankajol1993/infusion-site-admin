<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addModalLabel">Add New Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm" method="post" action="{{ route('category.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-dark">Category Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Category Name" required>
                    </div>

                 <div class="mb-3">
    <label for="parentIds" class="form-label text-dark">Parent Category</label>
    <select name="parent_ids[]" id="parentIds" class="form-control" multiple style="display: none;">
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>
    {{-- This is the new custom component that the user will see --}}
    <div class="custom-select-container" data-target-select="#parentIds"></div>
</div>

                    <div class="mb-3">
                        <label class="form-label text-dark">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Enter Description"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-dark">Image</label>
                        <input type="file" accept="image/webp" name="image" class="form-control">
                        <span class="text-danger" style="font-size: 12px;">image width: 50px and height: 50px, type: webp</span>
                    </div>
<div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" role="switch" id="is_featured" name="is_featured" value="1">
        <label class="form-check-label" for="is_featured">Set as Featured Category</label>
    </div>
                    <div>
                        <button type="submit" class="btn btn-primary btn-sm w-md mt-4">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>