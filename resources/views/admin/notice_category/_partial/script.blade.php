{{-- resources/views/admin/notice_category/_partial/script.blade.php --}}
<script>
    // Use unique variables
    var currentPageNC = 1, searchTermNC = '', sortColumnNC = 'name', sortDirectionNC = 'asc';
    var editModalNC = new bootstrap.Modal(document.getElementById('editCategoryModalNC'));

    // --- Define Routes ---
    var routesNC = { // Unique prefix
        fetch: "{{ route('ajax.noticeCategory.data') }}",
        show: id => `{{ route('noticeCategory.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('noticeCategory.update', ':id') }}`.replace(':id', id), // Kept for form action
        delete: id => `{{ route('noticeCategory.destroy', ':id') }}`.replace(':id', id), // Kept for form action
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchNCData() { // Unique function name
        $.get(routesNC.fetch, {
            page: currentPageNC, search: searchTermNC, sort: sortColumnNC, direction: sortDirectionNC
        }, function (res) {
            let rows = ''; $('#tableBodyNC').empty(); // Use unique ID

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="3" class="text-center text-muted">No categories found.</td></tr>`; // Colspan 3
                 $('#tableBodyNC').html(rows); $('#tableRowCountNC').text(`Showing 0 to 0 of 0 entries`); $('#paginationNC').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let editBtnHtml = ''; let deleteBtnHtml = '';
                @if(Auth::user()->can('noticeCategoryUpdate'))
                editBtnHtml = `<button class="btn btn-sm btn-info btn-edit-nc btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `; // Unique class
                @endif
                @if(Auth::user()->can('noticeCategoryDelete'))
                deleteBtnHtml = `<button class="btn btn-sm btn-danger btn-delete-nc btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>`; // Unique class
                @endif

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${item.name || 'N/A'}</td>
                    <td>${editBtnHtml}${deleteBtnHtml}</td>
                </tr>`;
            });
            $('#tableBodyNC').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountNC').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderNCPagination(res); // Use unique function name
        });
    }

    // --- Render Pagination ---
    function renderNCPagination(res) { // Use unique function name
        let paginationHtml = '';
        if (res.last_page > 1) { /* ... Same pagination logic ... */
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-nc" href="#" data-page="1">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-nc" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-nc" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-nc" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-nc" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationNC').html(paginationHtml); // Use unique ID
    }

    // --- Search Input Handler ---
    $('#searchInputNC').on('keyup', function () { searchTermNC = $(this).val(); currentPageNC = 1; fetchNCData(); }); // Use unique vars/funcs

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableNC', function () { // Use unique class
        let col = $(this).data('column'); sortDirectionNC = (sortColumnNC === col && sortDirectionNC === 'asc') ? 'desc' : 'asc'; sortColumnNC = col;
        $('.sortableNC').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirectionNC === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchNCData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link-nc', function (e) { // Use unique class
        e.preventDefault(); const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPageNC) { currentPageNC = page; fetchNCData(); }
    });

    // --- Edit Button Click Handler ---
    $(document).on('click', '.btn-edit-nc', function () { // Use unique class
        const id = $(this).data('id');
        
        // Target new form ID
        $('#editCategoryForm')[0].reset(); 
        
        // Remove Laravel errors (if any were showing)
        $('#editCategoryForm .text-danger').remove(); 

        $.get(routesNC.show(id), function (data) {
            // Target new input IDs
            $('#editCategoryId').val(data.id);
            $('#editName').val(data.name);
            
            // *** KEY CHANGE: Set the form's action URL ***
            $('#editCategoryForm').attr('action', routesNC.update(id));
            
            editModalNC.show();
        }).fail(function(xhr) { Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch category data.', 'error'); });
    });

    // --- Edit Form Submit Handler (REMOVED) ---
    // The form now submits normally via standard HTML.

    // --- Single Delete Button Handler ---
    $(document).on('click', '.btn-delete-nc', function () { // Use unique class
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete this category?', text: "You won't be able to revert this! Check if notices are linked.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a dynamic form to submit the DELETE request
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = routesNC.delete(id);
                form.style.display = 'none';

                // Add CSRF token
                let csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = routesNC.token;
                form.appendChild(csrfInput);

                // Add Method Spoofing
                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // --- Helper Function to Reload Data After Delete (REMOVED) ---
    // No longer needed as the page reloads on standard form submission.

    // --- Initial Data Load ---
    fetchNCData(); // Use unique function name

</script>