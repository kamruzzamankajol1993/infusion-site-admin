<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Campaign Type</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('facebookAds.campaign.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="e.g., Awareness Campaign" required>
                </div>
                <div class="mb-3">
                    <label for="addDescription" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="addDescription" class="form-control summernote" required>{{ old('description') }}</textarea>
                    <div id="addDescription-error" class="form-error"></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Campaign</button></div>
        </form>
      </div>
    </div>
</div>
<script>
    document.getElementById('addModal').addEventListener('hidden.bs.modal', () => {
        document.getElementById('addForm').reset();
        $('#addDescription').summernote('code', '');
        $('#addForm .is-invalid').removeClass('is-invalid');
        $('#addForm .form-error').empty();
    });
</script>