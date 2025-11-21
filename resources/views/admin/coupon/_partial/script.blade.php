<script>
    var currentPage = 1, searchTerm = '';
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    var canUpdate = @json(Auth::user()->can('couponUpdate'));
    var canDelete = @json(Auth::user()->can('couponDelete'));
    var routes = {
        fetch: "{{ route('ajax.coupons.data') }}",
        show: "{{ route('coupon.show', ':id') }}",
        update: "{{ route('coupon.update', ':id') }}",
        delete: "{{ route('coupon.destroy', ':id') }}"
    };

    function fetchData() {
        $.get(routes.fetch, { page: currentPage, search: searchTerm }, function (res) {
            let rows = ''; 
            if (!res.data || res.data.length === 0) {
                 $('#tableBody').html('<tr><td colspan="6" class="text-center text-muted">No coupons found.</td></tr>');
                 return;
            }
            res.data.forEach((item, i) => {
                let discount = item.type === 'percent' ? `${item.amount}%` : `৳${item.amount}`;
                let status = item.status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                let expiry = item.expire_date ? new Date(item.expire_date).toLocaleDateString() : '<span class="text-muted">Unlimited</span>';
                
                let editBtn = canUpdate ? `<button class="btn btn-sm btn-info btn-edit" data-id="${item.id}"><i class="bi bi-pencil-square"></i></button> ` : '';
                let delBtn = canDelete ? `<button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}"><i class="bi bi-trash"></i></button>` : '';
                
                rows += `<tr>
                    <td>${(res.current_page - 1) * res.per_page + i + 1}</td>
                    <td class="fw-bold text-primary">${item.code}</td>
                    <td>${discount}</td>
                    <td>${expiry}</td>
                    <td>${status}</td>
                    <td>${editBtn}${delBtn}</td>
                </tr>`;
            });
            $('#tableBody').html(rows);
            $('#tableRowCount').text(`Showing ${((res.current_page-1)*res.per_page)+1} to ${Math.min(res.current_page*res.per_page, res.total)} of ${res.total}`);
            renderPagination(res);
        });
    }

    function renderPagination(res) { 
        let html = '';
        if(res.prev_page_url) html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${res.current_page - 1})">&laquo;</a></li>`;
        html += `<li class="page-item active"><span class="page-link">${res.current_page}</span></li>`;
        if(res.next_page_url) html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${res.current_page + 1})">&raquo;</a></li>`;
        $('#pagination').html(html);
    }
    window.changePage = function(p) { currentPage = p; fetchData(); };
    $('#searchInput').on('keyup', function() { searchTerm = $(this).val(); currentPage = 1; fetchData(); });

    $(document.body).on('click', '.btn-edit', function () {
        let id = $(this).data('id');
        $('#editForm').attr('action', routes.update.replace(':id', id));
        $.get(routes.show.replace(':id', id), function (data) {
            $('#editId').val(data.id);
            $('#editCode').val(data.code);
            $('#editType').val(data.type);
            $('#editAmount').val(data.amount);
            $('#editExpireDate').val(data.expire_date ? data.expire_date.substring(0, 10) : '');
            $('#editStatus').val(data.status ? '1' : '0');
            editModal.show();
        });
    });

    $(document).on('click', '.btn-delete', function () {
        let id = $(this).data('id');
        if(confirm('Delete this coupon?')) {
            $('#delete-form').attr('action', routes.delete.replace(':id', id)).submit();
        }
    });

    fetchData();
</script>