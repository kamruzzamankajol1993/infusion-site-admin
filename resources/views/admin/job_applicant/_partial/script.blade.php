{{-- resources/views/admin/job_applicant/_partial/script.blade.php --}}
<script>
    // Use unique variables for this module
    var currentPageJA = 1, searchTermJA = '', sortColumnJA = 'created_at', sortDirectionJA = 'desc';
    var jobFilterValue = 'all'; // Variable for job filter (if using the filter dropdown)

    // --- Define Routes ---
    var routesJA = { // Unique prefix
        fetch: "{{ route('ajax.jobApplicant.data') }}",
        show: id => `{{ route('jobApplicant.show', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('jobApplicant.destroy', ':id') }}`.replace(':id', id),
        // --- NEW EXPORT ROUTES ---
        exportExcel: "{{ route('jobApplicant.export.excel') }}",
        exportPdf: "{{ route('jobApplicant.export.pdf') }}",
        // --- END NEW EXPORT ROUTES ---
        token: "{{ csrf_token() }}" // CSRF token for AJAX delete
    };

    // --- Fetch and Render Table Data ---
    function fetchJAData() { // Unique function name
        // Show loading state
        $('#tableBodyJA').html('<tr><td colspan="8" class="text-center py-4"><span class="spinner-border spinner-border-sm"></span> Loading applicants...</td></tr>'); // Updated colspan to 8

        $.get(routesJA.fetch, {
            page: currentPageJA,
            search: searchTermJA,
            sort: sortColumnJA,
            direction: sortDirectionJA,
            job_filter: jobFilterValue // Pass filter value to backend
        }, function (res) {
            let rows = ''; $('#tableBodyJA').empty(); // Use unique ID, clear previous rows

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="8" class="text-center text-muted py-4">No job applicants found matching your criteria.</td></tr>`; // Updated colspan
                 $('#tableBodyJA').html(rows);
                 $('#tableRowCountJA').text(`Showing 0 to 0 of 0 entries`);
                 $('#paginationJA').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                // Format dates safely
                let appliedDate = item.created_at ? new Date(item.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric'}) : 'N/A';
                let dob = item.date_of_birth ? new Date(item.date_of_birth).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric'}) : 'N/A';

                let showUrl = routesJA.show(item.id); // Get URL via function
                let deleteId = item.id;

                // Check permissions (passed from Blade)
                let canDelete = {{ Auth::user()->can('jobApplicantDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('jobApplicantView') ? 'true' : 'false' }};

                // Build action buttons conditionally
                let actionBtns = '';
                 if(canView) actionBtns += `<a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm me-1" title="View Details"><i class="fa fa-eye"></i></a>`;
                 if(canDelete) actionBtns += `<button class="btn btn-sm btn-danger btn-delete-ja btn-custom-sm" data-id="${deleteId}" title="Delete"><i class="fa fa-trash"></i></button>`; // Unique class

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${item.full_name || 'N/A'}</td>
                    <td>${item.career?.title || '<span class="text-muted small">N/A</span>'}</td> {{-- Access related job title --}}
                    <td>${item.email || 'N/A'}</td>
                    <td>${item.phone_number || 'N/A'}</td>
                    <td>${dob}</td>                  {{-- NEW: Date of Birth --}}
                    <td>${appliedDate}</td>
                    <td>${actionBtns || '<span class="text-muted small">No actions</span>'}</td>
                </tr>`;
            });
            $('#tableBodyJA').html(rows); // Update table body with generated rows

            // Update Row Count & Pagination Display
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountJA').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderJAPagination(res); // Call unique pagination function
        })
         .fail(function(jqXHR, textStatus, errorThrown) { // Handle AJAX errors
             console.error("AJAX Error loading job applicants:", textStatus, errorThrown);
             $('#tableBodyJA').html('<tr><td colspan="8" class="text-center text-danger py-4">Could not load data. Please check connection and try again.</td></tr>'); // Updated colspan
             $('#tableRowCountJA').text(`Showing 0 to 0 of 0 entries`); $('#paginationJA').empty();
         });
    }

    // --- Render Pagination ---
    function renderJAPagination(res) { // Unique function name
        let paginationHtml = '';
        if (res.last_page > 1) {
            // First and Previous links
             paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-ja" href="#" data-page="1">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-ja" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;

            // Page number links logic (show limited pages)
            const maxVisiblePages = 5; // How many page numbers to show around current
            let startPage = Math.max(1, res.current_page - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(res.last_page, startPage + maxVisiblePages - 1);
            // Adjust startPage if endPage reaches the last page
            if(endPage === res.last_page){
                 startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            for (let i = startPage; i <= endPage; i++) {
                 paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-ja" href="#" data-page="${i}">${i}</a></li>`;
            }

            // Next and Last links
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-ja" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-ja" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationJA').html(paginationHtml); // Use unique ID
    }

    // --- Debounced Search Input Handler ---
     let searchTimeoutJA;
    $('#searchInputJA').on('keyup', function () { // Use unique ID
        clearTimeout(searchTimeoutJA);
        searchTermJA = $(this).val(); // Use unique var
        searchTimeoutJA = setTimeout(function() {
            currentPageJA = 1; // Reset to page 1 on search
            fetchJAData(); // Use unique fetch function
        }, 300); // Debounce time in ms (e.g., 300ms)
    });

    // --- Job Filter Dropdown Handler ---
    $('#jobFilter').on('change', function() {
        jobFilterValue = $(this).val();
        currentPageJA = 1; // Reset page on filter change
        fetchJAData(); // Refetch data with the filter
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableJA', function () { // Use unique class
        let col = $(this).data('column');
        // Toggle direction if same column, otherwise default to 'asc'
        sortDirectionJA = (sortColumnJA === col && sortDirectionJA === 'asc') ? 'desc' : 'asc';
        sortColumnJA = col; // Update the current sort column
        // Update visual indicators
        $('.sortableJA').removeClass('sorting_asc sorting_desc');
        $(this).addClass(sortDirectionJA === 'asc' ? 'sorting_asc' : 'sorting_desc');
        fetchJAData(); // Refetch sorted data
    });

    // --- Pagination Click Handler ---
    // Use event delegation on the pagination container for dynamically added links
    $('#paginationJA').on('click', '.page-link-ja', function (e) { // Use unique ID and class
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        const isDisabled = $(this).parent().hasClass('disabled');
        const isActive = $(this).parent().hasClass('active');
        // Only fetch if the page number is valid and the link isn't disabled or already active
        if (!isNaN(page) && !isDisabled && !isActive) {
            currentPageJA = page; // Update current page
            fetchJAData(); // Fetch data for the new page
        }
    });

    // --- Single Delete Button Handler (AJAX) ---
    // Use event delegation on the table body for dynamically added rows/buttons
    $('#tableBodyJA').on('click', '.btn-delete-ja', function () { // Use unique ID and class
        const id = $(this).data('id');
        const button = $(this); // Reference to the clicked button
        Swal.fire({
            title: 'Delete this applicant?',
            text: "The associated CV file will also be deleted! This cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Red color for delete confirmation
            cancelButtonColor: '#3085d6', // Blue color for cancel
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable button and show spinner on confirmation
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                $.ajax({
                    url: routesJA.delete(id), // Call the delete route function
                    method: 'DELETE',
                    data: { _token: routesJA.token }, // Include CSRF token
                    success: function(response) {
                        // Show success toast
                        Swal.fire({
                            toast: true, icon: 'success', title: response.message || 'Applicant deleted!',
                            position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                        });
                        checkAndReloadJAData(); // Use unique reload function
                    },
                    error: function(xhr) {
                        // Show error alert
                        Swal.fire('Error!', xhr.responseJSON?.error || 'Could not delete applicant.', 'error');
                        // Re-enable button and restore icon on error
                        button.prop('disabled', false).html('<i class="fa fa-trash"></i>');
                    }
                });
            }
        });
    });

    // --- Helper Function to Reload Data After Delete ---
    // Checks if the current page becomes empty after deletion and goes back one page if needed.
    function checkAndReloadJAData() { // Use unique function name
        // Check current page data count before fetching the potentially new page
        $.get(routesJA.fetch, {
            page: currentPageJA, // Check the current page first
            search: searchTermJA,
            sort: sortColumnJA,
            direction: sortDirectionJA,
            job_filter: jobFilterValue,
            check_empty: true // Optional: Add a flag if backend needs it, otherwise check 'res.data.length'
         }, function (res) {
             // If the current page is now empty AND it wasn't page 1, decrement the page number
             if ((!res.data || res.data.length === 0) && currentPageJA > 1) {
                currentPageJA--;
             }
            fetchJAData(); // Fetch data for the (potentially adjusted) current page
        }).fail(function() {
            // If the check fails, just try fetching the current page again
            fetchJAData();
        });
    }
    
    // --- NEW: Download Handlers ---
    /**
     * Constructs the download URL, appending the current job filter value if active.
     * @param {string} route - The base route (e.g., routesJA.exportExcel)
     */
    function generateDownloadUrl(route) {
        // Construct URL with the current filter value
        const url = new URL(route);
        // Only append job_filter if it's set and not 'all'
        if (jobFilterValue && jobFilterValue !== 'all') {
            url.searchParams.set('job_filter', jobFilterValue);
        }
        return url.toString();
    }

    $('#downloadExcelBtnJA').on('click', function() {
        const url = generateDownloadUrl(routesJA.exportExcel);
        window.location.href = url; // Initiate file download
    });

    $('#downloadPdfBtnJA').on('click', function() {
        const url = generateDownloadUrl(routesJA.exportPdf);
        window.location.href = url; // Initiate file download
    });
    // --- END NEW: Download Handlers ---

    // --- Initial Data Load ---
    fetchJAData(); // Call unique function name on page load

</script>