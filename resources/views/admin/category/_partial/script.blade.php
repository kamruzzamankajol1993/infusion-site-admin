<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Initializes a custom, searchable, multi-select component.
     * @param {jQuery} container - The container element for the custom select.
     */
    function initCustomSelect(container) {
        const targetSelector = container.data('target-select');
        const hiddenSelect = $(targetSelector);
        let optionsHtml = '';

        hiddenSelect.find('option').each(function() {
            const value = $(this).val();
            const text = $(this).text();
            optionsHtml += `
                <li>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="${value}" id="option-${targetSelector.substring(1)}-${value}">
                        <label class="form-check-label" for="option-${targetSelector.substring(1)}-${value}">${text}</label>
                    </div>
                </li>
            `;
        });

        const componentHtml = `
            <div class="custom-select-control">
                <span class="custom-select-placeholder">Select parent categories...</span>
            </div>
            <div class="custom-select-dropdown">
                <div class="custom-select-header">
                    <input type="text" class="custom-select-search form-control" placeholder="Search...">
                    <button type="button" class="close-dropdown-btn">&times;</button>
                </div>
                <ul class="custom-select-options">${optionsHtml}</ul>
            </div>
        `;
        container.html(componentHtml);

        const control = container.find('.custom-select-control');
        const dropdown = container.find('.custom-select-dropdown');
        const searchInput = container.find('.custom-select-search');
        const optionsList = container.find('.custom-select-options');
        const placeholder = container.find('.custom-select-placeholder');
        const closeBtn = container.find('.close-dropdown-btn');

        function renderPills() {
            control.find('.custom-select-pill').remove();
            let hasSelection = false;
            hiddenSelect.find('option:selected').each(function() {
                hasSelection = true;
                const value = $(this).val();
                const text = $(this).text();
                const pill = $(`<span class="custom-select-pill">${text}<span class="remove-pill" data-value="${value}">&times;</span></span>`);
                control.prepend(pill);
            });
            placeholder.toggle(!hasSelection);
        }

        // Event listeners
        control.on('click', function(e) {
            if (!$(e.target).hasClass('remove-pill')) {
                dropdown.toggle();
            }
        });
        
        closeBtn.on('click', function() {
            dropdown.hide();
        });

        $(document).on('click', function(e) {
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                dropdown.hide();
            }
        });

        searchInput.on('keyup', function() {
            const searchTerm = $(this).val().toLowerCase();
            optionsList.find('li').each(function() {
                const labelText = $(this).find('label').text().toLowerCase();
                $(this).toggle(labelText.includes(searchTerm));
            });
        });

        optionsList.on('change', 'input[type="checkbox"]', function() {
            const value = $(this).val();
            const isChecked = $(this).is(':checked');
            hiddenSelect.find(`option[value="${value}"]`).prop('selected', isChecked);
            renderPills();
        });

        control.on('click', '.remove-pill', function() {
            const value = $(this).data('value');
            hiddenSelect.find(`option[value="${value}"]`).prop('selected', false);
            optionsList.find(`input[value="${value}"]`).prop('checked', false);
            renderPills();
        });

        // Public method to update component externally
        container.data('update', function(selectedValues = []) {
            hiddenSelect.find('option').prop('selected', false);
            optionsList.find('input[type="checkbox"]').prop('checked', false);
            selectedValues.forEach(value => {
                hiddenSelect.find(`option[value="${value}"]`).prop('selected', true);
                optionsList.find(`input[value="${value}"]`).prop('checked', true);
            });
            renderPills();
        });

        renderPills(); // Initial render
    }

    // --- MAIN SCRIPT LOGIC ---
    $(document).ready(function() {
        $('#addModal').one('shown.bs.modal', function () {
            initCustomSelect($(this).find('.custom-select-container'));
        });
        $('#editModal').one('shown.bs.modal', function () {
            initCustomSelect($(this).find('.custom-select-container'));
        });
    });

    var editModal = new bootstrap.Modal(document.getElementById('editModal'));
    var currentPage = 1, searchTerm = '', sortColumn = 'id', sortDirection = 'desc';
    var routes = {
        fetch: "{{ route('ajax.category.data') }}",
        show: id => `{{ route('category.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('category.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('category.destroy', ':id') }}`.replace(':id', id),
        deleteMultiple: "{{ route('category.destroy-multiple') }}",
        csrf: "{{ csrf_token() }}"
    };
    
    // Show Edit Modal
    $(document).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $.get(routes.show(id), function (item) {
            $('#editId').val(item.id);
            $('#editName').val(item.name);
            $('#editDescription').val(item.description);
            $('#editStatus').val(item.status);
            $('#edit_is_featured').prop('checked', item.is_featured == 1); // Set featured switch
            
            const customSelectContainer = $('#editModal .custom-select-container');
            if (customSelectContainer.data('update')) {
                customSelectContainer.data('update')(item.parent_ids || []);
            }
            
            $('#imagePreview').attr('src', item.image ? `{{ asset('/') }}public/${item.image}` : '').toggle(!!item.image);
            editModal.show();
        });
    });

    // Reset modals on close
    $('#addModal, #editModal').on('hidden.bs.modal', function () {
        const form = $(this).find('form');
        form[0].reset();
        form.find('input[name="is_featured"]').prop('checked', false); // Reset featured switch

        const customSelect = $(this).find('.custom-select-container');
        if (customSelect.data('update')) {
            customSelect.data('update')([]); // Reset custom select to empty
        }
    });

    // Fetch and render table data
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage,
            search: searchTerm,
            sort: sortColumn,
            direction: sortDirection
        }, function (res) {
            let rows = '';
            res.data.forEach((item, i) => {
                const imageUrl = item.image ? `{{ asset('/') }}public/${item.image}` : 'https://placehold.co/50x50/EFEFEF/AAAAAA&text=N/A';
                const statusBadge = item.status == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                const featuredBadge = item.is_featured == 1 ? '<span class="badge bg-primary">Yes</span>' : '<span class="badge bg-secondary">No</span>';
                const parentBadges = item.parents.length > 0 ? item.parents.map(p => `<span class="badge bg-info me-1">${p.name}</span>`).join('') : '<span class="text-muted">—</span>';
                
                rows += `<tr>
                    <td><input class="form-check-input row-checkbox" type="checkbox" data-id="${item.id}"></td>
                    <td>${(res.current_page - 1) * 10 + i + 1}</td>
                    <td><img src="${imageUrl}" alt="${item.name}" width="50" class="img-thumbnail"></td>
                    <td>${item.name}</td>
                    <td>${parentBadges}</td>
                    <td>${featuredBadge}</td>
                    <td>${statusBadge}</td>
                    <td class="d-flex gap-2">
                        <button class="btn btn-sm btn-info btn-edit" data-id="${item.id}"><i class="fa fa-edit"></i></button>
                        <form action="${routes.delete(item.id)}" method="POST" class="d-inline">
                            <input type="hidden" name="_token" value="${routes.csrf}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>`;
            });
            $('#tableBody').html(rows);

            // Pagination
            let paginationHtml = '';
            if (res.last_page > 1) {
                paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link" href="#" data-page="1">First</a></li>`;
                for (let i = 1; i <= res.last_page; i++) {
                    paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                }
                paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link" href="#" data-page="${res.last_page}">Last</a></li>`;
            }
            $('#pagination').html(paginationHtml);
        });
    }

    // Search and Pagination handlers
    $('#searchInput').on('keyup', function () { searchTerm = $(this).val(); currentPage = 1; fetchData(); });
    $(document).on('click', '.page-link', function (e) { e.preventDefault(); currentPage = $(this).data('page'); fetchData(); });

    // Handle Edit Form Submission
    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        const id = $('#editId').val();
        let formData = new FormData(this);
        formData.append('_method', 'PUT');

        $.ajax({
            url: routes.update(id),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(res) {
                editModal.hide();
                fetchData();
                Swal.fire('Success!', res.message, 'success');
            },
            error: function(err) {
                let errorHtml = '<ul class="text-start mb-0">';
                if (err.status === 422 && err.responseJSON && err.responseJSON.errors) {
                    $.each(err.responseJSON.errors, function (key, value) {
                        errorHtml += `<li>${value[0]}</li>`;
                    });
                } else {
                    errorHtml += '<li>An unexpected error occurred. Please try again.</li>';
                }
                errorHtml += '</ul>';
                Swal.fire({ icon: 'error', title: 'Submission Failed', html: errorHtml });
            }
        });
    });

    // Single Delete
    $(document).on('click', '.btn-delete', function () {
        const form = $(this).closest('form');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Multiple Checkbox Management
    function manageCheckboxes() {
        const totalRows = $('.row-checkbox').length;
        const checkedRows = $('.row-checkbox:checked').length;
        $('#selectAllCheckbox').prop('checked', totalRows > 0 && totalRows === checkedRows);
        $('#deleteSelectedBtn').toggle(checkedRows > 0);
    }
    $('#selectAllCheckbox').on('click', function() {
        $('.row-checkbox').prop('checked', this.checked);
        manageCheckboxes();
    });
    $(document).on('change', '.row-checkbox', function() {
        manageCheckboxes();
    });

    // Multiple Delete
    $('#deleteSelectedBtn').on('click', function() {
        const selectedIds = $('.row-checkbox:checked').map(function() { return $(this).data('id'); }).get();
        if (selectedIds.length === 0) return;

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete ${selectedIds.length} categories. This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete them!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: routes.deleteMultiple,
                    type: 'POST',
                    data: { _token: routes.csrf, ids: selectedIds },
                    success: function(res) {
                        Swal.fire('Deleted!', res.message, 'success');
                        $('#selectAllCheckbox').prop('checked', false);
                        fetchData();
                    },
                    error: function(err) {
                        Swal.fire({ icon: 'error', title: 'Deletion Failed', text: 'An unexpected error occurred. Please try again.' });
                    }
                });
            }
        });
    });
    
    // Initial data load
    fetchData();
</script>