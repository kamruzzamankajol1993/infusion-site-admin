<script>
    var modalOne = new bootstrap.Modal(document.getElementById('editUserModal'));
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';

    // --- ADDED: Pass permissions to JS ---
    var canUpdateDesignation = @json(Auth::user()->can('designationUpdate'));
    var canDeleteDesignation = @json(Auth::user()->can('designationDelete'));

    var routes = {
        fetch: "{{ route('ajax.designationtable.data') }}",
        show: id => `{{ route('designation.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('designation.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('designation.destroy', ':id') }}`.replace(':id', id),
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

                // --- MODIFIED: Check permissions for buttons ---
                let editBtnHtml = '';
                let deleteBtnHtml = '';

                if(canUpdateDesignation) {
                    editBtnHtml = `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${user.id}"><i class="fa fa-edit"></i></button> `;
                }
                if(canDeleteDesignation) {
                    deleteBtnHtml = `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${user.id}"><i class="fa fa-trash"></i></button>`;
                }
                // --- END MODIFICATION ---

                rows += `<tr>
                    <td>${(res.current_page - 1) * 10 + i + 1}</td>
                    <td>${user.name}</td>
                    <td>
                        ${editBtnHtml}
                        ${deleteBtnHtml}
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

                // Show max 5 pages around current
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

    //show method
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');

        // --- MODIFIED: Set form action and clear errors ---
        $('#editUserForm').attr('action', routes.update(id));
        $('#editUserForm .is-invalid').removeClass('is-invalid');
        $('#editUserForm .invalid-feedback.d-block').remove();
        $('#editUserForm .alert-danger').remove();
        // --- END MODIFICATION ---

        $.get(routes.show(id), function (user) {
            $('#editUserId').val(user.id);
            $('#editName').val(user.name);
            modalOne.show();
        });
    });

    // --- REMOVED: Edit Form Submit AJAX Handler ---
    // $('#editUserForm').on('submit', ...);

    // --- MODIFIED: Delete Button Handler ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); // Get the correct URL

        Swal.fire({
            title: 'Delete this designation?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Find the hidden form, set its action, and submit it
                const deleteForm = $('#delete-designation-form');
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();
            }
        });
    });
    // --- END MODIFIED DELETE HANDLER ---

    // --- MODIFIED: Modal hide event ---
    $('#editUserModal').on('hidden.bs.modal', function () { // Fixed event name
        $('#editUserForm')[0].reset();
        $('#editUserForm .is-invalid').removeClass('is-invalid');
        // --- MODIFIED: Clear Blade error messages ---
        $('#editUserForm .invalid-feedback.d-block').remove();
        $('#editUserForm .alert-danger').remove();
    });

    fetchData();

    // --- ADDED: Re-open edit modal on validation error ---
    $(document).ready(function() {
        @if (session('error_modal_id') && $errors->update->any())
            var failedId = {{ session('error_modal_id') }};
            // Set the form action
            $('#editUserForm').attr('action', routes.update(failedId));
            
            // Set the ID
            $('#editUserId').val(failedId);
            // Note: The 'old()' value is already set in the modal's HTML
            
            // Show the modal
            modalOne.show();
        @endif
    });
    // --- END ADDED SCRIPT ---
</script>

<script>
    var exportInvoicesUrl = "{{ route('downloadDesignationExcel') }}";
    var exportInvoicesUrlPdf = "{{ route('downloadDesignationPdf') }}";

    document.getElementById('invoiceFilter').addEventListener('change', function() {
    var selected = this.value;
    if (!selected) return;


    if( selected == 'excel'){

    var url = `${exportInvoicesUrl}`;
    }else{
 var url = `${exportInvoicesUrlPdf}`;
    }

    window.location.href = url;
});
</script>