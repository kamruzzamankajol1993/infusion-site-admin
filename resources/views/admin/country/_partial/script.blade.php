{{-- resources/views/admin/country/_partial/script.blade.php --}}
<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'name', sortDirection = 'asc';
    var editModal = new bootstrap.Modal(document.getElementById('editCountryModal')); // Bootstrap 5 modal instance

    // --- MODIFIED: Pass Blade permissions to JS variables ---
    var canUpdateCountry = @json(Auth::user()->can('countryUpdate'));
    var canDeleteCountry = @json(Auth::user()->can('countryDelete'));

    // --- Define Routes ---
    var routes = {
        fetch: "{{ route('ajax.country.data') }}",
        show: id => `{{ route('country.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('country.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('country.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage,
            search: searchTerm,
            sort: sortColumn,
            direction: sortDirection
        }, function (res) {
            let rows = '';
            $('#tableBody').empty();

            if (!res.data || res.data.length === 0) {
                 {{-- MODIFIED: Colspan changed from 4 to 5 --}}
                 rows = `<tr><td colspan="5" class="text-center text-muted">No countries found.</td></tr>`;
                 $('#tableBody').html(rows);
                 $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`);
                 $('#pagination').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let statusBadge = item.status == 1
                    ? `<span class="badge bg-success-soft text-success">Active</span>`
                    : `<span class="badge bg-danger-soft text-danger">Inactive</span>`;

                let editBtnHtml = '';
                let deleteBtnHtml = '';

                // --- MODIFIED: Use JS variables for permissions ---
                if (canUpdateCountry) {
                    editBtnHtml = `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `;
                }
                if (canDeleteCountry) {
                    deleteBtnHtml = `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>`;
                }
                // --- END MODIFICATION ---

                {{-- MODIFIED: Added item.iso3 --}}
                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${item.name || 'N/A'}</td>
                    <td>${item.iso3 || 'N/A'}</td>
                    <td>${statusBadge}</td>
                    <td>${editBtnHtml}${deleteBtnHtml}</td>
                </tr>`;
            });
            $('#tableBody').html(rows);

            // Update Row Count
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);

            // Render Pagination
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
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#pagination').html(paginationHtml);
    }

    // --- Search Input Handler ---
    $('#searchInput').on('keyup', function () {
        searchTerm = $(this).val();
        currentPage = 1;
        fetchData();
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column');
        sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
        sortColumn = col;
        $('.sortable').removeClass('sorting_asc sorting_desc');
        $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc');
        fetchData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
         if (!isNaN(page) && page !== currentPage) {
            currentPage = page;
            fetchData();
        }
    });

    // --- Edit Button Click Handler (Show Modal) ---
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        
        // --- MODIFIED: Set the form's action URL dynamically ---
        $('#editCountryForm').attr('action', routes.update(id));

        // --- MODIFIED: Clear previous Blade errors ---
        $('#editCountryForm .is-invalid').removeClass('is-invalid');
        $('#editCountryForm .invalid-feedback.d-block').remove();
        $('#editCountryForm .alert-danger').remove();

        $.get(routes.show(id), function (data) {
            $('#editCountryId').val(data.id);
            $('#editName').val(data.name);
            $('#editIso3').val(data.iso3); {{-- <-- ADDED --}}
            $('#editStatus').val(data.status ? '1' : '0'); // Set select value
            editModal.show();
        }).fail(function(xhr) {
             Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch country data.', 'error');
        });
    });

    // --- REMOVED: Edit Form Submit Handler (AJAX) ---
    // The '$('#editCountryForm').on('submit', ...)' block has been deleted.

     // --- Clear edit modal on hide ---
     $('#editCountryModal').on('hidden.bs.modal', function () {
        $('#editCountryForm')[0].reset(); // Reset form fields
        // --- MODIFIED: Clear Blade errors ---
        $('#editCountryForm .is-invalid').removeClass('is-invalid');
        $('#editCountryForm .invalid-feedback.d-block').remove();
        $('#editCountryForm .alert-danger').remove();
         $('#editSubmitBtn').prop('disabled', false).text('Save Changes'); 
    });

    // --- Single Delete Button Handler (Already uses form submit, NO CHANGE NEEDED) ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); 

        Swal.fire({
            title: 'Delete this country?',
            text: "Ensure this country is not associated with any projects first. You won't be able to revert this!", 
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteForm = $('#delete-country-form'); 
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();
            }
        });
    });

    // --- Helper Function to Reload Data After Delete ---
    function checkAndReloadData() {
        $.get(routes.fetch, { page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection, check_empty: true }, function (res) {
             if (res.data.length === 0 && currentPage > 1) {
                currentPage--;
            }
            fetchData();
        });
    }

    // --- Initial Data Load ---
    fetchData();

    // --- ADDED: Re-open edit modal on validation error ---
    $(document).ready(function() {
        @if (session('error_modal_id') && $errors->update->any())
            var failedId = {{ session('error_modal_id') }};
            // Set the form action
            $('#editCountryForm').attr('action', routes.update(failedId));
            
            // Set the ID
            $('#editCountryId').val(failedId);
            // Note: The 'old()' values are already set in the modal's HTML
            
            // Show the modal
            editModal.show();
        @endif
    });
    // --- END ADDED SCRIPT ---

</script>