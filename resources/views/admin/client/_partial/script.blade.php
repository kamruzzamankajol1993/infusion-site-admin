{{-- resources/views/admin/client/_partial/script.blade.php --}}
<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';
    var editModal = new bootstrap.Modal(document.getElementById('editClientModal'));

    // --- MODIFIED: Pass Blade permissions to JS variables ---
    var canUpdateClient = @json(Auth::user()->can('clientUpdate'));
    var canDeleteClient = @json(Auth::user()->can('clientDelete'));

    // --- Define Routes ---
    var routes = {
        fetch: "{{ route('ajax.client.data') }}",
        show: id => `{{ route('client.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('client.update', ':id') }}`.replace(':id', id), // Needs POST route
        delete: id => `{{ route('client.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();

            if (!res.data || res.data.length === 0) {
                 // --- Updated colspan ---
                 rows = `<tr><td colspan="5" class="text-center text-muted">No clients found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let logoHtml = item.logo
                    ? `<img src="{{ asset('') }}${item.logo}" alt="${item.name}" style="max-height: 50px; max-width: 60px; object-fit: contain;">`
                    : `<span class="text-muted small">No Logo</span>`;
                
                // --- New: Format shape text ---
                let shapeText = 'N/A';
                if (item.image_shape) {
                    shapeText = item.image_shape.charAt(0).toUpperCase() + item.image_shape.slice(1);
                }

                // --- MODIFIED: Use JS variables for permissions ---
                let editBtnHtml = ''; let deleteBtnHtml = '';
                if (canUpdateClient) {
                    editBtnHtml = `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `;
                }
                if (canDeleteClient) {
                    deleteBtnHtml = `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>`;
                }
                // --- END MODIFICATION ---

                // --- Updated row template ---
                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${logoHtml}</td>
                    <td>${item.name || 'N/A'}</td>
                    <td>${shapeText}</td>
                    <td>${editBtnHtml}${deleteBtnHtml}</td>
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

    // --- Edit Button Click Handler (Show Modal & Fetch Data) ---
    $(document.body).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        const preview = $('#editLogoPreview');
        const placeholder = $('#editPreviewPlaceholder');

        // --- MODIFIED: Set the form's action URL dynamically ---
        $('#editClientForm').attr('action', routes.update(id));

        // Reset modal state
        $('#editClientForm')[0].reset();
        $('#editClientForm .is-invalid').removeClass('is-invalid');
        // --- MODIFIED: Remove Blade's validation error messages ---
        $('#editClientForm .invalid-feedback.d-block').remove();
        
        preview.attr('src', '#').hide();
        placeholder.show();
        preview.removeAttr('data-original-src'); // Clear previous original src


        $.get(routes.show(id), function (data) {
            $('#editClientId').val(data.id);
            $('#editName').val(data.name);
            // --- New: Set image shape dropdown and trigger change ---
            $('#editImageShape').val(data.image_shape);
            $('#editImageShape').trigger('change'); // Trigger change to update help text/preview
            
            if (data.logo_url) {
                preview.attr('src', data.logo_url).show();
                preview.attr('data-original-src', data.logo_url); // Store original for reset
                placeholder.hide();
            } else {
                 preview.hide();
                 placeholder.show();
            }
            editModal.show();
        }).fail(function(xhr) {
             Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch client data.', 'error');
        });
    });

    // --- REMOVED: Edit Form Submit Handler (AJAX) ---
    // The '$('#editClientForm').on('submit', ...)' block has been deleted.


    // --- Single Delete Button Handler ---
    // --- ============ (Delete handler remains the same) ============ ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); // Get the correct URL

        Swal.fire({
            title: 'Delete this client?',
            text: "Associated projects might be affected, and you won't be able to revert this!", // Updated text
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Find the hidden form, set its action, and submit it
                const deleteForm = $('#delete-client-form'); // Use the new form ID
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();
            }
        });
    });
    // --- ============ END DELETE HANDLER ============ ---

    // --- Helper Function to Reload Data After Delete ---
    function checkAndReloadData() { /* ... same as before ... */
        $.get(routes.fetch, { page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection, check_empty: true }, function (res) {
             if (res.data.length === 0 && currentPage > 1) { currentPage--; }
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
            $('#editClientForm').attr('action', routes.update(failedId));
            
            // Manually re-populate 'old' data from session
            $('#editClientId').val(failedId);
            // Note: 'old' data is already set in the modal's 'value' and 'selected' attributes
            
            // Trigger change to update help text/styles based on old() value
            $('#editImageShape').trigger('change'); 
            
            // Show the modal
            editModal.show();
        @endif
    });
    // --- END ADDED SCRIPT ---

</script>