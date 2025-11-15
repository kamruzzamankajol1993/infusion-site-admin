<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';

    // --- Define Routes (using unique IDs from index) ---
    var routes = {
        fetch: "{{ route('ajax.pressRelease.data') }}",
        edit: id => `{{ route('pressRelease.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('pressRelease.show', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('pressRelease.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, 
            search: searchTerm, 
            sort: sortColumn, 
            direction: sortDirection
        }, function (res) {
            let rows = ''; 
            $('#tableBodyPR').empty(); // Use unique ID

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="6" class="text-center text-muted">No press releases found.</td></tr>`; // Colspan 6
                 $('#tableBodyPR').html(rows); 
                 $('#tableRowCountPR').text(`Showing 0 to 0 of 0 entries`); 
                 $('#paginationPR').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                // ... (imageHtml, contentPreview, releaseDate formatting is unchanged) ...
                let imageUrl = item.image ? `{{ asset('') }}${item.image}` : `{{ asset('public/No_Image_Available.jpg') }}`;
                let imageHtml = `<img src="${imageUrl}" alt="${item.title || 'Press Release'}" style="width: 100px; height: auto; object-fit: contain; border-radius: 4px;">`;
                
                let contentPreview = '<span class="text-muted small">N/A</span>';
                if(item.link) {
                    let linkHost = $('<a>').attr('href', item.link).prop('hostname');
                    contentPreview = `<a href="${item.link}" target="_blank" class="small" title="${item.link}">View Link (${linkHost}) <i data-feather="external-link" style="width:12px;"></i></a>`;
                } else if(item.description) {
                    contentPreview = $(item.description).text().substring(0, 70) + '...';
                }
                
                let releaseDate = '<span class="text-muted small">N/A</span>';
                if(item.release_date) {
                    try {
                        releaseDate = new Date(item.release_date).toLocaleDateString('en-GB', { 
                            day: '2-digit', month: 'short', year: 'numeric' 
                        });
                    } catch(e) { releaseDate = item.release_date; } 
                }

                // --- Action Buttons ---
                let editUrl = routes.edit(item.id);
                let showUrl = routes.show(item.id);
                let deleteId = item.id;
                let actionButtons = '';

                @if (Auth::user()->can('pressReleaseView'))
                    actionButtons += `<a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm" title="View"><i class="fa fa-eye"></i></a> `;
                @endif
                @if (Auth::user()->can('pressReleaseUpdate'))
                    actionButtons += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm" title="Edit"><i class="fa fa-edit"></i></a> `;
                @endif
                
                // --- 1. UPDATED DELETE BUTTON ---
                @if (Auth::user()->can('pressReleaseDelete'))
                    let deleteUrl = routes.delete(deleteId);
                    actionButtons += `
                        <form action="${deleteUrl}" method="POST" class="d-inline" id="delete-form-pr-${deleteId}">
                            <input type="hidden" name="_token" value="${routes.token}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger btn-delete-pr btn-custom-sm" data-id="${deleteId}" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    `;
                @endif
                // --- END UPDATE ---

                // --- Build Row ---
                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${imageHtml}</td>
                    <td>${item.title || 'N/A'}</td>
                    <td>${releaseDate}</td>
                    <td class="small">${contentPreview}</td>
                    <td>${actionButtons}</td>
                </tr>`;
            });
            $('#tableBodyPR').html(rows);
            feather.replace(); // To render the external-link icon

            // ... (row count and pagination rendering is unchanged) ...
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountPR').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderPagination(res);
        }).fail(function(jqXHR, textStatus, errorThrown) {
             console.error("AJAX Error:", textStatus, errorThrown);
             $('#tableBodyPR').html('<tr><td colspan="6" class="text-center text-danger">Failed to load data. Please try again.</td></tr>');
        });
    }

    // --- Render Pagination ---
    function renderPagination(res) {
        // ... (this function is unchanged) ...
        let paginationHtml = '';
        if (res.last_page > 1) {
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="1">&laquo;&laquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const maxPagesToShow = 5;
            let startPage = Math.max(1, res.current_page - Math.floor(maxPagesToShow / 2));
            let endPage = Math.min(res.last_page, startPage + maxPagesToShow - 1);
            if(endPage === res.last_page) startPage = Math.max(1, endPage - maxPagesToShow + 1);
            if(startPage > 1) paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            for (let i = startPage; i <= endPage; i++) { 
                paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`; 
            }
            if(endPage < res.last_page) paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationPR').html(paginationHtml);
    }

    // --- Search Input Handler ---
    let searchTimeout;
    $('#searchInputPR').on('keyup', function () { 
        // ... (this function is unchanged) ...
        searchTerm = $(this).val(); 
        currentPage = 1;
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(fetchData, 300);
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortablePR', function () { 
        // ... (this function is unchanged) ...
        let col = $(this).data('column'); 
        sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc'; 
        sortColumn = col;
        $('.sortablePR').removeClass('sorting_asc sorting_desc'); 
        $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc'); 
        fetchData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '#paginationPR .page-link', function (e) { 
        // ... (this function is unchanged) ...
        e.preventDefault(); 
        const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPage) { 
            currentPage = page; 
            fetchData(); 
        }
    });

    // --- 2. UPDATED Single Delete Button Handler ---
    $(document).on('click', '.btn-delete-pr', function () { // Changed selector
        const id = $(this).data('id');
        // No deleteUrl needed here

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
                // Find the form and submit it
                $(`#delete-form-pr-${id}`).submit();
            }
        });
    });
    
    // --- 3. REMOVED checkAndReloadData() function ---
    // function checkAndReloadData() { ... } // This is no longer needed.

    // --- Initial Data Load ---
    fetchData();
</script>