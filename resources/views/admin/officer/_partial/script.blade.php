<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';

    var routes = {
        fetch: "{{ route('ajax.officertable.data') }}",
        edit: `{{ route('officer.edit', ':id') }}`,
        show: `{{ route('officer.show', ':id') }}`,
        delete: `{{ route('officer.destroy', ':id') }}`,
        token: "{{ csrf_token() }}"
    };

    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage,
            search: searchTerm,
            sort: sortColumn,
            direction: sortDirection
        }, function (res) {
            let rows = '';
            res.data.forEach((item, i) => {
                let statusBadge = item.status == 1 
                    ? `<span class="badge bg-success">Active</span>` 
                    : `<span class="badge bg-danger">Inactive</span>`;

                let categories = item.categories.map(cat => 
                    `<span class="badge bg-secondary me-1">${cat.name}</span>`
                ).join(' ');
                
                let imageUrl = item.image 
                    ? `{{ asset('') }}/${item.image}` 
                    : `{{ asset('public/admin/assets/img/demo-user.svg') }}`; // A default placeholder

                let editUrl = routes.edit.replace(':id', item.id);
                let showUrl = routes.show.replace(':id', item.id);
                let deleteId = item.id;

                rows += `<tr>
                    <td>${(res.current_page - 1) * 10 + i + 1}</td>
                    <td><img src="${imageUrl}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;"></td>
                    <td>${item.name}</td>
                    <td>${categories}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm"><i class="fa fa-eye"></i></a>
                        <a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm"><i class="fa fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${deleteId}"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>`;
            });
            $('#tableBody').html(rows);

            // Pagination
            let paginationHtml = '';
            if (res.last_page > 1) {
                 paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="1">First</a></li>`;
                 paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page - 1}">Prev</a></li>`;
                
                const startPage = Math.max(1, res.current_page - 2);
                const endPage = Math.min(res.last_page, res.current_page + 2);

                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }

                 paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">Next</a></li>`;
                 paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.last_page}">Last</a></li>`;
            }
            $('#pagination').html(paginationHtml);
        });
    }

    $('#searchInput').on('keyup', function () {
        searchTerm = $(this).val();
        currentPage = 1;
        fetchData();
    });

    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column');
        sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc';
        sortColumn = col;
        fetchData();
    });

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        currentPage = parseInt($(this).data('page'));
        fetchData();
    });

   // --- ============ MODIFIED DELETE HANDLER ============ ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete.replace(':id', id); // Get the correct URL

        Swal.fire({
            title: 'Delete this officer?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Standard red delete button
            cancelButtonColor: '#3085d6', // Standard blue cancel button
            confirmButtonText: 'Yes, delete it!'
            // Removed preConfirm as we are not using AJAX
        }).then((result) => {
            if (result.isConfirmed) {
                // Find the hidden form, set its action, and submit it
                const deleteForm = $('#delete-officer-form'); // Use the new form ID
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();

                // --- REMOVED AJAX LOGIC and manual table refresh ---
                // Swal.fire({ toast: true, ... });
                // $.get(routes.fetch, ...);
            }
        });
    });
    // --- ============ END MODIFIED DELETE HANDLER ============ ---

    fetchData();
</script>