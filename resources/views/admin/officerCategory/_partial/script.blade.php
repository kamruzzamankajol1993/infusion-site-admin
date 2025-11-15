<script>
    var modalOne = new bootstrap.Modal(document.getElementById('editUserModal'));
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';

    var routes = {
        fetch: "{{ route('ajax.officerCategorytable.data') }}",
        show: id => `{{ route('officerCategory.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('officerCategory.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('officerCategory.destroy', ':id') }}`.replace(':id', id),
        csrf: "{{ csrf_token() }}"
    };

    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage,
            search: searchTerm,
            sort: sortColumn,
            direction: sortDirection
        }, function (res) {
            let rows = '';
            res.data.forEach((user, i) => {
                
                // Get parent name, or show 'N/A' if it's null
                let parentName = user.parent ? user.parent.name : '<span class="text-muted">N/A</span>';

                rows += `<tr>
                    <td>${(res.current_page - 1) * 10 + i + 1}</td>
                    <td>${user.name}</td>
                    <td>${parentName}</td>
                    <td>
                        <button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${user.id}"><i class="fa fa-edit"></i></button>
                        
                        <form action="${routes.delete(user.id)}" method="POST" style="display:inline;" class="delete-form">
                            <input type="hidden" name="_token" value="${routes.csrf}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-danger btn-custom-sm"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>`;
            });
            $('#tableBody').html(rows);

            let paginationHtml = '';

            if (res.last_page > 1) {
                paginationHtml += `
                    <li class="page-item ${res.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="1">First</a>
                    </li>
                    <li class="page-item ${res.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${res.current_page - 1}">Prev</a>
                    </li>`;

                const startPage = Math.max(1, res.current_page - 2);
                const endPage = Math.min(res.last_page, res.current_page + 2);

                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `
                        <li class="page-item ${i === res.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>`;
                }

                paginationHtml += `
                    <li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${res.current_page + 1}">Next</a>
                    </li>
                    <li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${res.last_page}">Last</a>
                    </li>`;
            }

            $('#pagination').html(paginationHtml);
        });
    }

    $('#searchInput').on('keyup', function () {
        searchTerm = $(this).val();
        currentPage = 1;
        fetchData();
    });

    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column');
        sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
        sortColumn = col;
        fetchData();
    });

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        currentPage = parseInt($(this).data('page'));
        fetchData();
    });

    // --- MODIFIED ---
    // Update the .btn-edit click handler to set form action and populate data
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        const updateUrl = routes.update(id); // Get the URL for the form action
        
        $.get(routes.show(id), function (user) {
            // Set the form's action attribute
            $('#editUserForm').attr('action', updateUrl);
            
            // Populate the form fields
            $('#editName').val(user.name);
            $('#editParentId').val(user.parent_id);
            
            modalOne.show();
        });
    });

    // --- REMOVED ---
    // The AJAX submit handler for #editUserForm has been completely removed.
    
    
    // --- UNCHANGED ---
    // The delete form handler
    $(document).on('submit', '.delete-form', function (e) {
        e.preventDefault(); // Stop the form from submitting
        var form = this; // Get the form element

        Swal.fire({
            title: 'Delete this category?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form normally
                form.submit();
            }
        });
    });


    // --- MODIFIED ---
    // Simplified the modal hide event.
    $('#editUserModal').on('hidden.bs.modal', function () {
        $('#editUserForm')[0].reset();
        $('#editUserForm').attr('action', '');
    });

    fetchData();
</script>