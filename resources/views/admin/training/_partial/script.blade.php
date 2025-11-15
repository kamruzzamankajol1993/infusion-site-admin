{{-- resources/views/admin/training/_partial/script.blade.php --}}
<script>
    // Use unique variables
    var currentPageT = 1, searchTermT = '', sortColumnT = 'id', sortDirectionT = 'desc';

    // --- Define Routes ---
    var routesT = { // Unique prefix
        fetch: "{{ route('ajax.training.data') }}",
        edit: id => `{{ route('training.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('training.show', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('training.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchTData() { // Unique function name
        $.get(routesT.fetch, {
            page: currentPageT, search: searchTermT, sort: sortColumnT, direction: sortDirectionT
        }, function (res) {
            let rows = ''; $('#tableBodyT').empty(); // Use unique ID

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="7" class="text-center text-muted">No trainings found.</td></tr>`; // Colspan updated to 7
                 $('#tableBodyT').html(rows); $('#tableRowCountT').text(`Showing 0 to 0 of 0 entries`); $('#paginationT').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let imageUrl = item.image ? `{{ asset('') }}${item.image}` : `{{ asset('public/admin/assets/img/placeholder.png') }}`;
                let startDate = item.start_date ? new Date(item.start_date).toLocaleDateString('en-GB') : 'N/A';
                let fee = item.training_fee !== null ? parseFloat(item.training_fee).toFixed(2) : 'N/A';

                // --- MODIFIED: Status Badge Logic ---
                let statusBadge = '';
                if (item.status === 'complete') {
                    statusBadge = `<span class="badge bg-success-soft text-success">Completed</span>`;
                } else if (item.status === 'running') {
                    statusBadge = `<span class="badge bg-primary-soft text-primary">Running</span>`;
                } else if (item.status === 'postponed') {
                    statusBadge = `<span class="badge bg-danger-soft text-danger">Postponed</span>`;
                } else { // upcoming
                     statusBadge = `<span class="badge bg-warning-soft text-warning">Upcoming</span>`;
                }
                // --- END MODIFICATION ---

                let editUrl = routesT.edit(item.id);
                let showUrl = routesT.show(item.id);
                let deleteId = item.id;

                let canUpdate = {{ Auth::user()->can('trainingUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('trainingDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('trainingView') ? 'true' : 'false' }};

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td><img src="${imageUrl}" alt="${item.title}" style="width: 80px; height: auto; object-fit: contain;"></td>
                    <td>${item.title || 'N/A'}</td>
                    {{-- Category Column Removed --}}
                    <td>${statusBadge}</td>
                    <td>${startDate}</td>
                    <td>${fee}</td>
                    <td>`;
                 if(canView) rows += `<a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm" title="View"><i class="fa fa-eye"></i></a> `;
                 if(canUpdate) rows += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm" title="Edit"><i class="fa fa-edit"></i></a> `;
                 if(canDelete) rows += `<button class="btn btn-sm btn-danger btn-delete-t btn-custom-sm" data-id="${deleteId}" title="Delete"><i class="fa fa-trash"></i></button>`; // Unique class
                 rows += `</td>
                </tr>`;
            });
            $('#tableBodyT').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountT').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderTPagination(res); // Unique function
        });
    }

    // --- Render Pagination ---
    function renderTPagination(res) { // Unique function
        let paginationHtml = '';
        if (res.last_page > 1) {
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-t" href="#" data-page="1">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-t" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-t" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-t" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-t" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationT').html(paginationHtml); // Unique ID
    }

    // --- Search Input Handler ---
    $('#searchInputT').on('keyup', function () { searchTermT = $(this).val(); currentPageT = 1; fetchTData(); }); // Use unique vars/funcs

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableT', function () { // Use unique class
        let col = $(this).data('column'); sortDirectionT = (sortColumnT === col && sortDirectionT === 'asc') ? 'desc' : 'asc'; sortColumnT = col;
        $('.sortableT').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirectionT === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchTData();
    });

    // --- Pagination Click Handler ---
    $(document).on('click', '.page-link-t', function (e) { // Use unique class
        e.preventDefault(); const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPageT) { currentPageT = page; fetchTData(); }
    });

    // --- Single Delete Button Handler ---
   // --- ============ MODIFIED DELETE HANDLER ============ ---
    $(document).on('click', '.btn-delete-t', function () { // Use unique class
        const id = $(this).data('id');
        const deleteUrl = routesT.delete(id); // Get the correct URL

        Swal.fire({
            title: 'Delete this training?',
            text: "Associated skills and documents will also be deleted. You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Find the hidden form, set its action, and submit it
                const deleteForm = $('#delete-training-form'); // Use the new form ID
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();

                // --- REMOVED AJAX CALL ---
                // $.ajax({ ... });
            }
        });
    });
    // --- ============ END MODIFIED DELETE HANDLER ============ ---

    // --- Helper Function to Reload Data After Delete ---
    function checkAndReloadTData() { // Use unique function name
        $.get(routesT.fetch, { page: currentPageT, search: searchTermT, sort: sortColumnT, direction: sortDirectionT, check_empty: true }, function (res) {
             if (res.data.length === 0 && currentPageT > 1) { currentPageT--; }
            fetchTData();
        });
    }

    // --- Initial Data Load ---
    fetchTData(); // Use unique function name

</script>