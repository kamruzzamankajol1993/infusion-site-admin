<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="editModalLabel">Edit FAQ</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT') <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editQuestion" class="form-label">Question <span class="text-danger">*</span></label>
                    <input type="text" id="editQuestion" name="question" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="editAnswer" class="form-label">Answer <span class="text-danger">*</span></label>
                    <textarea id="editAnswer" name="answer" class="form-control summernote" required>{{ old('answer') }}</textarea>
                    <div id="editAnswer-error" class="form-error"></div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Changes</button></div>
        </form>
      </div>
    </div>
</div>
<script>
    document.getElementById('editModal').addEventListener('hidden.bs.modal', () => {
         document.getElementById('editForm').reset();
         $('#editAnswer').summernote('code', '');
         $('#editForm .is-invalid').removeClass('is-invalid');
         $('#editForm .form-error').empty();
         $('#editForm .invalid-feedback.d-block').remove();
    });
</script>