{{-- resources/views/admin/team/_partial/script.blade.php --}}
<script>
    // === START: Standard Table & Modal JS ===
    var currentPage = 1, searchTerm = '', sortColumn = 'order', sortDirection = 'asc';
    var editModal = new bootstrap.Modal(document.getElementById('editTeamModal'));

    var canUpdateTeam = @json(Auth::user()->can('teamUpdate'));
    var canDeleteTeam = @json(Auth::user()->can('teamDelete'));

    var routes = {
        fetch: "{{ route('ajax.team.data') }}",
        show: id => `{{ route('team.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('team.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('team.destroy', ':id') }}`.replace(':id', id),
        updateOrder: "{{ route('team.updateOrder') }}", // <-- New route for reordering
        token: "{{ csrf_token() }}"
    };

    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="6" class="text-center text-muted">No team members found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let imageHtml = item.image
                    ? `<img src="{{ asset('') }}${item.image}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">`
                    : `<span class="text-muted small">No Image</span>`;
                
                let editBtnHtml = ''; let deleteBtnHtml = '';
                if (canUpdateTeam) {
                    editBtnHtml = `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `;
                }
                if (canDeleteTeam) {
                    deleteBtnHtml = `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>`;
                }

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${imageHtml}</td>
                    <td>${item.name || 'N/A'}</td>
                    <td>${item.designation || 'N/A'}</td>
                    <td>${item.order}</td>
                    <td>${editBtnHtml}${deleteBtnHtml}</td>
                </tr>`;
            });
            $('#tableBody').html(rows);

             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderPagination(res);
        });
    }

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

    $('#searchInput').on('keyup', function () { searchTerm = $(this).val(); currentPage = 1; fetchData(); });

    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column'); 
        sortDirection = sortColumn === col ? (sortDirection === 'asc' ? 'desc' : 'asc') : 'asc'; 
        sortColumn = col;
        $('.sortable').removeClass('sorting_asc sorting_desc'); 
        $(this).addClass(sortDirection === 'asc' ? 'sorting_asc' : 'sorting_desc'); 
        fetchData();
    });

    $(document).on('click', '.page-link', function (e) {
        e.preventDefault(); const page = parseInt($(this).data('page'));
        if (!isNaN(page) && page !== currentPage) { currentPage = page; fetchData(); }
    });

    $(document.body).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        const preview = $('#editImagePreview');
        const placeholder = $('#editPreviewPlaceholder');

        $('#editTeamForm').attr('action', routes.update(id));
        $('#editTeamForm')[0].reset();
        $('#editTeamForm .is-invalid').removeClass('is-invalid');
        $('#editTeamForm .invalid-feedback.d-block').remove();
        
        preview.attr('src', '#').hide();
        placeholder.show();
        preview.removeAttr('data-original-src'); 

        $.get(routes.show(id), function (data) {
            $('#editTeamId').val(data.id);
            $('#editName').val(data.name);
            $('#editDesignation').val(data.designation); // <-- Set designation
            
            if (data.image_url) {
                preview.attr('src', data.image_url).show();
                preview.attr('data-original-src', data.image_url); 
                placeholder.hide();
            } else {
                 preview.hide();
                 placeholder.show();
            }
            editModal.show();
        }).fail(function(xhr) {
             Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch member data.', 'error');
        });
    });

    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); 

        Swal.fire({
            title: 'Delete this member?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const deleteForm = $('#delete-team-form'); 
                deleteForm.attr('action', deleteUrl);
                deleteForm.submit();
            }
        });
    });

    $(document).ready(function() {
        if ($('#tableBody').length) { // Only fetch data if the table tab is active
            fetchData(); 
        }

        @if (session('error_modal_id') && $errors->update->any())
            var failedId = {{ session('error_modal_id') }};
            $('#editTeamForm').attr('action', routes.update(failedId));
            $('#editTeamId').val(failedId);
            editModal.show();
        @endif
    });
    // === END: Standard Table & Modal JS ===


    // === START: Drag & Drop Reorder JS ===
    const sortableList = document.getElementById('sortableList');
    if (sortableList) {
        let sortable = Sortable.create(sortableList, {
            handle: '.sortable-handle', // Use this class to drag
            animation: 150,
            ghostClass: 'sortable-ghost',
        });

        $('#saveOrderBtn').on('click', function() {
            const $btn = $(this);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Saving...');

            const newOrder = sortable.toArray(); // Gets array of data-id attributes

            $.ajax({
                url: routes.updateOrder,
                type: 'POST',
                data: {
                    teamIds: newOrder,
                    _token: routes.token
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    // Optionally, reload the page to see new order numbers
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Could not save the new order.'
                    });
                },
                complete: function() {
                    $btn.prop('disabled', false).html('<i data-feather="save" class="me-1" style="width:18px;"></i> Save Order');
                    feather.replace(); // Re-render icons
                }
            });
        });
    }
    // === END: Drag & Drop Reorder JS ===

</script>