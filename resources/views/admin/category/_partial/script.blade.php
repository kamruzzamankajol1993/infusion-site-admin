<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';
    var addModal = new bootstrap.Modal(document.getElementById('addModal'));
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));

    var canUpdate = @json(Auth::user()->can('categoryUpdate'));
    var canDelete = @json(Auth::user()->can('categoryDelete'));

    var routes = {
        fetch: "{{ route('ajax.category.data') }}",
        show: id => `{{ route('category.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('category.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('category.destroy', ':id') }}`.replace(':id', id),
    };

    // --- Fetch and Render Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();
            let items = res.data || [];

            if (items.length === 0) {
                 rows = `<tr><td colspan="6" class="text-center text-muted">No categories found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }

            items.forEach((item, i) => {
                let sl = (res.current_page - 1) * res.per_page + i + 1;
                let status = item.status ? `<span class="badge bg-success">Active</span>` : `<span class="badge bg-danger">Inactive</span>`;
                let parent = item.parent ? item.parent.name : '<span class="text-muted">N/A</span>';
                
                let editBtn = canUpdate ? `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> ` : '';
                let deleteBtn = canDelete ? `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>` : '';

                rows += `<tr>
                    <td>${sl}</td>
                    <td>${item.name || 'N/A'}</td>
                    <td>${item.slug || 'N/A'}</td>
                    <td>${parent}</td>
                    <td>${status}</td>
                    <td>${editBtn}${deleteBtn}</td>
                </tr>`;
            });
            $('#tableBody').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * res.per_page + 1;
             const endEntry = startEntry + items.length - 1;
             $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderPagination(res);
        });
    }

    // --- Render Pagination ---
    function renderPagination(res) { 
        let paginationHtml = '';
        if (res.last_page > 1) {
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="1">&laquo;&laquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#pagination').html(paginationHtml);
    }

    // --- Search Input Handler ---
    $('#searchInput').on('keyup', function () { searchTerm = $(this).val(); currentPage = 1; fetchData(); });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column'); 
        // Don't allow sorting by 'slug' or 'parent' in this simple setup
        if (col === 'slug') return;
        if (col === 'parent') col = 'parent_id'; // Sort by ID instead of name

        sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc'; 
        sortColumn = col;
        $('.sortable').removeClass('sorting_asc sorting_desc'); 
        $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc'); 
        fetchData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault(); const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPage) { currentPage = page; fetchData(); }
    });

    // --- Edit Button Click Handler (Show Modal & Fetch Data) ---
    $(document.body).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $('#editForm').attr('action', routes.update(id));
        
        // Reset modal state
        $('#editForm')[0].reset();
        $('#editForm .is-invalid').removeClass('is-invalid');
        $('#editForm .text-danger').remove();

        $.get(routes.show(id), function (data) {
            $('#editId').val(data.id);
            $('#editName').val(data.name);
            $('#editParentId').val(data.parent_id || '');
            $('#editStatus').val(data.status ? '1' : '0');

            // Disable selecting self as parent
            $('#editParentId option').prop('disabled', false); // Re-enable all
            $('#editParentId option[value="' + data.id + '"]').prop('disabled', true); // Disable self
            
            editModal.show();
        }).fail(function(xhr) {
             Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch category data.', 'error');
        });
    });

    // --- Single Delete Button Handler ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); 

        Swal.fire({
            title: 'Delete this category?',
            text: "Child categories will become top-level. You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteForm = $('#delete-form');
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();
            }
        });
    });

    // --- Initial Data Load ---
    fetchData();

    // --- Re-open modal on validation error ---
    $(document).ready(function() {
        @if (session('error_modal') === 'addModal' && $errors->any())
            addModal.show();
        @endif

        @if (session('error_modal_id') && $errors->update->any())
            var failedId = {{ session('error_modal_id') }};
            $('#editForm').attr('action', routes.update(failedId));
            
            // Re-disable self as parent
            $('#editParentId option').prop('disabled', false);
            $('#editParentId option[value="' + failedId + '"]').prop('disabled', true);
            
            editModal.show();
        @endif
    });

    // --- Clear validation on modal close ---
    $('#addModal').on('hidden.bs.modal', function () {
        $('#addForm')[0].reset();
        $('#addForm .is-invalid').removeClass('is-invalid');
        $('#addForm .text-danger').remove();
    });
    $('#editModal').on('hidden.bs.modal', function () {
        $('#editForm')[0].reset();
        $('#editForm .is-invalid').removeClass('is-invalid');
        $('#editForm .text-danger').remove();
        $('#editParentId option').prop('disabled', false); // Re-enable all options
    });

</script>