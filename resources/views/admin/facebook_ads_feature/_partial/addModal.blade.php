<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5" id="addModalLabel">Add New Feature Card</h1><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <form id="addForm" method="post" action="{{ route('facebookAds.feature.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addIconName" class="form-label">Icon Name <span class="text-danger">*</span></label>
                    <input type="text" name="icon_name" id="addIconName" class="form-control" value="mdi:target-arrow" required>
                    <small class="form-text text-muted">Find icon names from <a href="https://icon-sets.iconify.design/" target="_blank">Iconify</a> (e.g., mdi:target-arrow)</small>
                </div>
                <div class="mb-3">
                    <label for="addTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="addTitle" class="form-control" placeholder="Enter title" required>
                </div>
                <div class="mb-3">
                    <label for="addDescription" class="form-label">Description (Optional)</label>
                    <textarea name="description" id="addDescription" rows="2" class="form-control" placeholder="Enter short description"></textarea>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save Feature</button></div>
        </form>
      </div>
    </div>
</div>
<script> document.getElementById('addModal').addEventListener('hidden.bs.modal', () => document.getElementById('addForm').reset()); </script>