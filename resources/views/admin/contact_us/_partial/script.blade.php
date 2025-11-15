<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'created_at', sortDirection = 'desc'; // Default sort to Received Date
    var selectedIds = []; // Array to keep track of selected checkbox IDs
    var viewModal; // Variable to hold the Bootstrap Modal instance

    // --- Define Routes (Make sure these match your web.php) ---
    // We define functions separately to avoid Blade parser errors with "key: arrow =>" syntax
    var routes = {
        fetch: "{{ route('ajax.contactUs.data') }}",
        show_url: "{{ route('contactUs.show', ':id') }}",
        delete_url: "{{ route('contactUs.destroy', ':id') }}",
        deleteMultiple: "{{ route('contactUs.destroyMultiple') }}",
        token: "{{ csrf_token() }}",

        // These functions use the URL templates defined above
        show: function(id) {
            return this.show_url.replace(':id', id);
        },
        delete: function(id) {
            return this.delete_url.replace(':id', id);
        }
    };

    // --- Initialize View Modal when DOM is ready ---
    document.addEventListener('DOMContentLoaded', function () {
        var viewModalElement = document.getElementById('viewMessageModal');
        if (viewModalElement) {
             viewModal = new bootstrap.Modal(viewModalElement);
        } else {
            console.error('View Message Modal element not found.');
        }
    });

    // --- Fetch and Render Table Data ---
    function fetchData() {
        // Show loading indicator (optional)
        $('#tableBody').html('<tr><td colspan="7" class="text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');

        $.get(routes.fetch, {
            page: currentPage,
            search: searchTerm,
            sort: sortColumn,
            direction: sortDirection
        }, function (res) {
            let rows = '';
            $('#tableBody').empty(); // Clear previous rows or loading indicator

            // Check if data array exists and has items
            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="7" class="text-center text-muted">No messages found.</td></tr>`; // Updated colspan to 7
                 $('#tableBody').html(rows);
                 $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); // Update row count
                 $('#pagination').empty(); // Clear pagination
                 resetCheckboxes(); // Ensure checkboxes are reset
                 return; // Exit if no data
            }

            // Loop through data and build table rows
            res.data.forEach((item, i) => {
                // Safely format date, handle potential nulls
                let receivedDate = 'N/A';
                if (item.created_at) {
                    try {
                        receivedDate = new Date(item.created_at).toLocaleString(); // Format date nicely
                    } catch (e) {
                        console.error("Error parsing date:", item.created_at, e);
                        receivedDate = item.created_at; // Fallback to raw string if parsing fails
                    }
                }

                // Check if the current item ID is in the selectedIds array
                let isChecked = selectedIds.includes(item.id.toString()); // Ensure comparison is consistent (string)

                // --- Build Delete Form ---
                let deleteButtonHtml = '';
                
                @if(Auth::user()->can('contactUsDelete'))
                deleteButtonHtml = `
                    <form action="${routes.delete(item.id)}" method="POST" class="d-inline" id="delete-form-${item.id}">
                        <input type="hidden" name="_token" value="${routes.token}">
                        <input type="hidden" name="_method" value="DELETE">
                        
                        <button type="button" class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                `;
                @endif
                // --- END Form Build ---

                rows += `<tr>
                    <td>
                        <input class="form-check-input rowCheckbox" type="checkbox" value="${item.id}" ${isChecked ? 'checked' : ''}>
                    </td>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${item.fullname || 'N/A'}</td>
                    <td>${item.email || 'N/A'}</td>
                    <td>${item.mobilenumber || 'N/A'}</td>
                    <td>${receivedDate}</td>
                    <td>
                        {{-- View Button --}}
                        <button class="btn btn-sm btn-info btn-view btn-custom-sm me-1" data-id="${item.id}" title="View Message">
                            <i class="fa fa-eye"></i>
                        </button>

                        {{-- Delete Form/Button (from variable above) --}}
                        ${deleteButtonHtml}
                    </td>
                </tr>`;
            });
            $('#tableBody').html(rows); // Append rows to the table body

            // Update Row Count Text
            const startEntry = res.from || ((res.current_page - 1) * (res.per_page || 10) + 1);
            const endEntry = res.to || (startEntry + res.data.length - 1);
            $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);

            // --- Render Pagination ---
            renderPagination(res);
            updateDeleteSelectedButton(); // Update delete selected button state
        }).fail(function(jqXHR, textStatus, errorThrown) { // Handle AJAX errors
            console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
            $('#tableBody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load data. Please check console or try again.</td></tr>');
            $('#pagination').html(''); // Clear pagination on error
            $('.card-footer .text-muted').text('Error loading data');
            resetCheckboxes();
        });
    }

    // --- Render Pagination Links ---
    function renderPagination(res) {
        let paginationHtml = '';
        if (res.last_page > 1) {
            // First & Previous Links
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="1">&laquo;&laquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;

            // Page Number Links
            const maxPagesToShow = 5; 
            let startPage = Math.max(1, res.current_page - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(res.last_page, startPage + maxPagesToShow - 1);
            if (endPage === res.last_page) {
                startPage = Math.max(1, endPage - maxPagesToShow + 1);
            }
            if (startPage > 1) { 
                paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }
            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            if (endPage < res.last_page) { 
                paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            // Next & Last Links
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#pagination').html(paginationHtml);
    }

    // --- Search Input Handler ---
    let searchTimeout;
    $('#searchInput').on('keyup', function () {
        searchTerm = $(this).val();
        currentPage = 1; // Reset to first page on search
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(fetchData, 300); 
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column');
        if (col) { 
            sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
            sortColumn = col;
            $('.sortable').removeClass('sorting_asc sorting_desc');
            $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc');
            fetchData();
        }
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
         if (!isNaN(page) && page !== currentPage) {
            currentPage = page;
            fetchData();
        }
    });

    // --- View Button Click Handler ---
    $(document).on('click', '.btn-view', function () {
        const id = $(this).data('id');
        if (!id) return; 

        // Show loading state in modal
        $('#modalFromName').text('Loading...');
        $('#modalFromEmail').text('...');
        $('#modalFromPhone').text('...');
        $('#modalReceivedDate').text('...');
        $('#modalMessageContent').html('<div class="text-center"><div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div></div>');

        if(viewModal) viewModal.show(); // Show modal

        // Fetch message details via AJAX (using the new routes.show function)
        $.get(routes.show(id), function(message) {
             $('#modalFromName').text(message.fullname || 'N/A');
             $('#modalFromEmail').text(message.email || 'N/A');
             $('#modalFromPhone').text(message.mobilenumber || 'N/A');
             let receivedDate = 'N/A';
             if (message.created_at) {
                 try {
                     receivedDate = new Date(message.created_at).toLocaleString();
                 } catch (e) { receivedDate = message.created_at; }
             }
             $('#modalReceivedDate').text(receivedDate);
             $('#modalMessageContent').text(message.message || 'No message content.');
        }).fail(function(xhr) {
             console.error("Failed to fetch message details:", xhr);
             $('#modalFromName').text('Error');
             $('#modalFromEmail').text('-');
             $('#modalFromPhone').text('-');
             $('#modalReceivedDate').text('-');
             $('#modalMessageContent').html('<div class="alert alert-danger alert-sm">Failed to load message details. Please try again.</div>');
        });
    });
    // ----------------------------------------

    // --- Single Delete Button Handler (Submits Form) ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const formId = `#delete-form-${id}`; // The ID of the form to submit

        Swal.fire({
            title: 'Delete this message?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Find the specific form and submit it
                $(formId).submit();
            }
        });
    });

    // --- Checkbox Handling ---
    $(document).on('change', '.rowCheckbox', function() {
        const id = $(this).val();
        if ($(this).is(':checked')) { if (!selectedIds.includes(id)) { selectedIds.push(id); } }
        else { selectedIds = selectedIds.filter(selectedId => selectedId !== id); }
        updateDeleteSelectedButton();
        const totalVisibleCheckboxes = $('.rowCheckbox').length;
        $('#checkAll').prop('checked', totalVisibleCheckboxes > 0 && $('.rowCheckbox:checked').length === totalVisibleCheckboxes);
    });

    $('#checkAll').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.rowCheckbox').prop('checked', isChecked).trigger('change'); 
    });

    function updateDeleteSelectedButton() {
        $('#deleteSelectedBtn').toggle(selectedIds.length > 0);
    }

    function resetCheckboxes() {
        selectedIds = [];
        $('#checkAll').prop('checked', false);
        $('.rowCheckbox').prop('checked', false);
        updateDeleteSelectedButton();
    }
    // -----------------------------------------------

    // --- Delete Selected Button Handler (Still uses AJAX) ---
    $('#deleteSelectedBtn').on('click', function() {
         if (selectedIds.length === 0) { Swal.fire('No Selection', 'Please select messages to delete.', 'info'); return; }

         Swal.fire({
            title: `Delete ${selectedIds.length} selected messages?`, text: "You won't be able to revert this!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.showLoading(); 
                $.ajax({
                    url: routes.deleteMultiple, method: 'DELETE', 
                    data: { _token: routes.token, ids: selectedIds },
                    success: function(response) {
                        Swal.fire({ toast: true, icon: 'success', title: response.message || 'Selected deleted!', position: 'top-end', showConfirmButton: false, timer: 2000 });
                        resetCheckboxes();
                        checkAndReloadData(); // Reloads data via AJAX
                    },
                    error: function(xhr) { Swal.fire('Error!', xhr.responseJSON?.error || 'Could not delete selected messages.', 'error'); }
                });
            }
        });
    });

    // --- Helper Function to Reload Data After AJAX Delete ---
    function checkAndReloadData() {
        const currentVisibleRows = $('#tableBody tr:has(td)').length; 
        if (currentVisibleRows === selectedIds.length && currentPage > 1) {
             currentPage--; // Go to previous page
        }
        resetCheckboxes(); 
        fetchData();
    }
    
    // --- Initial Data Load ---
    fetchData();

</script>