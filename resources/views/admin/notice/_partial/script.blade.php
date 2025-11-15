{{-- resources/views/admin/notice/_partial/script.blade.php --}}
<script>
    // Use unique variables for this module
    var currentPageN = 1, searchTermN = '', sortColumnN = 'date', sortDirectionN = 'desc';

    // --- Define Routes ---
    var routesN = { // Unique prefix
        fetch: "{{ route('ajax.notice.data') }}",
        edit: id => `{{ route('notice.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('notice.show', ':id') }}`.replace(':id', id), // Link to show page or PDF
        delete: id => `{{ route('notice.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchNData() { // Unique function name
        // Show loading indicator (optional)
        // $('#tableBodyN').html('<tr><td colspan="5" class="text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');

        $.get(routesN.fetch, {
            page: currentPageN,
            search: searchTermN,
            sort: sortColumnN,
            direction: sortDirectionN,
            perPage: 10 // Match controller pagination
        }, function (res) {
            let rows = '';
            $('#tableBodyN').empty(); // Clear previous rows or loading indicator

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="5" class="text-center text-muted py-4">No notices found matching your criteria.</td></tr>`; // Improved message
                 $('#tableBodyN').html(rows);
                 $('#tableRowCountN').text(`Showing 0 to 0 of 0 entries`); // Update row count
                 $('#paginationN').empty(); // Clear pagination
                 return; // Exit if no data
            }

            // Loop through the data and build table rows
            res.data.forEach((item, i) => {
                // Format date nicely (e.g., 21 Oct, 2025) or use your preferred format
                let noticeDate = item.date ? new Date(item.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric'}) : '<span class="text-muted">N/A</span>';
                // Use the pre-formatted pdf_url from the controller
                let pdfUrl = item.pdf_url || '#';
                let pdfLinkDisabled = !item.pdf_url; // Disable if URL is null

                // Prepare action button URLs/IDs
                let editUrl = routesN.edit(item.id);
                // let showUrl = routesN.show(item.id); // Use show route if you have a show page
                
                // --- UPDATED: Get delete URL for the form ---
                let deleteUrl = routesN.delete(item.id); 

                // Check permissions (passed from controller or assumed based on user role)
                let canUpdate = {{ Auth::user()->can('noticeUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('noticeDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('noticeView') ? 'true' : 'false' }}; // Assuming view includes PDF view

                // Construct the table row HTML
                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${item.title || '<span class="text-muted">N/A</span>'}</td>
                    <td>${item.category?.name || '<span class="text-muted small">Uncategorized</span>'}</td>
                    <td>${noticeDate}</td>
                    <td>`; // Start Actions column

                 // View PDF Button
                 if(canView) {
                     rows += `<a href="${pdfUrl}" target="_blank"
                                class="btn btn-sm btn-primary btn-custom-sm ${pdfLinkDisabled ? 'disabled' : ''}"
                                title="View PDF" ${pdfLinkDisabled ? 'aria-disabled="true" style="pointer-events: none; opacity: 0.6;"' : ''}>
                                <i class="fa fa-file-pdf"></i>
                              </a> `;
                 }
                 // Edit Button
                 if(canUpdate) {
                     rows += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm" title="Edit">
                                <i class="fa fa-edit"></i>
                              </a> `;
                 }
                 
                 // --- UPDATED: Delete button is now a form ---
                 if(canDelete) {
                    rows += `<form action="${deleteUrl}" method="POST" class="d-inline-block form-delete-n" style="margin-bottom: 0;">
                                <input type="hidden" name="_token" value="${routesN.token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger btn-custom-sm" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>`; // Unique class form-delete-n
                 }
                 
                 rows += `</td>
                </tr>`; // End row
            });
            $('#tableBodyN').html(rows); // Add all rows to the table body

            // --- Update Row Count Text ---
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountN').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`); // Use unique ID

            // --- Render Pagination ---
            renderNPagination(res); // Use unique function name
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Handle AJAX errors (e.g., show an error message)
            console.error("AJAX Error:", textStatus, errorThrown);
            $('#tableBodyN').html('<tr><td colspan="5" class="text-center text-danger py-4">Could not load data. Please try again.</td></tr>');
            $('#tableRowCountN').text(`Showing 0 to 0 of 0 entries`);
            $('#paginationN').empty();
        });
    }

    // --- Render Pagination Links ---
    function renderNPagination(res) { // Unique function name
        let paginationHtml = '';
        if (res.last_page > 1) {
            // First & Previous links
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-n" href="#" data-page="1" aria-label="First">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-n" href="#" data-page="${res.current_page - 1}" aria-label="Previous">&laquo;</a></li>`;

            // Page number links (show max 5 around current)
            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);

            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-n" href="#" data-page="${i}">${i}</a></li>`;
            }

            // Next & Last links
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-n" href="#" data-page="${res.current_page + 1}" aria-label="Next">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-n" href="#" data-page="${res.last_page}" aria-label="Last">&raquo;&raquo;</a></li>`;
        }
         $('#paginationN').html(paginationHtml); // Use unique ID for pagination container
    }

    // --- Debounced Search Input Handler ---
     let searchTimeoutN;
    $('#searchInputN').on('keyup', function () { // Use unique ID
        clearTimeout(searchTimeoutN); // Clear previous timeout
        searchTermN = $(this).val(); // Use unique var
        searchTimeoutN = setTimeout(function() { // Set new timeout
            currentPageN = 1; // Reset to first page on search
            fetchNData(); // Use unique function
        }, 300); // Delay in milliseconds (e.g., 300ms)
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableN', function () { // Use unique class
        let col = $(this).data('column');
        // Toggle direction if same column is clicked again
        sortDirectionN = (sortColumnN === col && sortDirectionN === 'asc') ? 'desc' : 'asc'; // Use unique vars
        sortColumnN = col; // Use unique var
        // Update visual indicators
        $('.sortableN').removeClass('sorting_asc sorting_desc');
        $(this).addClass(sortDirectionN === 'asc' ? 'sorting_asc' : 'sorting_desc');
        fetchNData(); // Use unique function
    });

    // --- Pagination Click Handler ---
    // Use event delegation on the pagination container
    $('#paginationN').on('click', '.page-link-n', function (e) { // Use unique ID and class
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        const isDisabled = $(this).parent().hasClass('disabled');
        const isActive = $(this).parent().hasClass('active');

        // Fetch data only if the link is valid and not the current page
        if (!isNaN(page) && !isDisabled && !isActive) {
            currentPageN = page; // Use unique var
            fetchNData(); // Use unique function
        }
    });

    // --- UPDATED: Delete Form Handler (replaces btn-delete-n click handler) ---
    $('#tableBodyN').on('submit', '.form-delete-n', function (e) { // Use unique ID and class, on 'submit'
        e.preventDefault(); // Prevent the form from submitting immediately
        
        const form = this; // Get the form element
        const button = $(form).find('button[type="submit"]');

        Swal.fire({
            title: 'Delete this notice?',
            text: "The associated PDF file will also be deleted. This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Red for delete
            cancelButtonColor: '#3085d6', // Blue for cancel
            confirmButtonText: 'Yes, delete it!',
            focusCancel: true // Focus cancel button by default
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state on button
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                
                // Submit the form
                form.submit();
            }
        });
    });

    // --- REMOVED checkAndReloadNData() function ---

    // --- Initial Data Load on Page Ready ---
    fetchNData(); // Use unique function name

</script>