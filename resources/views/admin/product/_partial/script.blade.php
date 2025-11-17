<script>
    var currentPage = 1, searchTerm = '', sortColumn = 'order', sortDirection = 'asc';
    var addModal = new bootstrap.Modal(document.getElementById('addModal'));
    var editModal = new bootstrap.Modal(document.getElementById('editModal'));

    var canUpdate = @json(Auth::user()->can('productUpdate'));
    var canDelete = @json(Auth::user()->can('productDelete'));

    var routes = {
        fetch: "{{ route('ajax.product.data') }}",
        show: id => `{{ route('product.show', ':id') }}`.replace(':id', id),
        update: id => `{{ route('product.update', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('product.destroy', ':id') }}`.replace(':id', id),
        updateOrder: "{{ route('product.updateOrder') }}",
        assetBase: "{{ asset('') }}"
    };

    // --- Summernote Initializer ---
    function initSummernote(selector) {
        try {
            $(selector).summernote({ height: 120, toolbar: [
                ['style', ['bold', 'italic', 'underline']], ['para', ['ul', 'ol', 'paragraph']]
            ]});
        } catch(e) { console.warn("Summernote failed to initialize."); }
    }

    // --- Fetch and Render Table Data ---
    function fetchData() {
        $.get(routes.fetch, {
            page: currentPage, search: searchTerm, sort: sortColumn, direction: sortDirection
        }, function (res) {
            let rows = ''; $('#tableBody').empty();
            let items = res.data || [];

            if (items.length === 0) {
                 rows = `<tr><td colspan="8" class="text-center text-muted">No products found.</td></tr>`;
                 $('#tableBody').html(rows); $('#tableRowCount').text(`Showing 0 to 0 of 0 entries`); $('#pagination').empty();
                 return;
            }

            items.forEach((item, i) => {
                let sl = (res.current_page - 1) * res.per_page + i + 1;
                let status = item.status ? `<span class="badge bg-success">Active</span>` : `<span class="badge bg-danger">Inactive</span>`;
                let category = item.category ? item.category.name : '<span class="text-muted">N/A</span>';
                let image = item.image ? `<img src="${routes.assetBase}${item.image}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: cover;">` : 'N/A';
                
                let price = `৳${parseFloat(item.selling_price).toFixed(2)}`;
                if(item.discount_price > 0) {
                    price = `<strong>৳${parseFloat(item.discount_price).toFixed(2)}</strong> <del class="text-muted">৳${parseFloat(item.selling_price).toFixed(2)}</del>`;
                }

                let editBtn = canUpdate ? `<button class="btn btn-sm btn-info btn-edit btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> ` : '';
                let deleteBtn = canDelete ? `<button class="btn btn-sm btn-danger btn-delete btn-custom-sm" data-id="${item.id}" title="Delete"><i class="fa fa-trash"></i></button>` : '';

                rows += `<tr>
                    <td>${sl}</td>
                    <td>${image}</td>
                    <td>${item.name || 'N/A'}</td>
                    <td>${category}</td>
                    <td>${price}</td>
                    <td>${item.stock_quantity}</td>
                    <td>${status}</td>
                    <td>${editBtn}${deleteBtn}</td>
                </tr>`;
            });
            $('#tableBody').html(rows);
            
            const startEntry = (res.current_page - 1) * res.per_page + 1;
            const endEntry = startEntry + items.length - 1;
            $('#tableRowCount').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
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

    // --- Search, Sort, Pagination Handlers ---
    $('#searchInput').on('keyup', function () { searchTerm = $(this).val(); currentPage = 1; fetchData(); });
    $(document).on('click', '.sortable', function () {
        let col = $(this).data('column'); 
        if (col === 'slug') return;
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

    // --- Edit Button Click Handler (Show Modal & Fetch Data) ---
    $(document.body).on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        $('#editForm').attr('action', routes.update(id));
        
        // Reset modal state
        $('#editForm')[0].reset();
        $('#editDescription').summernote('code', '');
        $('#packages-container-edit').html(''); // Clear dynamic packages
        $('#editImagePreview').hide().attr('src', '#');
        $('#editForm .is-invalid').removeClass('is-invalid');
        $('#editForm .text-danger').remove();

        $.get(routes.show(id), function (data) {
            $('#editName').val(data.name);
            $('#editDescription').summernote('code', data.description);
            $('#editSellingPrice').val(data.selling_price);
            $('#editDiscountPrice').val(data.discount_price);
            $('#editBuyingPrice').val(data.buying_price);
            $('#editCategory').val(data.category_id || '');
            $('#editSku').val(data.sku);
            $('#editStock').val(data.stock_quantity);
            $('#editStatus').prop('checked', data.status);
            $('#editTopSelling').prop('checked', data.is_top_selling_product);

            if (data.image_url) {
                $('#editImagePreview').attr('src', data.image_url).data('original-src', data.image_url).show();
            }

            // Populate packages
            const container = $('#packages-container-edit');
            container.html(''); // Clear again just in case
            editPackageCounter = 0;
            if (Array.isArray(data.packages) && data.packages.length > 0) {
                data.packages.forEach((pkg, index) => {
                    container.append(`
                        <div class="row g-2 mb-2 package-row">
                            <div class="col-6">
                                <input type="text" name="packages[${index}][variation_name]" class="form-control" value="${pkg.variation_name}" required>
                            </div>
                            <div class="col-5">
                                <input type="number" step="0.01" name="packages[${index}][additional_price]" class="form-control" value="${pkg.additional_price}" required>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button class="btn btn-outline-danger btn-sm" type="button" onclick="removePackageInput(this)">&times;</button>
                            </div>
                        </div>
                    `);
                    editPackageCounter = index + 1;
                });
            }
            
            editModal.show();
        }).fail(function(xhr) {
             Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch product data.', 'error');
        });
    });

    // --- Single Delete Button Handler ---
    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const deleteUrl = routes.delete(id); 
        Swal.fire({
            title: 'Delete this product?',
            text: "This will also delete its packages! You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-form').attr('action', deleteUrl).submit();
            }
        });
    });

    // --- Drag & Drop Reorder ---
    const sortableList = document.getElementById('sortableList');
    if (sortableList) {
        let sortable = Sortable.create(sortableList, { handle: '.sortable-handle', animation: 150 });
        $('#saveOrderBtn').on('click', function() {
            const $btn = $(this); $btn.prop('disabled', true).html('Saving...');
            $.ajax({
                url: routes.updateOrder, type: 'POST', 
                data: { itemIds: sortable.toArray(), _token: routes.token },
                success: res => { 
                    Swal.fire('Success!', res.message, 'success'); 
                    setTimeout(() => window.location.reload(), 1000); 
                },
                error: () => Swal.fire('Error!', 'Could not save the new order.', 'error'),
                complete: () => $btn.prop('disabled', false).html('<i data-feather="save" class="me-1" style="width:18px;"></i> Save Order')
            });
        });
    }

    // --- Initial Load & Modal Error Handling ---
    $(document).ready(function() {
        initSummernote('.summernote');
        if ($('#tableBody').length) { fetchData(); }

        @if (session('error_modal') === 'addModal' && $errors->any())
            addModal.show();
        @endif

        @if (session('error_modal_id') && $errors->update->any())
            var failedId = {{ session('error_modal_id') }};
            $('#editForm').attr('action', routes.update(failedId));
            
            // Repopulate dynamic packages on validation fail
            const container = $('#packages-container-edit');
            container.html(''); // Clear
            let editPackageCounter = 0;
            @if(old('packages'))
                @foreach(old('packages') as $i => $pkg)
                    container.append(`
                        <div class="row g-2 mb-2 package-row">
                            <div class="col-6">
                                <input type="text" name="packages[{{ $i }}][variation_name]" class="form-control" value="{{ $pkg['variation_name'] }}" required>
                            </div>
                            <div class="col-5">
                                <input type="number" step="0.01" name="packages[{{ $i }}][additional_price]" class="form-control" value="{{ $pkg['additional_price'] }}" required>
                            </div>
                            <div class="col-1 d-flex align-items-center">
                                <button class="btn btn-outline-danger btn-sm" type="button" onclick="removePackageInput(this)">&times;</button>
                            </div>
                        </div>
                    `);
                    editPackageCounter = {{ $i }} + 1;
                @endforeach
            @endif

            editModal.show();
        @endif
    });
</script>