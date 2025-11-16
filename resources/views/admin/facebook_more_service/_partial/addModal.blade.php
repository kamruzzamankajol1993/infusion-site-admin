<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Service Card</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('facebookPage.service.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addIconName" class="form-label">Icon Name <span class="text-danger">*</span></label>
                    <input type="text" name="icon_name" id="addIconName" class="form-control" value="mdi:check-circle" required>
                    <small class="form-text text-muted">Find icon names from <a href="https://icon-sets.iconify.design/" target="_blank">Iconify</a> (e.g., mdi:robot-happy-outline)</small>
                </div>
                <div class="mb-3">
                    <label for="addTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="Enter title" required>
                </div>
                <div class="mb-3">
                    <label for="addDescription" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea name="description" id="addDescription" rows="3" class="form-control" placeholder="Enter short description" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="addLinkText" class="form-label">Link Text <span class="text-danger">*</span></label>
                        <input type="text" name="link_text" id="addLinkText" class="form-control" value="Buy Now &rarr;" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="addLinkUrl" class="form-label">Link URL <span class="text-danger">*</span></label>
                        <input type="url" name="link_url" id="addLinkUrl" class="form-control" value="#" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Service</button></div>
        </form>
      </div>
    </div>
</div>
<script> document.getElementById('addModal').addEventListener('hidden.bs.modal', () => document.getElementById('addForm').reset()); </script>