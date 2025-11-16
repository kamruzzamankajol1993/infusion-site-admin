<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="addModalLabel">Add New Checklist Item</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addForm" method="post" action="{{ route('webSolution.checklist.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="Enter item title" required>
                    @error('title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Item</button>
            </div>
        </form>
      </div>
    </div>
</div>
<script>
    document.getElementById('addModal').addEventListener('hidden.bs.modal', () => document.getElementById('addForm').reset());
</script>