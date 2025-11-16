{{-- resources/views/admin/why_us/_partial/script.blade.php --}}
<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';
    var editModal = new bootstrap.Modal(document.getElementById('editWhyUsModal'));

    // --- Pass Blade permissions to JS variables ---
    var canUpdateWhyUs = @json(Auth::user()->can('whyUsUpdate'));
    var canDeleteWhyUs = @json(Auth::user()->can('whyUsDelete'));

    // --- Define Routes ---
    var routes = {
        fetch: "{{ route('ajax.why-us.data') }}",
        show: id => `{{ route('why-us.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('why-us.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('why-us.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="4" class="text-center text-muted">No items found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let imageHtml = item.image
                    ? `<img src="{{ asset('') }}${item.image}" alt="${item.name}" style="width: 60px; height: 60px; object-fit: contain;">`
                    : `<span class="text-muted small">No Image</span>`;
                
                // --- Use JS variables for permissions ---
                let editBtnHtml = ''; let deleteBtnHtml = '';
                if (canUpdateWhyUs) {
                    editBtnHtml = `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `;
                }
                if (canDeleteWhyUs) {
                    deleteBtnHtml = `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>`;
                }

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

        $('#editWhyUsForm').attr('action', routes.update(id));

        // Reset modal state
        $('#editWhyUsForm')[0].reset();
        $('#editWhyUsForm .is-invalid').removeClass('is-invalid');
        $('#editWhyUsForm .invalid-feedback.d-block').remove();
        
        preview.attr('src', '#').hide();
        placeholder.show();
        preview.removeAttr('data-original-src'); 

        $.get(routes.show(id), function (data) {
            $('#editWhyUsId').val(data.id);
            $('#editName').val(data.name);
            
            if (data.image_url) {
                preview.attr('src', data.image_url).show();
                preview.attr('data-original-src', data.image_url); 
                placeholder.hide();
            } else {
                 preview.hide();
                 placeholder.show();
            }
            editModal.show();
        }).fail(function(xhr) {
             Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch item data.', 'error');
        });
    });

    // --- Single Delete Button Handler ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); 

        Swal.fire({
            title: 'Delete this item?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteForm = $('#delete-why-us-form'); 
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
            
            $('#editWhyUsForm').attr('action', routes.update(failedId));
            $('#editWhyUsId').val(failedId);
            
            editModal.show();
        @endif
    });

</script>