{{-- resources/views/admin/solution/_partial/script.blade.php --}}
<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';
    var editModal = new bootstrap.Modal(document.getElementById('editSolutionModal'));

    // --- Pass Blade permissions to JS variables ---
    var canUpdateSolution = @json(Auth::user()->can('solutionUpdate'));
    var canDeleteSolution = @json(Auth::user()->can('solutionDelete'));

    // --- Define Routes ---
    var routes = {
        fetch: "{{ route('ajax.solution.data') }}",
        show: id => `{{ route('solution.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('solution.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('solution.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();

            if (!res.data || res.data.length === 0) {
                 // --- Updated colspan to 4 ---
                 rows = `<tr><td colspan="4" class="text-center text-muted">No solutions found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                // *** UPDATED: Image style ***
                let imageHtml = item.image
                    ? `<img src="{{ asset('') }}${item.image}" alt="${item.name}" style="width: 60px; height: 60px; object-fit: contain;">`
                    : `<span class="text-muted small">No Image</span>`;
                
                // --- Use JS variables for permissions ---
                let editBtnHtml = ''; let deleteBtnHtml = '';
                if (canUpdateSolution) {
                    editBtnHtml = `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `;
                }
                if (canDeleteSolution) {
                    deleteBtnHtml = `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>`;
                }

                // --- Updated row template (removed shape) ---
                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${imageHtml}</td>
                    <td>${item.name || 'N/A'}</td>
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
        const preview = $('#editImagePreview');
        const placeholder = $('#editPreviewPlaceholder');

        // --- Set the form's action URL dynamically ---
        $('#editSolutionForm').attr('action', routes.update(id));

        // Reset modal state
        $('#editSolutionForm')[0].reset();
        $('#editSolutionForm .is-invalid').removeClass('is-invalid');
        $('#editSolutionForm .invalid-feedback.d-block').remove();
        
        preview.attr('src', '#').hide();
        placeholder.show();
        preview.removeAttr('data-original-src'); // Clear previous original src


        $.get(routes.show(id), function (data) {
            $('#editSolutionId').val(data.id);
            $('#editName').val(data.name);
            
            if (data.image_url) {
                preview.attr('src', data.image_url).show();
                preview.attr('data-original-src', data.image_url); // Store original for reset
                placeholder.hide();
            } else {
                 preview.hide();
                 placeholder.show();
            }
            editModal.show();
        }).fail(function(xhr) {
             Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch solution data.', 'error');
        });
    });

    // --- Single Delete Button Handler ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); // Get the correct URL

        Swal.fire({
            title: 'Delete this solution?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Find the hidden form, set its action, and submit it
                const deleteForm = $('#delete-solution-form'); 
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();
            }
        });
    });

    // --- Helper Function to Reload Data After Delete ---
    function checkAndReloadData() {
        $.get(routes.fetch, { page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection, check_empty: true }, function (res) {
             if (res.data.length === 0 && currentPage > 1) { currentPage--; }
            fetchData();
        });
    }

    // --- Initial Data Load ---
    fetchData();

    // --- Re-open edit modal on validation error ---
    $(document).ready(function() {
        @if (session('error_modal_id') && $errors->update->any())
            var failedId = {{ session('error_modal_id') }};
            // Set the form action
            $('#editSolutionForm').attr('action', routes.update(failedId));
            
            // Manually re-populate 'old' data from session
            $('#editSolutionId').val(failedId);
            // Note: 'old' data is already set in the modal's 'value' attribute
            
            // Show the modal
            editModal.show();
        @endif
    });

</script>