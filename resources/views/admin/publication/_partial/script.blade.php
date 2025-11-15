{{-- resources/views/admin/publication/_partial/script.blade.php --}}
<script>
    // Use unique variables for this module to avoid conflicts
    var currentPageP = 1, searchTermP = '', sortColumnP = 'date', sortDirectionP = 'desc';

    // --- Define Routes ---
    var routesP = { // Unique prefix 'P' for Publication
        fetch: "{{ route('ajax.publication.data') }}",
        edit: id => `{{ route('publication.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('publication.show', ':id') }}`.replace(':id', id), // Link to show page or direct PDF link
        delete: id => `{{ route('publication.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}" // CSRF token for forms
    };

    // --- Fetch and Render Table Data ---
    function fetchPData() { // Unique function name
        // Optional: Show a loading state in the table body
        $('#tableBodyP').html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>'); // Colspan 5

        $.get(routesP.fetch, {
            page: currentPageP,
            search: searchTermP,
            sort: sortColumnP,
            direction: sortDirectionP,
            perPage: 10 // Match controller pagination setting
        }, function (res) {
            let rows = '';
            $('#tableBodyP').empty(); // Clear previous rows or loading indicator

            // Handle case where no data is returned
            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="5" class="text-center text-muted py-4">No publications found matching your criteria.</td></tr>`; // Colspan 5
                 $('#tableBodyP').html(rows);
                 $('#tableRowCountP').text(`Showing 0 to 0 of 0 entries`); // Update row count
                 $('#paginationP').empty(); // Clear pagination
                 return; // Exit if no data
            }

            // Loop through the data and build table rows
            res.data.forEach((item, i) => {
                // Format date nicely (e.g., 21 Oct, 2025)
                let pubDate = item.date ? new Date(item.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric'}) : '<span class="text-muted">N/A</span>';

                // Use the image_url prepared by the controller, provide a placeholder
                let imageUrl = item.image || `{{ asset('public/admin/assets/img/placeholder.png') }}`; // Adjust placeholder path if needed
                let imageHtml = `<img src="{{asset('/')}}${imageUrl}" alt="${item.title || 'Publication Image'}" style="max-height: 50px; max-width: 75px; object-fit: contain; border: 1px solid #eee;">`;

                // Use the pdf_url prepared by the controller
                let pdfUrl = item.pdf_url || '#';
                let pdfLinkDisabled = !item.pdf_url; // Disable button if URL is null

                // Prepare action button URLs/IDs
                let editUrl = routesP.edit(item.id);
                let deleteUrl = routesP.delete(item.id); // --- Get delete URL for the form

                // Check permissions dynamically (ensure Auth facade is available or pass permissions via view)
                let canUpdate = {{ Auth::user()->can('publicationUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('publicationDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('publicationView') ? 'true' : 'false' }};

                // Construct the table row HTML
                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${imageHtml}</td>
                    <td>${item.title || '<span class="text-muted">N/A</span>'}</td>
                    <td>${pubDate}</td>
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
                    rows += `<form action="${deleteUrl}" method="POST" class="d-inline-block form-delete-p" style="margin-bottom: 0;">
                                <input type="hidden" name="_token" value="${routesP.token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger btn-custom-sm" title="Delete"><i class="fa fa-trash"></i></button>
                            </form>`; // Unique class form-delete-p
                 }
                 // --- End Update ---
                 
                 rows += `</td>
                </tr>`; // End row
            });
            $('#tableBodyP').html(rows); // Add all rows to the table body

            // --- Update Row Count Text ---
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountP').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`); // Use unique ID

            // --- Render Pagination ---
            renderPPagination(res); // Use unique function name
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            // Handle AJAX errors (e.g., show an error message in the table)
            console.error("AJAX Error loading publications:", textStatus, errorThrown);
            $('#tableBodyP').html('<tr><td colspan="5" class="text-center text-danger py-4">Could not load data. Server error. Please try again.</td></tr>'); // Colspan 5
            $('#tableRowCountP').text(`Showing 0 to 0 of 0 entries`);
            $('#paginationP').empty();
        });
    }

    // --- Render Pagination Links ---
    function renderPPagination(res) { // Unique function name
        let paginationHtml = '';
        if (res.last_page > 1) {
            // Define max number of visible page links (e.g., 5)
            const maxVisiblePages = 5;
            let startPage = Math.max(1, res.current_page - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(res.last_page, startPage + maxVisiblePages - 1);

            // Adjust startPage if endPage reaches the last page limit
             if (endPage === res.last_page) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }


            // First & Previous links
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-p" href="#" data-page="1" aria-label="First">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-p" href="#" data-page="${res.current_page - 1}" aria-label="Previous">&laquo;</a></li>`;

            // Page number links
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-p" href="#" data-page="${i}">${i}</a></li>`;
            }

            // Next & Last links
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-p" href="#" data-page="${res.current_page + 1}" aria-label="Next">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-p" href="#" data-page="${res.last_page}" aria-label="Last">&raquo;&raquo;</a></li>`;
        }
         $('#paginationP').html(paginationHtml); // Use unique ID for pagination container
    }

    // --- Debounced Search Input Handler ---
     let searchTimeoutP; // Debounce timer variable
    $('#searchInputP').on('keyup', function () { // Use unique ID
        clearTimeout(searchTimeoutP); // Clear previous timeout if user types quickly
        searchTermP = $(this).val(); // Use unique var for search term
        searchTimeoutP = setTimeout(function() { // Set a new timeout
            currentPageP = 1; // Reset to first page whenever search term changes
            fetchPData(); // Fetch data after delay using unique function
        }, 300); // Delay in milliseconds (e.g., 300ms) before sending request
    });

    // --- Column Sorting Handler ---
    // Use event delegation for dynamically added content if needed, but direct binding is fine here
    $(document).on('click', '.sortableP', function () { // Use unique class
        let col = $(this).data('column');
        // Toggle direction if same column is clicked again, otherwise default to 'asc'
        sortDirectionP = (sortColumnP === col && sortDirectionP === 'asc') ? 'desc' : 'asc'; // Use unique vars
        sortColumnP = col; // Use unique var
        // Update visual indicators (optional but helpful)
        $('.sortableP').removeClass('sorting_asc sorting_desc'); // Clear previous sort indicators
        $(this).addClass(sortDirectionP === 'asc' ? 'sorting_asc' : 'sorting_desc'); // Add new indicator
        fetchPData(); // Fetch data with new sorting parameters using unique function
    });

    // --- Pagination Click Handler ---
    // Use event delegation on the pagination container for dynamically added links
    $('#paginationP').on('click', '.page-link-p', function (e) { // Use unique ID and class
        e.preventDefault(); // Prevent default anchor behavior
        const page = parseInt($(this).data('page'));
        const isDisabled = $(this).parent().hasClass('disabled');
        const isActive = $(this).parent().hasClass('active');

        // Fetch data only if the link is valid and represents a different page
        if (!isNaN(page) && !isDisabled && !isActive) {
            currentPageP = page; // Use unique var
            fetchPData(); // Fetch data for the new page using unique function
        }
    });

    // --- UPDATED: Delete Form Handler ---
    $('#tableBodyP').on('submit', '.form-delete-p', function (e) { // Use unique ID and class, on 'submit'
        e.preventDefault(); // Prevent the form from submitting immediately

        const form = this; // Get the form element
        const button = $(form).find('button[type="submit"]');

        // Use SweetAlert for confirmation
        Swal.fire({
            title: 'Delete this publication?',
            text: "The associated PDF file and image will also be deleted. This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Red color for delete confirmation
            cancelButtonColor: '#3085d6', // Blue color for cancel
            confirmButtonText: 'Yes, delete it!',
            focusCancel: true // Focus the cancel button by default
        }).then((result) => {
            // Proceed only if the user confirms
            if (result.isConfirmed) {
                // Show loading state on the delete button
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

                // Submit the form
                form.submit();
            }
        });
    });

    // --- REMOVED checkAndReloadPData() function ---

    // --- Initial Data Load ---
    fetchPData(); // Call the unique fetch function when the page is ready

</script>