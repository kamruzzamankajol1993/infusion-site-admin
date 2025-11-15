{{-- resources/views/admin/download/_partial/script.blade.php --}}
<script>
    // 1. Use unique variables
    var currentPageD = 1, searchTermD = '', sortColumnD = 'date', sortDirectionD = 'desc';

    // 2. Define new routes
    var routesD = {
        fetch: "{{ route('ajax.download.data') }}",
        edit: id => `{{ route('download.edit', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('download.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // 3. Use unique function name
    function fetchDData() {
        $.get(routesD.fetch, {
            page: currentPageD,
            search: searchTermD,
            sort: sortColumnD,
            direction: sortDirectionD,
            perPage: 10
        }, function (res) {
            let rows = '';
            // 4. Use unique table ID
            $('#tableBodyD').empty();

            if (!res.data || res.data.length === 0) {
                 // 5. Update colspan to 4 (Sl, Title, Date, Action)
                 rows = `<tr><td colspan="4" class="text-center text-muted py-4">No downloads found.</td></tr>`;
                 $('#tableBodyD').html(rows);
                 $('#tableRowCountD').text(`Showing 0 to 0 of 0 entries`);
                 $('#paginationD').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let noticeDate = item.date ? new Date(item.date).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric'}) : '<span class="text-muted">N/A</span>';
                let pdfUrl = item.pdf_url || '#';
                let pdfLinkDisabled = !item.pdf_url;
                let editUrl = routesD.edit(item.id);
                let deleteUrl = routesD.delete(item.id); 

                // 6. Check new permissions
                let canUpdate = {{ Auth::user()->can('downloadUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('downloadDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('downloadView') ? 'true' : 'false' }};

                // 7. Build row (removed category column)
                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${item.title || '<span class="text-muted">N/A</span>'}</td>
                    <td>${noticeDate}</td>
                    <td>`; // Start Actions

                 if(canView) {
                     rows += `<a href="${pdfUrl}" target="_blank"
                                class="btn btn-sm btn-primary btn-custom-sm ${pdfLinkDisabled ? 'disabled' : ''}"
                                title="View PDF" ${pdfLinkDisabled ? 'aria-disabled="true" style="pointer-events: none; opacity: 0.6;"' : ''}>
                                <i class="fa fa-file-pdf"></i>
                              </a> `;
                 }
                 if(canUpdate) {
                     rows += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm" title="Edit">
                                <i class="fa fa-edit"></i>
                              </a> `;
                 }
                 if(canDelete) {
                    // 8. Use unique class
                    rows += `<form action="${deleteUrl}" method="POST" class="d-inline-block form-delete-d" style="margin-bottom: 0;">
                                <input type="hidden" name="_token" value="${routesD.token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger btn-custom-sm" title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>`;
                 }
                 rows += `</td>
                </tr>`;
            });
            // 9. Use unique IDs
            $('#tableBodyD').html(rows);
            const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
            const endEntry = startEntry + res.data.length - 1;
            $('#tableRowCountD').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderDPagination(res);
        })
        .fail(function() {
            $('#tableBodyD').html('<tr><td colspan="4" class="text-center text-danger py-4">Could not load data.</td></tr>');
            $('#tableRowCountD').text(`Showing 0 to 0 of 0 entries`);
            $('#paginationD').empty();
        });
    }

    // 10. Use unique function name
    function renderDPagination(res) {
        let paginationHtml = '';
        if (res.last_page > 1) {
            // 11. Use unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-d" href="#" data-page="1">&laquo;&laquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-d" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-d" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-d" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-d" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationD').html(paginationHtml); // 12. Use unique ID
    }

    // 13. Use unique IDs and vars
    let searchTimeoutD;
    $('#searchInputD').on('keyup', function () {
        clearTimeout(searchTimeoutD);
        searchTermD = $(this).val();
        searchTimeoutD = setTimeout(function() {
            currentPageD = 1;
            fetchDData();
        }, 300);
    });

    // 14. Use unique class and vars
    $(document).on('click', '.sortableD', function () {
        let col = $(this).data('column');
        sortDirectionD = (sortColumnD === col && sortDirectionD === 'asc') ? 'desc' : 'asc';
        sortColumnD = col;
        $('.sortableD').removeClass('sorting_asc sorting_desc');
        $(this).addClass(sortDirectionD === 'asc' ? 'sorting_asc' : 'sorting_desc');
        fetchDData();
    });

    // 15. Use unique IDs and class
    $('#paginationD').on('click', '.page-link-d', function (e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        const isDisabled = $(this).parent().hasClass('disabled');
        const isActive = $(this).parent().hasClass('active');
        if (!isNaN(page) && !isDisabled && !isActive) {
            currentPageD = page;
            fetchDData();
        }
    });

    // 16. Use unique IDs and class
    $('#tableBodyD').on('submit', '.form-delete-d', function (e) {
        e.preventDefault();
        const form = this;
        const button = $(form).find('button[type="submit"]');

        Swal.fire({
            title: 'Delete this download?',
            text: "The associated PDF file will also be deleted. This cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
                form.submit();
            }
        });
    });

    // 17. Use unique function name
    fetchDData();

</script>