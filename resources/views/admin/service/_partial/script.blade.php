{{-- resources/views/admin/service/_partial/script.blade.php --}}
<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';

    // --- Define Routes ---
    // Use arrow functions for routes needing ID replacement
    var routes = {
        fetch: "{{ route('ajax.service.data') }}",
        edit: id => `{{ route('service.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('service.show', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('service.destroy', ':id') }}`.replace(':id', id), // Function to replace :id
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
            $('#tableBody').empty(); // Clear previous

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="5" class="text-center text-muted">No services found.</td></tr>`; // Correct colspan
                 $('#tableBody').html(rows);
                 $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`);
                 $('#pagination').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let descriptionPreview = item.description ? stripHtml(item.description).substring(0, 100) + (stripHtml(item.description).length > 100 ? '...' : '') : 'N/A';
                let imageUrl = item.image ? `{{ asset('') }}${item.image}` : `{{ asset('public/admin/assets/img/placeholder.png') }}`; // Default placeholder
                let editUrl = routes.edit(item.id); // Call the function to get URL
                let showUrl = routes.show(item.id); // Call the function to get URL
                let deleteId = item.id;

                // Determine permission states
                let canUpdate = {{ Auth::user()->can('serviceUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('serviceDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('serviceView') ? 'true' : 'false' }}; // Assuming view permission

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td><img src="${imageUrl}" alt="${item.title}" style="width: 80px; height: auto; object-fit: contain;"></td>
                    <td>${item.title || 'N/A'}</td>
                    <td>${descriptionPreview}</td>
                    <td>`;
                 if(canView) {
                     rows += `<a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm me-1" title="View"><i class="fa fa-eye"></i></a>`; // Added me-1 for spacing
                 }
                 if(canUpdate) {
                     rows += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm me-1" title="Edit"><i class="fa fa-edit"></i></a>`; // Added me-1 for spacing
                 }
                 if(canDelete) {
                    // This button triggers the SweetAlert confirmation
                    rows += `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${deleteId}" title="Delete"><i class="fa fa-trash"></i></button>`;
                 }
                 rows += `</td>
                </tr>`;
            });
            $('#tableBody').html(rows);

            // Update Row Count Text
            const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
            const endEntry = startEntry + res.data.length - 1;
            $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);

            // Render Pagination
            renderPagination(res);
        }).fail(function(jqXHR, textStatus, errorThrown) { // Added basic error handling for fetch
             console.error("AJAX Error:", textStatus, errorThrown);
             $('#tableBody').html(`<tr><td colspan="5" class="text-center text-danger">Failed to load data. Please try again.</td></tr>`); // Correct colspan
        });
    }

    // Helper function to strip HTML tags for preview
    function stripHtml(html) {
        let tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    }

    // --- Render Pagination Links --- (Standard pagination logic)
    function renderPagination(res) {
        let paginationHtml = '';
        if (res.last_page > 1) {
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="1">&laquo;&laquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;

            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);

            for (let i = startPage; i <= endPage; i++) {
                paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }

            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#pagination').html(paginationHtml);
    }

    // --- Search Input Handler ---
    $('#searchInput').on('keyup', function () {
        searchTerm = $(this).val();
        currentPage = 1; // Reset to page 1 on search
        fetchData();
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column');
        // Toggle direction if same column, otherwise default to 'asc'
        sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
        sortColumn = col;
        // Update visual indicators
        $('.sortable').removeClass('sorting_asc sorting_desc');
        $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc');
        fetchData(); // Refetch sorted data
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

    // --- Single Delete Button Handler (using form submission) ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); // Now correctly calls the function to get the URL

        Swal.fire({
            title: 'Delete this service?',
            text: "This will also delete associated keypoints. You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Find the hidden form, set its action attribute, and submit it
                const deleteForm = $('#delete-service-form');
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();
                // No AJAX call needed here
            }
        });
    });

    // --- Initial Data Load ---
    fetchData();
</script>