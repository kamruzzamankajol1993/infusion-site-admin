<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';
    var editModal = new bootstrap.Modal(document.getElementById('editLinkModal')); // Reference to edit modal

    // --- Define Routes ---
    var routes = {
        fetch: "{{ route('ajax.importantLink.data') }}",
        show: id => `{{ route('importantLink.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('importantLink.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('importantLink.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="4" class="text-center text-muted">No important links found.</td></tr>`; // Updated colspan
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let editBtnHtml = ''; let deleteBtnHtml = '';
                @if(Auth::user()->can('importantLinkUpdate'))
                editBtnHtml = `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `;
                @endif

                // --- UPDATED: Delete button is now a form ---
                @if(Auth::user()->can('importantLinkDelete'))
                let deleteUrl = routes.delete(item.id);
                deleteBtnHtml = `<form action="${deleteUrl}" method="POST" class="d-inline-block form-delete-il" style="margin-bottom: 0;">
                                    <input type="hidden" name="_token" value="${routes.token}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger btn-custom-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                </form>`; // Unique class form-delete-il
                @endif
                // --- END UPDATE ---

                // Truncate long links for display
                let displayLink = item.link;
                if (displayLink.length > 50) {
                    displayLink = displayLink.substring(0, 47) + '...';
                }

                rows += `<tr>
                    <td>${(res.current_page - 1) * res.per_page + i + 1}</td>
                    <td>${item.title || 'N/A'}</td>
                    <td><a href="${item.link}" target="_blank" title="${item.link}">${displayLink || 'N/A'}</a></td>
                    <td>${editBtnHtml}${deleteBtnHtml}</td>
                </tr>`;
            });
            $('#tableBody').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * res.per_page + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
             renderPagination(res);
        });
    }

    // --- Render Pagination --- (Standard pagination logic)
    function renderPagination(res) {
        let paginationHtml = '';
        if (res.last_page > 1) {
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-nav" href="#" data-page="1">&laquo;&laquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-nav" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-nav" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-nav" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-nav" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#pagination').html(paginationHtml);
    }

    // --- Search Input Handler ---
    $('#searchInput').on('keyup', function () { searchTerm = $(this).val(); currentPage = 1; fetchData(); });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column'); sortDirection = (sortColumn === col && sortDirection === 'asc') ? 'desc' : 'asc'; sortColumn = col;
        $('.sortable').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link-nav', function (e) {
        e.preventDefault(); const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPage) { currentPage = page; fetchData(); }
    });

    // --- Edit Button Click Handler ---
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(routes.show(id), function (data) {
            $('#editLinkId').val(data.id);
            $('#editTitle').val(data.title);
            $('#editLink').val(data.link);
            $('#editLinkForm').attr('action', routes.update(id)); // Set form action
             // Clear previous validation states
            $('#editLinkForm').removeClass('was-validated');
            $('#editTitle, #editLink').removeClass('is-invalid');
            editModal.show();
        }).fail(function(jqXHR) {
             console.error("Fetch Error:", jqXHR);
             Swal.fire('Error', jqXHR.responseJSON?.error || 'Could not fetch link details.', 'error');
        });
    });

    // --- Edit Form Submit Handler (AJAX) ---
    $('#editLinkForm').on('submit', function (e) {
        e.preventDefault();
        var form = this;

        // Basic client-side validation (optional, enhances UX)
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const id = $('#editLinkId').val();
        const url = $(this).attr('action'); // Get URL from form action
        const formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: url,
            method: 'POST', // Method spoofing (_method=PUT) is handled by serialize()
            data: formData,
            success: function(response) {
                editModal.hide();
                Swal.fire({ toast: true, icon: 'success', title: response.message || 'Link updated!', position: 'top-end', showConfirmButton: false, timer: 3000 });
                fetchData(); // Reload table data
            },
            error: function(jqXHR) {
                 console.error("Update Error:", jqXHR);
                 // Display validation errors or general error from server
                 if (jqXHR.status === 422) {
                     // Handle validation errors (e.g., display under fields)
                     let errors = jqXHR.responseJSON.errors;
                     if(errors.title) $('#editTitle').addClass('is-invalid').siblings('.invalid-feedback').text(errors.title[0]); else $('#editTitle').removeClass('is-invalid');
                     if(errors.link) $('#editLink').addClass('is-invalid').siblings('.invalid-feedback').text(errors.link[0]); else $('#editLink').removeClass('is-invalid');
                     // Optionally show a general validation message
                     Swal.fire('Validation Error', 'Please check the highlighted fields.', 'warning');
                 } else {
                     Swal.fire('Error', jqXHR.responseJSON?.error || 'Could not update link.', 'error');
                 }
            }
        });
    });


    // --- REMOVED: Single Delete Button Handler (AJAX) ---
    // $(document).on('click', '.btn-delete', function () { ... });

    // --- ADDED: Delete Form Submit Handler ---
    $(document).on('submit', '.form-delete-il', function (e) {
        e.preventDefault(); // Prevent the form from submitting immediately
        
        const form = this; // Get the form element
        const button = $(form).find('button[type="submit"]');

        Swal.fire({
            title: 'Delete this link?', 
            text: "You won't be able to revert this!", 
            icon: 'warning',
            showCancelButton: true, 
            confirmButtonColor: '#d33', 
            cancelButtonColor: '#3085d6', 
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state on button
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                
                // Submit the form
                form.submit();
            }
        });
    });

    // Add modal validation trigger (optional)
    document.getElementById('addLinkForm').addEventListener('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        this.classList.add('was-validated');
    }, false);


    // --- Initial Data Load ---
    fetchData();

</script>