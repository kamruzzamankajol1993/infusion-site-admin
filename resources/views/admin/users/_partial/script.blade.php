<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  
  
    var routes = {
        fetch: "{{ route('ajax.usertable.data') }}",
        edit: id => `{{ route('users.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('users.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('users.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('users.destroy', ':id') }}`.replace(':id', id),
        csrf: "{{ csrf_token() }}"
    };

    var currentPage = 1;
    var searchTerm = '';
    var sortColumn = 'id';
    var sortDirection = 'desc';

    function fetchData() {
    $.get(routes.fetch, {
        page: currentPage,
        search: searchTerm,
        sort: sortColumn,
        direction: sortDirection,
        perPage: 10
    }, function (res) {
        let rows = '';
        res.data.forEach((item, index) => {
                const sl = (res.current_page - 1) * 10 + index + 1;
                const profile = item.image 
                    ? `{{ asset('/') }}${item.image}` 
                    : '{{ asset('/') }}public/No_Image_Available.jpg';

                const roles = item.roles.map(role => `<span class="badge bg-success">${role}</span>`).join(' ');

                const statusBadge = item.status == 1 
                    ? `<span class="badge bg-success">Active</span>` 
                    : `<span class="badge bg-danger">Inactive</span>`;
                
                // Added signature
                const signature = item.signature
                    ? `{{ asset('/') }}${item.signature}`
                    : '';
                
                const signatureImg = signature
                    ? `<img src="${signature}" style="height:30px; border:1px solid #ddd;" onerror="this.src='{{ asset('/') }}public/No_Image_Available.jpg'; this.style.display='none';">`
                    : 'N/A';


             

                rows += `
                    <tr>
                        <td>${sl}</td>
                        <td>${item.department_name ?? ''}</td>
                        <td><img src="${profile}" style="height:30px;" onerror="this.src='{{ asset('/') }}public/No_Image_Available.jpg';"></td>
                        <td>${item.name}</td>
                        <td>${item.designation_name ?? ''}</td>
                        <td>${item.phone}</td>
                        <td>${item.email}</td>
                        <td>${item.address}</td>
                        <td>${roles}</td>
                        <td>${signatureImg}</td> <td>${statusBadge}</td>
                        <td>${item.viewpassword}</td>
                        <td>
 ${res.can_show ? `<a href="${routes.show(item.id)}" class="btn btn-sm btn-info btn-custom-sm"><i class="fa fa-eye"></i></a>` : ''}
                             ${res.can_edit ? `<a href="${routes.edit(item.id)}" class="btn btn-sm btn-primary btn-custom-sm"><i class="fa fa-edit"></i></a>` : ''}


                ${res.can_delete ? `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}"><i class="fa fa-trash"></i></button>` : ''}

                            </td>
                    </tr>`;
            });
            $('#tableBody').html(rows);

            // Pagination
            var paginationHtml = '';
            if (res.last_page > 1) {
                paginationHtml += `
                    <li class="page-item ${res.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="1">First</a>
                    </li>
                    <li class="page-item ${res.current_page === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${res.current_page - 1}">Prev</a>
                    </li>`;

                const start = Math.max(1, res.current_page - 2);
                const end = Math.min(res.last_page, res.current_page + 2);

                for (var i = start; i <= end; i++) {
                    paginationHtml += `
                        <li class="page-item ${i === res.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>`;
                }

                paginationHtml += `
                    <li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${res.current_page + 1}">Next</a>
                    </li>
                    <li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${res.last_page}">Last</a>
                    </li>`;
            }
            $('#pagination').html(paginationHtml);
        });
    }

    $(document).on('keyup', '#searchInput', function () {
        searchTerm = $(this).val();
        currentPage = 1;
        fetchData();
    });

    $(document).on('click', '.sortable', function () {
        const col = $(this).data('column');
        sortDirection = (sortColumn === col && sortDirection === 'asc') ? 'desc' : 'asc';
        sortColumn = col;
        fetchData();
    });

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page && !$(this).parent().hasClass('disabled') && !$(this).parent().hasClass('active')) {
            currentPage = page;
            fetchData();
        }
    });
   

    $(document).on('click', '.btn-delete', function () {
    const id = $(this).data('id');
    Swal.fire({
        title: 'Delete this user?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        preConfirm: () => {
            return $.ajax({
                url: routes.delete(id),
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' }
            });
        }
    }).then(result => {
        if (result.isConfirmed) {
            Swal.fire({ toast: true, icon: 'success', title: 'User deleted', showConfirmButton: false, timer: 3000 });

            // Re-fetch and adjust page if needed
            $.get(routes.fetch, {
                page: currentPage,
                search: searchTerm,
                sort: sortColumn,
                direction: sortDirection
            }, function (res) {
                if (res.data.length === 0 && currentPage > 1) {
                    currentPage--;
                }
                fetchData();
            });
        }
    });
});

 
    fetchData();
</script>

<script>
    // Note: The 'invoiceFilter' ID is not present in index.blade.php.
    // This script might be for a different part of the page or legacy code.
    // I am including it as it was in your original file.
    var exportInvoicesUrl = "{{ route('downloadUserExcel') }}";
    var exportInvoicesUrlPdf = "{{ route('downloadUserPdf') }}";

    var filterElement = document.getElementById('invoiceFilter');
    if (filterElement) {
        filterElement.addEventListener('change', function() {
            var selected = this.value;
            if (!selected) return;

            var url = '';
            if( selected == 'excel'){
                url = `${exportInvoicesUrl}`;
            } else {
                url = `${exportInvoicesUrlPdf}`;
            }

            window.location.href = url;
        });
    }
</script>