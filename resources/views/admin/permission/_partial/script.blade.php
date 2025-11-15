{{-- resources/views/admin/permission/_partial/script.blade.php --}}
<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'group_name', sortDirection = 'asc';
    var selectedIds = []; // Array for selected checkbox IDs

    var routes = {
        fetch: "{{ route('ajax.permissiontable.data') }}",
        edit: id => `{{ route('permissions.edit', ':id') }}`.replace(':id', id),
        // update: id => `{{ route('permissions.update', ':id') }}`.replace(':id', id), // Update route needed for edit page, not here
        delete: id => `{{ route('permissions.destroy', ':id') }}`.replace(':id', id),
        deleteMultiple: "{{ route('permissions.destroyMultiple') }}", // Route for multiple delete
        token: "{{ csrf_token() }}"
    };

    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection, perPage: 10
        }, function (res) {
            let rows = ''; $('#tableBody').empty();

             if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="5" class="text-center text-muted">No permissions found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 resetCheckboxes(); // Reset checkbox state
                 return;
            }

            res.data.forEach((group, index) => {
                let permissions = group.permissions.map(p => `<span class="badge bg-primary-soft text-primary me-1 mb-1">${p.name}</span>`).join(' '); // Using softer badge
                 // Check if the current item ID is in the selectedIds array
                let isChecked = selectedIds.includes(group.first_permission_id.toString());

                rows += `
                    <tr>
                        <td>
                            <input class="form-check-input rowCheckbox" type="checkbox" value="${group.first_permission_id}" ${isChecked ? 'checked' : ''}>
                        </td>
                        <td>${(res.current_page - 1) * 10 + index + 1}</td>
                        <td>${group.group_name}</td>
                        <td>${permissions}</td>
                        <td>`;
                if(res.can_edit) rows += `<a href="${routes.edit(group.first_permission_id)}" class="btn btn-sm btn-info btn-custom-sm" title="Edit"><i class="fa fa-edit"></i></a> `; // Styled edit button
                if(res.can_delete) rows += `<button class="btn-delete btn btn-sm btn-danger btn-custom-sm" data-id="${group.first_permission_id}" title="Delete"><i class="fa fa-trash"></i></button>`; // Styled delete button
                rows += `</td>
                    </tr>`;
            });
            $('#tableBody').html(rows);

            // Update Row Count
             const startEntry = (res.current_page - 1) * 10 + 1; // Assuming perPage is 10
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);

            // Pagination
            renderPagination(res);
            updateDeleteSelectedButton(); // Update button state
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
        let col = $(this).data('column'); sortDirection = (sortColumn === col && sortDirection === 'asc') ? 'desc' : 'asc'; sortColumn = col;
         $('.sortable').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc');
        fetchData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault(); const page = $(this).data('page');
        if (page && !$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) { currentPage = page; fetchData(); }
    });


    // --- Checkbox Handling ---
    $(document).on('change', '.rowCheckbox', function() {
        const id = $(this).val();
        if ($(this).is(':checked')) { if (!selectedIds.includes(id)) selectedIds.push(id); }
        else { selectedIds = selectedIds.filter(selectedId => selectedId !== id); }
        updateDeleteSelectedButton();
        $('#checkAll').prop('checked', $('.rowCheckbox:checked').length === $('.rowCheckbox').length && $('.rowCheckbox').length > 0);
    });

    $('#checkAll').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.rowCheckbox').prop('checked', isChecked);
        selectedIds = [];
        if (isChecked) { $('.rowCheckbox').each(function() { selectedIds.push($(this).val()); }); }
        updateDeleteSelectedButton();
    });

    function updateDeleteSelectedButton() {
        if (selectedIds.length > 0) $('#deleteSelectedBtn').show();
        else $('#deleteSelectedBtn').hide();
    }

    function resetCheckboxes() {
        selectedIds = [];
        $('#checkAll').prop('checked', false);
        $('.rowCheckbox').prop('checked', false);
        updateDeleteSelectedButton();
    }


    // --- Single Delete Button Handler ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete this Permission Group?', // Updated text
            text: "All permissions within this group will be deleted!", // Added warning
            icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routes.delete(id), method: 'DELETE', data: { _token: routes.token },
                    success: function (response) {
                        Swal.fire({ toast: true, icon: 'success', title: response.message || 'Group deleted!', position: 'top-end', showConfirmButton: false, timer: 3000 });
                         selectedIds = selectedIds.filter(selectedId => selectedId !== id.toString()); // Remove if selected
                         updateDeleteSelectedButton();
                        checkAndReloadData();
                    },
                    error: function (xhr) { Swal.fire('Error!', xhr.responseJSON?.error || 'Could not delete group.', 'error'); }
                });
            }
        });
    });

     // --- Delete Selected Button Handler ---
    $('#deleteSelectedBtn').on('click', function() {
        if (selectedIds.length === 0) {
            Swal.fire('No Selection', 'Please select permission groups to delete.', 'info');
            return;
        }

        Swal.fire({
            title: `Delete ${selectedIds.length} selected groups?`,
            text: "All permissions within these groups will be deleted!",
            icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routes.deleteMultiple, // Use the multiple delete route
                    method: 'DELETE', // Match the route method
                    data: { _token: routes.token, ids: selectedIds }, // Send array of IDs
                    success: function(response) {
                        Swal.fire({ toast: true, icon: 'success', title: response.message || 'Selected groups deleted!', position: 'top-end', showConfirmButton: false, timer: 3000 });
                        resetCheckboxes(); // Clear selection
                        checkAndReloadData(); // Reload table
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.error || 'Could not delete selected groups.', 'error');
                    }
                });
            }
        });
    });


    // --- Helper to Reload Data After Delete ---
    function checkAndReloadData() {
        $.get(routes.fetch, { page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection, check_empty: true }, function (res) {
             if (res.data.length === 0 && currentPage > 1) currentPage--;
            fetchData();
        });
    }


    // --- Initial Data Load ---
    fetchData();
</script>

{{-- Excel/PDF Export Script (Keep if needed) --}}
<script>
    var exportExcelUrl = "{{ route('downloadPermissionExcel') }}";
    var exportPdfUrl = "{{ route('downloadPermissionPdf') }}";
    // Add button listeners if you have export buttons
    $('#exportExcelBtn').on('click', function() { window.location.href = exportExcelUrl; });
    $('#exportPdfBtn').on('click', function() { window.location.href = exportPdfUrl; });
</script>