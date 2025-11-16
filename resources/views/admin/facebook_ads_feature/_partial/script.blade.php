<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'order', sortDirection = 'asc';
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    var canUpdate = @json(Auth::user()->can('facebookAdsFeatureUpdate'));
    var canDelete = @json(Auth::user()->can('facebookAdsFeatureDelete'));
    var routes = {
        fetch: "{{ route('facebookAds.feature.data') }}",
        show: id => `{{ route('facebookAds.feature.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('facebookAds.feature.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('facebookAds.feature.destroy', ':id') }}`.replace(':id', id),
        updateOrder: "{{ route('facebookAds.feature.updateOrder') }}",
        token: "{{ csrf_token() }}"
    };
    function fetchData() {
        $.get(routes.fetch, { page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection }, res => {
            let rows = ''; $('#tableBody').empty();
            let items = res.data || []; 
            if (!items || items.length === 0) {
                 rows = `<tr><td colspan="6" class="text-center text-muted">No features found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty(); return;
            }
            items.forEach((item, i) => {
                let sl = (res.current_page - 1) * res.per_page + i + 1;
                let editBtn = canUpdate ? `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> ` : '';
                let delBtn = canDelete ? `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>` : '';
                rows += `<tr>
                    <td>${sl}</td>
                    <td>${item.icon_name || 'N/A'}</td>
                    <td>${item.title || 'N/A'}</td>
                    <td>${item.description || 'N/A'}</td>
                    <td>${item.order}</td> 
                    <td>${editBtn}${delBtn}</td>
                </tr>`;
            });
            $('#tableBody').html(rows);
            const start = (res.current_page - 1) * res.per_page + 1, end = start + items.length - 1;
            $('#tableRowCount').text(`Showing ${start} to ${end} of ${res.total} entries`);
            renderPagination(res);
        });
    }
    function renderPagination(res) { 
        let links = '';
        if (res.last_page > 1) {
            links += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const start = Math.max(1, res.current_page - 2), end = Math.min(res.last_page, res.current_page + 2);
            for (let i = start; i <= end; i++) { links += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`; }
            links += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
        }
         $('#pagination').html(links);
    }
    $('#searchInput').on('keyup', () => { searchTerm = $('#searchInput').val(); currentPage = 1; fetchData(); });
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column'); sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc'; sortColumn = col;
        $('.sortable').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc'); 
        fetchData();
    });
    $(document).on('click', '.page-link', function (e) { e.preventDefault(); const page = parseInt($(this).data('page')); if (!isNaN(page) && page !== currentPage) { currentPage = page; fetchData(); } });
    $(document.body).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $('#editForm').attr('action', routes.update(id));
        $.get(routes.show(id), data => {
            $('#editId').val(data.id); 
            $('#editTitle').val(data.title);
            $('#editIconName').val(data.icon_name);
            $('#editDescription').val(data.description);
            editModal.show();
        }).fail(() => Swal.fire('Error!', 'Could not fetch feature data.', 'error'));
    });
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({ title: 'Delete this feature?', text: "You won't be able to revert this!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!'
        }).then(result => { if (result.isConfirmed) { $('#delete-form').attr('action', routes.delete(id)).submit(); } });
    });
    const sortableList = document.getElementById('sortableList');
    if (sortableList) {
        let sortable = Sortable.create(sortableList, { handle: '.sortable-handle', animation: 150 });
        $('#saveOrderBtn').on('click', function() {
            const $btn = $(this); $btn.prop('disabled', true).html('Saving...');
            $.ajax({
                url: routes.updateOrder, type: 'POST', data: { itemIds: sortable.toArray(), _token: routes.token },
                success: res => { Swal.fire('Success!', res.message, 'success'); setTimeout(() => window.location.reload(), 1000); },
                error: () => Swal.fire('Error!', 'Could not save the new order.', 'error'),
                complete: () => $btn.prop('disabled', false).html('<i data-feather="save" class="me-1" style="width:18px;"></i> Save Order')
            });
        });
    }
    $(document).ready(() => {
        if ($('#tableBody').length) { fetchData(); }
        @if (session('error_modal_id') && $errors->update->any())
            var failedId = {{ session('error_modal_id') }};
            $('#editForm').attr('action', routes.update(failedId)); $('#editId').val(failedId); editModal.show();
        @endif
    });
</script>