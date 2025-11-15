@extends('admin.master.master')
@section('title', 'Shareholder List')
@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <h2 class="mb-0">Shareholder List</h2>
            <input class="form-control" id="searchInput" type="search" placeholder="Search by Name or Email..." style="max-width: 300px;">
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                @include('flash_message')
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:5%">SL</th>
                                <th>Shareholder</th>
                                <th>Branch</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                             <tr><td colspan="4" class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white d-flex justify-content-center">
                <nav id="pagination"></nav>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    let currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';

    const routes = {
        fetch: "{{ route('ajax.shareholders.data') }}",
        show: id => `{{ url('admin/users') }}/${id}`,
        edit: id => `{{ url('admin/users') }}/${id}/edit`,
        destroy: id => `{{ url('admin/users') }}/${id}`,
        asset: path => `{{ asset('') }}${path}`,
        defaultImage: `{{ asset('public/No_Image_Available.jpg') }}`,
        csrf: "{{ csrf_token() }}"
    };

    function fetchData(page = 1) {
        currentPage = page;
        const loaderRow = `<tr><td colspan="4" class="text-center p-5"><div class="spinner-border text-primary" role="status"></div></td></tr>`;
        $('#tableBody').html(loaderRow);

        $.get(routes.fetch, {
            page: currentPage,
            search: searchTerm,
            sort: sortColumn,
            direction: sortDirection
        }).done(res => {
            renderTable(res.data);
            renderPagination(res);
            feather.replace(); // Refresh icons
        }).fail(() => {
            $('#tableBody').html('<tr><td colspan="4" class="text-center text-danger">Failed to load data.</td></tr>');
        });
    }

    function renderTable(users) {
        if (users.length === 0) {
            $('#tableBody').html('<tr><td colspan="4" class="text-center text-muted">No shareholders found.</td></tr>');
            return;
        }

        let rows = '';
        users.forEach((user, index) => {
            const sl = (currentPage - 1) * 10 + index + 1;
            const profile = user.image ? routes.asset(user.image) : routes.defaultImage;

            rows += `
                <tr>
                    <td>${sl}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${profile}" class="rounded-circle me-2" width="40" height="40" alt="${user.name}" onerror="this.src='${routes.defaultImage}'">
                            <div>
                                <div class="fw-bold">${user.name}</div>
                                <small class="text-muted">${user.email}</small>
                            </div>
                        </div>
                    </td>
                    <td>${user.branch?.name || 'N/A'}</td>
                    <td class="text-center">
                        ${user.can_show ? `<a href="${routes.show(user.id)}" class="btn btn-sm btn-outline-secondary"><i data-feather="eye"></i></a>` : ''}
                        ${user.can_edit ? `<a href="${routes.edit(user.id)}" class="btn btn-sm btn-outline-primary"><i data-feather="edit-2"></i></a>` : ''}
                        ${user.can_delete ? `<button class="btn btn-sm btn-outline-danger btn-delete" data-id="${user.id}"><i data-feather="trash-2"></i></button>` : ''}
                    </td>
                </tr>
            `;
        });
        $('#tableBody').html(rows);
    }

    function renderPagination(res) {
        const pagination = $('#pagination').empty();
        if (!res.links) return;
        
        const paginationList = $('<ul class="pagination mb-0"></ul>');
        res.links.forEach(link => {
            const pageItem = $(`<li class="page-item ${link.active ? 'active' : ''} ${!link.url ? 'disabled' : ''}"></li>`);
            const pageLink = $(`<a class="page-link" href="#">${link.label}</a>`);
            if (link.url) {
                pageLink.on('click', e => {
                    e.preventDefault();
                    fetchData(new URL(link.url).searchParams.get('page'));
                });
            }
            pageItem.append(pageLink);
            paginationList.append(pageItem);
        });
        pagination.append(paginationList);
    }
    
    // MODIFIED: Added debounce to the search input
    $('#searchInput').on('keyup', _.debounce(function() {
        searchTerm = $(this).val();
        fetchData(1);
    }, 300));

    $(document).on('click', '.btn-delete', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?', text: "You won't be able to revert this!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routes.destroy(id), method: 'DELETE', data: { _token: routes.csrf },
                    success: () => {
                        Swal.fire('Deleted!', 'The user has been deleted.', 'success');
                        fetchData(currentPage);
                    },
                    error: () => Swal.fire('Error!', 'Could not delete the user.', 'error')
                });
            }
        });
    });

    fetchData(); // Initial Load
});
</script>
@endsection