<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header"><h1 class="modal-title fs-5">Add Coupon</h1><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="addForm" method="post" action="{{ route('coupon.store') }}">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control text-uppercase" placeholder="e.g., SALE2025" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-select">
                            <option value="fixed">Fixed Amount</option>
                            <option value="percent">Percentage (%)</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Expire Date</label>
                    <input type="date" name="expire_date" class="form-control">
                    <small class="text-muted">Leave blank for no expiry</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button type="submit" class="btn btn-primary">Save</button></div>
        </form>
      </div>
    </div>
</div>