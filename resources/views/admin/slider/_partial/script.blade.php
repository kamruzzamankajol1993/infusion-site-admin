{{-- resources/views/admin/slider/_partial/script.blade.php --}}
<script>
    // ===================================================================
    // SCRIPT FOR "VIEW LIST" TAB (PAGINATED TABLE)
    // ===================================================================

    // Use unique variables
    var currentPageS = 1, searchTermS = '', sortColumnS = 'display_order', sortDirectionS = 'asc'; // Default sort

    // --- Define Routes ---
    var routesS = { // Unique prefix
        fetch: "{{ route('ajax.slider.data') }}",
        edit: id => `{{ route('slider.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('slider.show', ':id') }}`.replace(':id', id),
        delete: id => `{{ route('slider.destroy', ':id') }}`.replace(':id', id),
        
        // --- 1. ADD NEW ROUTES for reorder tab ---
        fetchAll: "{{ route('slider.allForReorder') }}",
        updateOrder: "{{ route('slider.updateOrder') }}",
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchSData() { // Unique function name
        // 2. Colspan is back to 5
        $('#tableBodyS').html('<tr><td colspan="5" class="text-center py-4"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>'); // Colspan 5

        $.get(routesS.fetch, {
            page: currentPageS, search: searchTermS, sort: sortColumnS, direction: sortDirectionS
        }, function (res) {
            let rows = ''; $('#tableBodyS').empty(); // Use unique ID

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="5" class="text-center text-muted py-4">No sliders found.</td></tr>`; // Colspan 5
                 $('#tableBodyS').html(rows); $('#tableRowCountS').text(`Showing 0 to 0 of 0 entries`); $('#paginationS').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let textPreview = item.subtitle || (item.short_description ? stripHtml(item.short_description).substring(0, 70) + '...' : '<span class="text-muted small">No text</span>');
                let imageUrl = item.image 
                    ? `{{ asset('/') }}${item.image}` 
                    : '{{ asset('/') }}public/No_Image_Available.jpg'; // Use accessor
                let imageHtml = `<img src="${imageUrl}" alt="${item.title || 'Slider Image'}" style="max-height: 50px; max-width: 100px; object-fit: contain; border: 1px solid #eee;">`;

                let editUrl = routesS.edit(item.id);
                let showUrl = routesS.show(item.id);
                let deleteId = item.id;

                let canUpdate = {{ Auth::user()->can('sliderUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('sliderDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('sliderView') ? 'true' : 'false' }};

                // 3. Reverted to original row (no data-id, no drag handle)
                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${imageHtml}</td>
                    <td>${item.title || '<span class="text-muted small">N/A</span>'}</td>
                    <td class="small">${textPreview}</td>
                    <td>`;
                 if(canView) rows += `<a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm" title="View Details"><i class="fa fa-eye"></i></a> `;
                 if(canUpdate) rows += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm" title="Edit"><i class="fa fa-edit"></i></a> `;
                 
                 // --- DELETE BUTTON (No change) ---
                 if(canDelete) {
                    let deleteUrl = routesS.delete(deleteId);
                    rows += `
                        <form action="${deleteUrl}" method="POST" class="d-inline" id="delete-form-s-${deleteId}">
                            <input type="hidden" name="_token" value="${routesS.token}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger btn-delete-s btn-custom-sm" data-id="${deleteId}" title="Delete">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    `;
                 }
                 
                 rows += `</td>
                </tr>`;
            });
            $('#tableBodyS').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountS').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderSPagination(res); // Unique function
        })
         .fail(function(jqXHR, textStatus, errorThrown) {
             console.error("AJAX Error loading sliders:", textStatus, errorThrown);
             $('#tableBodyS').html('<tr><td colspan="5" class="text-center text-danger py-4">Could not load data. Please try again.</td></tr>');
             $('#tableRowCountS').text(`Showing 0 to 0 of 0 entries`); $('#paginationS').empty();
         });
    }

    // Helper function to strip HTML tags
    function stripHtml(html) { let tmp = document.createElement("DIV"); tmp.innerHTML = html; return tmp.textContent || tmp.innerText || ""; }

    // --- Render Pagination ---
    function renderSPagination(res) { // Unique function name
        let paginationHtml = '';
        if (res.last_page > 1) { /* ... Same pagination logic as before ... */
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-s" href="#" data-page="1">&laquo;&laquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-s" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const maxVisiblePages=5; let startPage = Math.max(1, res.current_page - Math.floor(maxVisiblePages / 2)); let endPage = Math.min(res.last_page, startPage + maxVisiblePages - 1); if(endPage === res.last_page){ startPage = Math.max(1, endPage - maxVisiblePages + 1);}
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-s" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-s" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-s" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationS').html(paginationHtml); // Use unique ID
    }

    // --- Debounced Search Input Handler ---
     let searchTimeoutS;
    $('#searchInputS').on('keyup', function () { // Use unique ID
        clearTimeout(searchTimeoutS); searchTermS = $(this).val(); // Use unique var
        searchTimeoutS = setTimeout(function() { currentPageS = 1; fetchSData(); }, 300); // Use unique vars/funcs
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableS', function () { // Use unique class
        let col = $(this).data('column'); sortDirectionS = (sortColumnS === col && sortDirectionS === 'asc') ? 'desc' : 'asc'; sortColumnS = col;
        $('.sortableS').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirectionS === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchSData();
    });

    // --- Pagination Click Handler ---
    $('#paginationS').on('click', '.page-link-s', function (e) { // Use unique ID and class
        e.preventDefault(); const page = parseInt($(this).data('page'));
        const isDisabled = $(this).parent().hasClass('disabled'); const isActive = $(this).parent().hasClass('active');
        if (!isNaN(page) && !isDisabled && !isActive) { currentPageS = page; fetchSData(); }
    });

    // --- Single Delete Button Handler ---
    $('#tableBodyS').on('click', '.btn-delete-s', function () { // Use unique ID and class
        const id = $(this).data('id');
        
        Swal.fire({
            title: 'Delete this slider?', text: "The image file will also be deleted!", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#delete-form-s-${id}`).submit();
            }
        });
    });

    // --- Initial Data Load ---
    fetchSData(); // Load the table data on page load


    // ===================================================================
    // 4. SCRIPT FOR "REORDER LIST" TAB (NEW)
    // ===================================================================
    var reorderListLoaded = false; // Flag to prevent multiple AJAX loads

    // --- Tab Click Handler ---
    // Listen for the "Reorder" tab to be clicked
    $('#reorder-tab').on('click', function() {
        if (!reorderListLoaded) {
            loadReorderList();
            reorderListLoaded = true; // Set flag
        }
    });

    // --- Load the reorder list ---
    function loadReorderList() {
        // Show loading spinner (it's already in the HTML)
        
        // Fetch all sliders from the new route
        $.get(routesS.fetchAll, function(data) {
            $('#reorderSliderList').empty(); // Clear loading spinner
            
            if (!data || data.length === 0) {
                $('#reorderSliderList').html('<p class="text-muted text-center">No sliders found to reorder.</p>');
                return;
            }

            // Build the list HTML
            data.forEach(function(item) {
                let imageUrl = item.image 
                    ? `{{ asset('/') }}${item.image}` 
                    : '{{ asset('/') }}public/No_Image_Available.jpg';
                
                let itemHtml = `
                    <div class="list-group-item" data-id="${item.id}">
                        <span class="reorder-handle"><i class="fa fa-bars"></i></span>
                        <img src="${imageUrl}" alt="${item.title || 'Slider'}" class="reorder-img">
                        <span class="reorder-title">${item.title || '<span class="text-muted">Untitled Slider</span>'}</span>
                    </div>
                `;
                $('#reorderSliderList').append(itemHtml);
            });

            // Initialize Sortable
            makeReorderable();

        }).fail(function() {
            $('#reorderSliderList').html('<p class="text-danger text-center">Failed to load sliders. Please try again.</p>');
            reorderListLoaded = false; // Allow retry on fail
        });
    }

    // --- Initialize jQuery UI Sortable on the list ---
    function makeReorderable() {
        $('#reorderSliderList').sortable({
            handle: '.reorder-handle',          // Class of the drag handle
            animation: 150,                  
            placeholder: "ui-sortable-placeholder", // Class for the placeholder
            
            // Function to call when the dragging stops
            update: function(event, ui) {
                saveReorder(); // Call the function to save the new order
            }
        });
    }

    // --- Save the new order ---
    // --- Save the new order ---
    function saveReorder() {
        let order = [];
        // Loop through each item in the list and get its 'data-id'
        $('#reorderSliderList .list-group-item').each(function() {
            order.push($(this).data('id'));
        });

        // Show processing state
        $('#reorderSliderList').css('opacity', 0.5);

        $.post(routesS.updateOrder, {
            order: order,         // The array of IDs in the new order
            _token: routesS.token // CSRF token
        }, function(res) {
            // Success
            $('#reorderSliderList').css('opacity', 1);
            console.log('Slider order saved successfully!');
            
            // --- 1. ADD THIS SWEETALERT TOAST ---
            Swal.fire({
                icon: 'success',
                title: 'Order Updated!',
                timer: 2000, // Auto-close after 2 seconds
                showConfirmButton: false,
                toast: true, // Use a smaller toast notification
                position: 'top-end' // Position in top-right
            });
            // --- END OF ADDITION ---
            
            // Reload the main table data in the background
            // so the "Sl" numbers are correct if the user switches back
            fetchSData(); 

        }).fail(function() {
            // Error
            $('#reorderSliderList').css('opacity', 1);
            Swal.fire('Error', 'Could not save the new order. Please try again.', 'error');
            
            // Reload list to revert to last saved order
            reorderListLoaded = false;
            loadReorderList();
        });
    }

</script>