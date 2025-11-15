{{-- resources/views/admin/project_category/_partial/script.blade.php --}}
<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';
    var editModalPC = new bootstrap.Modal(document.getElementById('editCategoryModal')); // Use unique ID

    // --- Pass Blade permissions to JS variables ---
    var canUpdatePC = @json(Auth::user()->can('projectCategoryUpdate'));
    var canDeletePC = @json(Auth::user()->can('projectCategoryDelete'));

    // --- Define Routes ---
    var routesPC = { // Use unique prefix
        fetch: "{{ route('ajax.projectCategory.data') }}",
        show: id => `{{ route('projectCategory.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('projectCategory.update', ':id') }}`.replace(':id', id), // Needs POST route
        delete: id => `{{ route('projectCategory.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchPCData() { // Use unique function name
        $.get(routesPC.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="4" class="text-center text-muted">No categories found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let imageHtml = item.image
                    ? `<img src="{{ asset('') }}${item.image}" alt="${item.name}" style="max-height: 60px; max-width: 100px; object-fit: contain;">` // Adjust size
                    : `<span class="text-muted small">No Image</span>`;

                // --- MODIFIED: Use JS variables for permissions ---
                let editBtnHtml = ''; let deleteBtnHtml = '';
                if (canUpdatePC) {
                    editBtnHtml = `<button class="btn btn-sm btn-info btn-edit-pc btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `;
                }
                if (canDeletePC) {
                    deleteBtnHtml = `<button class="btn btn-sm btn-danger btn-delete-pc btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>`;
                }
                // --- END MODIFICATION ---

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${imageHtml}</td>
                   <td>${item.name || 'N/A'} <strong class="text-primary">(ID: ${item.id})</strong></td>
                    <td>${editBtnHtml}${deleteBtnHtml}</td>
                </tr>`;
            });
            $('#tableBody').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderPCPagination(res); // Use unique function name
        });
    }

    // --- Render Pagination ---
    function renderPCPagination(res) { // Use unique function name
        let paginationHtml = '';
        if (res.last_page > 1) { /* ... Same pagination logic ... */
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
    $('#searchInput').on('keyup', function () { searchTerm = $(this).val(); currentPage = 1; fetchPCData(); });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column'); sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc'; sortColumn = col;
        $('.sortable').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchPCData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault(); const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPage) { currentPage = page; fetchPCData(); }
    });

    // --- Edit Button Click Handler ---
    $(document).on('click', '.btn-edit-pc', function () { // Use unique class
        const id = $(this).data('id');
        const preview = $('#editImagePreview');
        const placeholder = $('#editPreviewPlaceholderPC');

        // --- MODIFIED: Set the form's action URL dynamically ---
        $('#editCategoryForm').attr('action', routesPC.update(id));

        // Reset modal state
        $('#editCategoryForm')[0].reset();
        $('#editCategoryForm .is-invalid').removeClass('is-invalid');
        // Remove blade error messages if any exist
        $('#editCategoryForm .invalid-feedback.d-block').remove();
        preview.attr('src', '#').hide();
        placeholder.show();
        preview.removeAttr('data-original-src');


        $.get(routesPC.show(id), function (data) {
            $('#editCategoryId').val(data.id);
            $('#editName').val(data.name);
            if (data.image_url) {
                preview.attr('src', data.image_url).show();
                preview.attr('data-original-src', data.image_url);
                placeholder.hide();
            } else {
                 preview.hide(); placeholder.show();
            }
            editModalPC.show(); // Use unique modal instance
        }).fail(function(xhr) {
             Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch category data.', 'error');
        });
    });

    // --- REMOVED: Edit Form Submit Handler (AJAX) ---
    // The '$('#editCategoryForm').on('submit', ...)' block has been deleted.

    // --- ============ MODIFIED DELETE HANDLER ============ ---
    $(document).on('click', '.btn-delete-pc', function () { // Use unique class
        const id = $(this).data('id');
        const deleteUrl = routesPC.delete(id); // Get the correct URL

        Swal.fire({
            title: 'Delete this category?',
            text: "Associated projects might be affected. You won't be able to revert this!", // Updated text slightly
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Find the hidden form, set its action, and submit it
                const deleteForm = $('#delete-category-form'); // Use the new form ID
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();
            }
        });
    });
    // --- ============ END MODIFIED DELETE HANDLER ============ ---

    // --- Helper Function to Reload Data After Delete ---
    function checkAndReloadPCData() { // Use unique function name
        $.get(routesPC.fetch, { page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection, check_empty: true }, function (res) {
             if (res.data.length === 0 && currentPage > 1) { currentPage--; }
            fetchPCData();
        });
    }

    // --- Initial Data Load ---
    fetchPCData(); // Use unique function name

    // --- ADDED: Re-open edit modal on validation error ---
    $(document).ready(function() {
        @if (session('error_modal_id') && $errors->update->any())
            var failedId = {{ session('error_modal_id') }};
            // Set the form action
            $('#editCategoryForm').attr('action', routesPC.update(failedId));
            
            // Manually re-populate 'old' data
            $('#editCategoryId').val(failedId);
            $('#editName').val("{{ old('name') }}"); 
            // Note: We can't re-populate the file input or the image preview safely.
           
            
            // Show the modal
            editModalPC.show();
        @endif
    });
    // --- END ADDED SCRIPT ---

</script>