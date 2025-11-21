<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5">Edit Coupon</h1><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="editForm" method="POST" action="">
            @csrf @method('PUT') <input type="hidden" id="editId">
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" id="editCode" class="form-control text-uppercase" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" id="editType" class="form-select">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percent">Percentage (%)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="editAmount" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Expire Date</label>
                    <input type="date" name="expire_date" id="editExpireDate" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="editStatus" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Update</button></div>
        </form>
      </div>
    </div>
</div>