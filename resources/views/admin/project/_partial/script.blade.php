{{-- resources/views/admin/project/_partial/script.blade.php --}}
<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';

    // --- Define Routes ---
    var routes = {
        fetch: "{{ route('ajax.project.data') }}",
        edit: id => `{{ route('project.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('project.show', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('project.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="8" class="text-center text-muted">No projects found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let statusBadge = '';
                switch (item.status) {
                    case 'pending': statusBadge = `<span class="badge bg-warning-soft text-warning">Pending</span>`; break;
                    case 'ongoing': statusBadge = `<span class="badge bg-info-soft text-info">Ongoing</span>`; break;
                    case 'complete': statusBadge = `<span class="badge bg-success-soft text-success">Completed</span>`; break;
                    default: statusBadge = `<span class="badge bg-secondary-soft text-secondary">${item.status}</span>`;
                }

                let signDate = item.agreement_signing_date ? new Date(item.agreement_signing_date).toLocaleDateString('en-GB') : 'N/A'; // Format: dd/mm/yyyy

                let editUrl = routes.edit(item.id);
                let showUrl = routes.show(item.id);
                let deleteId = item.id;

                let canUpdate = {{ Auth::user()->can('projectUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('projectDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('projectView') ? 'true' : 'false' }};

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${item.title || 'N/A'}</td>
                    <td>${item.category?.name || '<span class="text-muted small">N/A</span>'}</td>
                    <td>${item.client?.name || '<span class="text-muted small">N/A</span>'}</td>
                    <td>${item.country?.name || '<span class="text-muted small">N/A</span>'}</td>
                    <td>${statusBadge}</td>
                    <td>${signDate}</td>
                    <td>`;
                 if(canView) rows += `<a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm" title="View"><i class="fa fa-eye"></i></a> `;
                 if(canUpdate) rows += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm" title="Edit"><i class="fa fa-edit"></i></a> `;
                 if(canDelete) rows += `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${deleteId}" title="Delete"><i class="fa fa-trash"></i></button>`;
                 rows += `</td>
                </tr>`;
            });
            $('#tableBody').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderPagination(res);
        });
    }

    // --- Render Pagination --- (Same logic as before)
    function renderPagination(res) { /* ... */
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
        let col = $(this).data('column'); sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc'; sortColumn = col;
        $('.sortable').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault(); const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPage) { currentPage = page; fetchData(); }
    });

    // --- Single Delete Button Handler ---
    // --- ============ MODIFIED DELETE HANDLER ============ ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); // Get the correct URL

        Swal.fire({
            title: 'Delete this project?',
            text: "This will also delete associated gallery images. You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Find the hidden form, set its action, and submit it
                const deleteForm = $('#delete-project-form');
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();

                // --- REMOVED AJAX CALL ---
                // $.ajax({ ... });
            }
        });
    });
    // --- ============ END MODIFIED DELETE HANDLER ============ ---

    // --- Helper Function to Reload Data After Delete ---
    function checkAndReloadData() {
        $.get(routes.fetch, { page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection, check_empty: true }, function (res) {
             if (res.data.length === 0 && currentPage > 1) { currentPage--; }
            fetchData();
        });
    }

    // --- Initial Data Load ---
    fetchData();

</script>