{{-- resources/views/admin/media/_partial/script.blade.php --}}
<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'order', sortDirection = 'asc';
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));

    var canUpdate = @json(Auth::user()->can('mediaUpdate'));
    var canDelete = @json(Auth::user()->can('mediaDelete'));

    var routes = {
        fetch: "{{ route('ajax.media.data') }}",
        show: id => `{{ route('media.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('media.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('media.destroy', ':id') }}`.replace(':id', id),
        updateOrder: "{{ route('media.updateOrder') }}",
        token: "{{ csrf_token() }}"
    };

    // --- Fetch Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();
            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="5" class="text-center text-muted">No items found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }
            res.data.forEach((item, i) => {
                let editBtnHtml = canUpdate ? `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> ` : '';
                let deleteBtnHtml = canDelete ? `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>` : '';
                
                // *** NEW: Build iframe for the table ***
                let videoHtml = `<span class="text-muted small">No Video ID</span>`;
                if(item.video_id) {
                    videoHtml = `<iframe class="video-preview-table"
                                    src="https://www.youtube.com/embed/${item.video_id}" 
                                    title="${item.title}" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                 </iframe>`;
                }

                rows += `<tr>
                    <td>${(res.current_page - 1) * res.per_page + i + 1}</td>
                    <td>${item.title || 'N/A'}</td>
                    <td>${videoHtml}</td>
                    <td>${item.order}</td>
                    <td>${editBtnHtml}${deleteBtnHtml}</td>
                </tr>`;
            });
            $('#tableBody').html(rows);
            const start = (res.current_page - 1) * res.per_page + 1;
            const end = start + res.data.length - 1;
            $('#tableRowCount').text(`Showing ${start} to ${end} of ${res.total} entries`);
            renderPagination(res);
        });
    }

    // --- Render Pagination ---
    function renderPagination(res) { 
        let paginationHtml = '';
        if (res.last_page > 1) {
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="1">&laquo;&laquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#pagination').html(paginationHtml);
    }

    // --- Event Handlers ---
    $('#searchInput').on('keyup', function () { searchTerm = $(this).val(); currentPage = 1; fetchData(); });
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column'); 
        sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc'; 
        sortColumn = col;
        $('.sortable').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc'); 
        fetchData();
    });
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault(); const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPage) { currentPage = page; fetchData(); }
    });

    $(document.body).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $('#editForm').attr('action', routes.update(id));
        $.get(routes.show(id), function (data) {
            $('#editId').val(data.id);
            $('#editTitle').val(data.title);
            $('#editYouTubeLink').val(data.youtube_link);
            editModal.show();
        }).fail(function(xhr) { Swal.fire('Error!', 'Could not fetch item data.', 'error'); });
    });

    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Delete this item?', text: "You won't be able to revert this!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteForm = $('#delete-form'); 
                deleteForm.attr('action', routes.delete(id));
                deleteForm.submit();
            }
        });
    });

    // --- Drag & Drop Reorder JS ---
    const sortableList = document.getElementById('sortableList');
    if (sortableList) {
        let sortable = Sortable.create(sortableList, {
            handle: '.sortable-handle', animation: 150, ghostClass: 'sortable-ghost',
        });
        $('#saveOrderBtn').on('click', function() {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> Saving...');
            const newOrder = sortable.toArray();
            $.ajax({
                url: routes.updateOrder, type: 'POST',
                data: { itemIds: newOrder, _token: routes.token },
                success: function(response) {
                    Swal.fire('Success!', response.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                },
                error: function(xhr) { Swal.fire('Error!', 'Could not save the new order.', 'error'); },
                complete: function() {
                    $btn.prop('disabled', false).html('<i data-feather="save" class="me-1" style="width:18px;"></i> Save Order');
                    feather.replace();
                }
            });
        });
    }

    // --- Document Ready ---
    $(document).ready(function() {
        if ($('#tableBody').length) { fetchData(); } // Fetch data only if table tab is active
        @if (session('error_modal_id') && $errors->update->any())
            var failedId = {{ session('error_modal_id') }};
            $('#editForm').attr('action', routes.update(failedId));
            $('#editId').val(failedId);
            editModal.show();
        @endif
    });
</script>