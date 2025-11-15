{{-- resources/views/admin/event/_partial/script.blade.php --}}
<script>
    // Use unique variables
    var currentPageE = 1, searchTermE = '', sortColumnE = 'start_date', sortDirectionE = 'desc';

    // --- Define Routes ---
    var routesE = { // Unique prefix
        fetch: "{{ route('ajax.event.data') }}",
        edit: id => `{{ route('event.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('event.show', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('event.destroy', ':id') }}`.replace(':id', id), // This URL is still used for the form action
        token: "{{ csrf_token() }}" // Still needed for the form
    };

    // --- Fetch and Render Table Data ---
    function fetchEData() { // Unique function name
        // Optional loading state
        $('#tableBodyE').html('<tr><td colspan="7" class="text-center py-4"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>'); // Colspan 7

        $.get(routesE.fetch, {
            page: currentPageE, search: searchTermE, sort: sortColumnE, direction: sortDirectionE
        }, function (res) {
            let rows = ''; $('#tableBodyE').empty();

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="7" class="text-center text-muted py-4">No events found.</td></tr>`; // Colspan 7
                 $('#tableBodyE').html(rows); $('#tableRowCountE').text(`Showing 0 to 0 of 0 entries`); $('#paginationE').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let statusBadge = item.status == 1
                    ? `<span class="badge bg-success-soft text-success">Published</span>`
                    : `<span class="badge bg-secondary-soft text-secondary">Draft</span>`;

                // Format Date(s)
                let eventDate = item.start_date ? new Date(item.start_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric'}) : 'N/A';
                if(item.end_date && item.end_date !== item.start_date) {
                    eventDate += ' - ' + new Date(item.end_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric'});
                }

                let imageUrl = item.image || `{{ asset('public/admin/assets/img/placeholder.png') }}`; // Use accessor
                let imageHtml = `<img src="{{ asset('${imageUrl}') }}" alt="${item.title || 'Event Image'}" style="max-height: 50px; max-width: 80px; object-fit: cover; border: 1px solid #eee;">`;

                let editUrl = routesE.edit(item.id);
                let showUrl = routesE.show(item.id);
                let deleteId = item.id;
                let deleteUrl = routesE.delete(deleteId); // --- Get delete URL for the form

                let canUpdate = {{ Auth::user()->can('eventUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('eventDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('eventView') ? 'true' : 'false' }};

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${imageHtml}</td>
                    <td>${item.title || 'N/A'}</td>
                    <td>${eventDate}</td>
                    <td>${item.time || '<span class="text-muted small">N/A</span>'}</td>
                    <td>${statusBadge}</td>
                    <td class="d-flex flex-wrap gap-1 justify-content-start">`; // Added classes for better spacing
                 if(canView) rows += `<a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm" title="View Details"><i class="fa fa-eye"></i></a>`;
                 if(canUpdate) rows += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm" title="Edit"><i class="fa fa-edit"></i></a>`;
                 
                 // --- UPDATED: Delete button is now a form ---
                 if(canDelete) {
                    rows += `<form action="${deleteUrl}" method="POST" class="d-inline-block form-delete-e" style="margin-bottom: 0;">
                                <input type="hidden" name="_token" value="${routesE.token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger btn-custom-sm" title="Delete"><i class="fa fa-trash"></i></button>
                            </form>`;
                 }
                 // --- End Update ---
                 
                 rows += `</td>
                </tr>`;
            });
            $('#tableBodyE').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountE').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderEPagination(res); // Unique function
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
             console.error("AJAX Error loading events:", textStatus, errorThrown);
             $('#tableBodyE').html('<tr><td colspan="7" class="text-center text-danger py-4">Could not load data. Please try again later.</td></tr>');
             $('#tableRowCountE').text(`Showing 0 to 0 of 0 entries`);
             $('#paginationE').empty();
         });
    }

    // --- Render Pagination ---
    function renderEPagination(res) { // Unique function name
        let paginationHtml = '';
        if (res.last_page > 1) { /* ... Same pagination logic as before ... */
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-e" href="#" data-page="1">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-e" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const maxVisiblePages = 5; let startPage = Math.max(1, res.current_page - Math.floor(maxVisiblePages / 2)); let endPage = Math.min(res.last_page, startPage + maxVisiblePages - 1); if (endPage === res.last_page) { startPage = Math.max(1, endPage - maxVisiblePages + 1); } // Adjust start page
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-e" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-e" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-e" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationE').html(paginationHtml); // Use unique ID
    }

    // --- Debounced Search Input Handler ---
     let searchTimeoutE;
    $('#searchInputE').on('keyup', function () { // Use unique ID
        clearTimeout(searchTimeoutE); searchTermE = $(this).val(); // Use unique var
        searchTimeoutE = setTimeout(function() { currentPageE = 1; fetchEData(); }, 300); // Use unique vars/funcs
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableE', function () { // Use unique class
        let col = $(this).data('column'); sortDirectionE = (sortColumnE === col && sortDirectionE === 'asc') ? 'desc' : 'asc'; sortColumnE = col;
        $('.sortableE').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirectionE === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchEData();
    });

    // --- Pagination Click Handler ---
    $('#paginationE').on('click', '.page-link-e', function (e) { // Use unique ID and class
        e.preventDefault(); const page = parseInt($(this).data('page'));
        const isDisabled = $(this).parent().hasClass('disabled'); const isActive = $(this).parent().hasClass('active');
        if (!isNaN(page) && !isDisabled && !isActive) { currentPageE = page; fetchEData(); }
    });

    // --- UPDATED: Delete Form Handler ---
    $('#tableBodyE').on('submit', '.form-delete-e', function (e) { // Use unique ID and class, on 'submit'
        e.preventDefault(); // Prevent the form from submitting immediately
        
        const form = this; // Get the form element
        const button = $(form).find('button[type="submit"]');

        Swal.fire({
            title: 'Delete this event?', 
            text: "The associated image file will also be deleted!", 
            icon: 'warning',
            showCancelButton: true, 
            confirmButtonColor: '#d33', 
            cancelButtonColor: '#3085d6', 
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable the button to prevent double-click and show spinner
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                
                // Submit the form
                form.submit();
            }
        });
    });
    
    // --- REMOVED checkAndReloadEData() function ---

    // --- Initial Data Load ---
    fetchEData(); // Use unique function name

</script>