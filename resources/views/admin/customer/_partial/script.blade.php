<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    var canUpdate = @json(Auth::user()->can('customerUpdate'));
    var canDelete = @json(Auth::user()->can('customerDelete'));
    var routes = {
        fetch: "{{ route('ajax.customer.data') }}",
        show: "{{ route('customer.show', ':id') }}",
        update: "{{ route('customer.update', ':id') }}",
        delete: "{{ route('customer.destroy', ':id') }}"
    };

    function fetchData() {
        $.get(routes.fetch, { page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection }, function (res) {
            let rows = ''; 
            if (!res.data || res.data.length === 0) {
                 $('#tableBody').html('<tr><td colspan="6" class="text-center text-muted">No customers found.</td></tr>');
                 return;
            }
            res.data.forEach((item, i) => {
                let sl = (res.current_page - 1) * res.per_page + i + 1;
                
                let editBtn = canUpdate ? `<button class="btn btn-sm btn-info btn-edit" data-id="${item.id}"><i class="bi bi-pencil-square"></i></button> ` : '';
                let delBtn = canDelete ? `<button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}"><i class="bi bi-trash"></i></button>` : '';
                
                rows += `<tr>
                    <td>${sl}</td>
                    <td>${item.name}</td>
                    <td>${item.email}</td>
                    <td>${item.phone || '-'}</td>
                    <td>${item.address || '-'}</td>
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
    $('.sortable').on('click', function() {
        sortColumn = $(this).data('column');
        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        fetchData();
    });

    $(document.body).on('click', '.btn-edit', function () {
        let id = $(this).data('id');
        $('#editForm').attr('action', routes.update.replace(':id', id));
        $.get(routes.show.replace(':id', id), function (data) {
            $('#editId').val(data.id);
            $('#editName').val(data.name);
            $('#editEmail').val(data.email);
            $('#editPhone').val(data.phone);
            $('#editAddress').val(data.address);
            editModal.show();
        });
    });

    $(document).on('click', '.btn-delete', function () {
        let id = $(this).data('id');
        if(confirm('Delete this customer account?')) {
            $('#delete-form').attr('action', routes.delete.replace(':id', id)).submit();
        }
    });

    fetchData();
</script>