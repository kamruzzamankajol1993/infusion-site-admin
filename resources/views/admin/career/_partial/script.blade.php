{{-- resources/views/admin/career/_partial/script.blade.php --}}
<script>
    // Use unique variables
    var currentPageC = 1, searchTermC = '', sortColumnC = 'application_deadline', sortDirectionC = 'desc';

    // --- Define Routes ---
    var routesC = { // Unique prefix
        fetch: "{{ route('ajax.career.data') }}",
        edit: id => `{{ route('career.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('career.show', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('career.destroy', ':id') }}`.replace(':id', id), // Still needed for form action
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchCData() { // Unique function name
        $('#tableBodyC').html('<tr><td colspan="6" class="text-center py-4"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>'); // Colspan 6

        $.get(routesC.fetch, {
            page: currentPageC, search: searchTermC, sort: sortColumnC, direction: sortDirectionC
        }, function (res) {
            let rows = ''; $('#tableBodyC').empty(); // Use unique ID

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="6" class="text-center text-muted py-4">No career postings found.</td></tr>`; // Colspan 6
                 $('#tableBodyC').html(rows); $('#tableRowCountC').text(`Showing 0 to 0 of 0 entries`); $('#paginationC').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let deadline = item.application_deadline ? new Date(item.application_deadline).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric'}) : '<span class="text-muted">N/A</span>';
                let isExpired = item.application_deadline ? new Date(item.application_deadline) < new Date() : false; // Check if deadline passed
                let deadlineClass = isExpired ? 'text-danger' : ''; // Style expired deadlines

                let editUrl = routesC.edit(item.id);
                let showUrl = routesC.show(item.id);
                let deleteUrl = routesC.delete(item.id); // Get delete URL for the form

                let canUpdate = {{ Auth::user()->can('careerUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('careerDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('careerView') ? 'true' : 'false' }};

                // --- UPDATED ROW TEMPLATE ---
                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${item.title || 'N/A'}</td>
                    <td>${item.position || 'N/A'}</td>
                    <td>${item.job_location || 'N/A'}</td>
                    <td class="${deadlineClass}">${deadline}</td>
                    <td>`;
                 if(canView) rows += `<a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm" title="View Details"><i class="fa fa-eye"></i></a> `;
                 if(canUpdate) rows += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm" title="Edit"><i class="fa fa-edit"></i></a> `;
                 if(canDelete) {
                    // Replace button with a form for "normal" delete
                    rows += `<form action="${deleteUrl}" method="POST" class="d-inline delete-form-c" data-title="${item.title || 'this post'}">
                                <input type="hidden" name="_token" value="${routesC.token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger btn-custom-sm" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                             </form>`;
                 }
                 rows += `</td>
                </tr>`;
                // --- END UPDATED ROW TEMPLATE ---
            });
            $('#tableBodyC').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountC').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderCPagination(res); // Unique function
        })
         .fail(function(jqXHR, textStatus, errorThrown) {
             console.error("AJAX Error loading careers:", textStatus, errorThrown);
             $('#tableBodyC').html('<tr><td colspan="6" class="text-center text-danger py-4">Could not load data. Please try again.</td></tr>');
             $('#tableRowCountC').text(`Showing 0 to 0 of 0 entries`); $('#paginationC').empty();
         });
    }

    // --- Render Pagination ---
    function renderCPagination(res) { // Unique function name
        let paginationHtml = '';
        if (res.last_page > 1) { /* ... Same pagination logic ... */
             paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-c" href="#" data-page="1">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-c" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const maxVisiblePages=5; let startPage = Math.max(1, res.current_page - Math.floor(maxVisiblePages / 2)); let endPage = Math.min(res.last_page, startPage + maxVisiblePages - 1); if(endPage === res.last_page){ startPage = Math.max(1, endPage - maxVisiblePages + 1);}
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-c" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-c" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-c" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationC').html(paginationHtml); // Use unique ID
    }

    // --- Debounced Search Input Handler ---
     let searchTimeoutC;
    $('#searchInputC').on('keyup', function () { // Use unique ID
        clearTimeout(searchTimeoutC); searchTermC = $(this).val(); // Use unique var
        searchTimeoutC = setTimeout(function() { currentPageC = 1; fetchCData(); }, 300); // Use unique vars/funcs
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableC', function () { // Use unique class
        let col = $(this).data('column'); sortDirectionC = (sortColumnC === col && sortDirectionC === 'asc') ? 'desc' : 'asc'; sortColumnC = col;
        $('.sortableC').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirectionC === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchCData();
    });

    // --- Pagination Click Handler ---
    $('#paginationC').on('click', '.page-link-c', function (e) { // Use unique ID and class
        e.preventDefault(); const page = parseInt($(this).data('page'));
        const isDisabled = $(this).parent().hasClass('disabled'); const isActive = $(this).parent().hasClass('active');
        if (!isNaN(page) && !isDisabled && !isActive) { currentPageC = page; fetchCData(); }
    });

    // --- NEW: SweetAlert Form Submission Handler ---
    // Listen for submit event on any form with class 'delete-form-c' inside the table body
    $('#tableBodyC').on('submit', '.delete-form-c', function (e) {
        e.preventDefault(); // Stop the form from submitting immediately
        
        const form = this; // Get the form element
        const title = $(form).data('title'); // Get the title from data attribute
        
        Swal.fire({
            title: `Delete this career posting?`,
            text: `You are about to delete '${title}'. This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the original form
                form.submit();
            }
        });
    });

    // --- OLD AJAX DELETE HANDLER (REMOVED) ---
    // --- 'checkAndReloadCData' FUNCTION (REMOVED) ---

    // --- Initial Data Load ---
    fetchCData(); // Use unique function name

</script>