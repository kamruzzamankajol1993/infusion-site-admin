{{-- resources/views/admin/training_category/_partial/script.blade.php --}}
<script>
    // Use unique variable names to avoid conflicts if multiple tables are on the same page
    var currentPageTC = 1, searchTermTC = '', sortColumnTC = 'id', sortDirectionTC = 'desc';
    var editModalTC = new bootstrap.Modal(document.getElementById('editCategoryModalTC'));

    // --- Define Routes ---
    var routesTC = { // Unique prefix
        fetch: "{{ route('ajax.trainingCategory.data') }}",
        show: id => `{{ route('trainingCategory.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('trainingCategory.update', ':id') }}`.replace(':id', id), // Needs POST route
        delete: id => `{{ route('trainingCategory.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchTCData() { // Unique function name
        $.get(routesTC.fetch, {
            page: currentPageTC, search: searchTermTC, sort: sortColumnTC, direction: sortDirectionTC
        }, function (res) {
            let rows = ''; $('#tableBodyTC').empty(); // Use unique ID

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="4" class="text-center text-muted">No categories found.</td></tr>`;
                 $('#tableBodyTC').html(rows); $('#tableRowCountTC').text(`Showing 0 to 0 of 0 entries`); $('#paginationTC').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let imageHtml = item.image
                    ? `<img src="{{ asset('') }}${item.image}" alt="${item.name}" style="max-height: 60px; max-width: 100px; object-fit: contain;">`
                    : `<span class="text-muted small">No Image</span>`;

                let editBtnHtml = ''; let deleteBtnHtml = '';
                @if(Auth::user()->can('trainingCategoryUpdate'))
                editBtnHtml = `<button class="btn btn-sm btn-info btn-edit-tc btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `; // Use unique class
                @endif
                @if(Auth::user()->can('trainingCategoryDelete'))
                deleteBtnHtml = `<button class="btn btn-sm btn-danger btn-delete-tc btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>`; // Use unique class
                @endif

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${imageHtml}</td>
                    <td>${item.name || 'N/A'}</td>
                    <td>${editBtnHtml}${deleteBtnHtml}</td>
                </tr>`;
            });
            $('#tableBodyTC').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountTC').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderTCPagination(res); // Use unique function name
        });
    }

    // --- Render Pagination ---
    function renderTCPagination(res) { // Use unique function name
        let paginationHtml = '';
        if (res.last_page > 1) { /* ... Same pagination logic ... */
             paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-tc" href="#" data-page="1">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-tc" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-tc" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-tc" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-tc" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationTC').html(paginationHtml); // Use unique ID
    }

    // --- Search Input Handler ---
    $('#searchInputTC').on('keyup', function () { searchTermTC = $(this).val(); currentPageTC = 1; fetchTCData(); }); // Use unique vars/funcs

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableTC', function () { // Use unique class
        let col = $(this).data('column'); sortDirectionTC = (sortColumnTC === col && sortDirectionTC === 'asc') ? 'desc' : 'asc'; sortColumnTC = col;
        $('.sortableTC').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirectionTC === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchTCData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link-tc', function (e) { // Use unique class
        e.preventDefault(); const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPageTC) { currentPageTC = page; fetchTCData(); }
    });

    // --- Edit Button Click Handler ---
    $(document).on('click', '.btn-edit-tc', function () { // Use unique class
        const id = $(this).data('id');
        const preview = $('#editImagePreviewTC');
        const placeholder = $('#editPreviewPlaceholderTC');

        $('#editCategoryFormTC')[0].reset();
        $('#editCategoryFormTC .is-invalid').removeClass('is-invalid');
        $('#editCategoryFormTC .invalid-feedback').text('');
        preview.attr('src', '#').hide(); placeholder.show();
        preview.removeAttr('data-original-src');

        $.get(routesTC.show(id), function (data) {
            $('#editCategoryIdTC').val(data.id);
            $('#editNameTC').val(data.name);
            if (data.image_url) {
                preview.attr('src', data.image_url).show();
                preview.attr('data-original-src', data.image_url);
                placeholder.hide();
            } else {
                 preview.hide(); placeholder.show();
            }
            editModalTC.show();
        }).fail(function(xhr) { Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch category data.', 'error'); });
    });

    // --- Edit Form Submit Handler ---
    $('#editCategoryFormTC').on('submit', function (e) {
        e.preventDefault();
        const id = $('#editCategoryIdTC').val();
        const $submitBtn = $('#editSubmitBtnTC');
        const formData = new FormData(this);

        $('#editCategoryFormTC .is-invalid').removeClass('is-invalid');
        $('#editCategoryFormTC .invalid-feedback').text('');
        $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

        $.ajax({
            url: routesTC.update(id), method: 'POST', data: formData, processData: false, contentType: false,
            success: function(response) {
                Swal.fire({ toast: true, icon: 'success', title: response.message || 'Category updated!', position: 'top-end', showConfirmButton: false, timer: 3000 });
                editModalTC.hide();
                fetchTCData();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    if (errors.name) { $('#editNameTC').addClass('is-invalid'); $('#editNameErrorTC').text(errors.name[0]); }
                    if (errors.image) { $('#editImageTC').addClass('is-invalid'); $('#editImageErrorTC').text(errors.image[0]); }
                } else { Swal.fire('Error!', xhr.responseJSON?.error || 'Could not update category.', 'error'); }
            },
            complete: function() { $submitBtn.prop('disabled', false).text('Save Changes'); }
        });
    });

    // --- Single Delete Button Handler ---
    $(document).on('click', '.btn-delete-tc', function () { // Use unique class
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete this category?', text: "You won't be able to revert this!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routesTC.delete(id), method: 'DELETE', data: { _token: routesTC.token },
                    success: function(response) {
                        Swal.fire({ toast: true, icon: 'success', title: response.message || 'Category deleted!', position: 'top-end', showConfirmButton: false, timer: 3000 });
                        checkAndReloadTCData(); // Use unique function
                    },
                    error: function(xhr) { Swal.fire('Error!', xhr.responseJSON?.error || 'Could not delete category.', 'error'); }
                });
            }
        });
    });

    // --- Helper Function to Reload Data After Delete ---
    function checkAndReloadTCData() { // Use unique function name
        $.get(routesTC.fetch, { page: currentPageTC, search: searchTermTC, sort: sortColumnTC, direction: sortDirectionTC, check_empty: true }, function (res) {
             if (res.data.length === 0 && currentPageTC > 1) { currentPageTC--; }
            fetchTCData();
        });
    }

    // --- Initial Data Load ---
    fetchTCData(); // Use unique function name

</script>