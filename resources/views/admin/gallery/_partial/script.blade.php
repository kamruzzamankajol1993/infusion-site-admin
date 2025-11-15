{{-- resources/views/admin/gallery/_partial/script.blade.php --}}
<script>
    // Use unique variables
    var currentPageG = 1, searchTermG = '', sortColumnG = 'id', sortDirectionG = 'desc';

    // --- Define Routes ---
    var routesG = { // Unique prefix
        fetch: "{{ route('ajax.gallery.data') }}",
        edit: id => `{{ route('gallery.edit', ':id') }}`.replace(':id', id),
        show: id => `{{ route('gallery.show', ':id') }}`.replace(':id', id), // Link to show page
        delete: id => `{{ route('gallery.destroy', ':id') }}`.replace(':id', id),
        token: "{{ csrf_token() }}"
    };

    // --- Fetch and Render Table Data ---
    function fetchGData() { // Unique function name
        // Optional: Show loading state
        // $('#tableBodyG').html('<tr><td colspan="5"><div class="spinner-border spinner-border-sm"></div></td></tr>');

        $.get(routesG.fetch, {
            page: currentPageG, search: searchTermG, sort: sortColumnG, direction: sortDirectionG
        }, function (res) {
            let rows = ''; $('#tableBodyG').empty(); // Use unique ID

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="5" class="text-center text-muted py-4">No gallery items found.</td></tr>`; // Colspan 5
                 $('#tableBodyG').html(rows); $('#tableRowCountG').text(`Showing 0 to 0 of 0 entries`); $('#paginationG').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let itemType = item.type ? item.type.charAt(0).toUpperCase() + item.type.slice(1) : 'N/A';
                
                let descriptionOrLink = '';
                if (item.type === 'video' && item.youtube_link) {
                    descriptionOrLink = item.short_description ? `<strong>${item.short_description}</strong><br>` : '';
                    descriptionOrLink += `<a href="${item.youtube_link}" target="_blank" rel="noopener noreferrer" class="small">${item.youtube_link}</a>`;
                } else if (item.type === 'image') {
                    descriptionOrLink = item.short_description || '<span class="text-muted small">No description</span>';
                } else {
                    descriptionOrLink = item.short_description || '<span class="text-muted small">No media link or description</span>';
                }

                let thumbnailHtml = '';
                const placeholderImg = `{{ asset('public/admin/assets/img/placeholder.png') }}`; // Adjust placeholder

                if (item.type === 'image' && item.image_file) {
                    thumbnailHtml = `<img src="${item.image_url}" alt="Gallery Image" style="max-height: 60px; max-width: 90px; object-fit: cover; border: 1px solid #eee;">`;
                } else if (item.type === 'video' && item.video_thumbnail_url) {
                    thumbnailHtml = `<img src="${item.video_thumbnail_url}" alt="Video Thumbnail" style="max-height: 60px; max-width: 90px; object-fit: cover; border: 1px solid #eee;">`;
                } else {
                    thumbnailHtml = `<img src="${placeholderImg}" alt="Placeholder" style="max-height: 60px; max-width: 90px; object-fit: contain; opacity: 0.5;">`;
                }

                let editUrl = routesG.edit(item.id);
                
                // --- UPDATED: Get delete URL for the form ---
                let deleteUrl = routesG.delete(item.id); 

                let canUpdate = {{ Auth::user()->can('galleryUpdate') ? 'true' : 'false' }};
                let canDelete = {{ Auth::user()->can('galleryDelete') ? 'true' : 'false' }};
                let canView = {{ Auth::user()->can('galleryView') ? 'true' : 'false' }};

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td>${thumbnailHtml}</td>
                    <td><span class="badge ${item.type === 'image' ? 'bg-info-soft text-info' : 'bg-danger-soft text-danger'}">${itemType}</span></td>
                    <td class="small">${descriptionOrLink}</td>
                    <td>`;
                
                 if(canView) {
                     let showUrl = routesG.show(item.id); 
                     let viewTitle = 'View Details';
                     rows += `<a href="${showUrl}" class="btn btn-sm btn-primary btn-custom-sm" title="${viewTitle}"><i class="fa fa-eye"></i></a> `;
                 }

                 // Edit Button
                 if(canUpdate) rows += `<a href="${editUrl}" class="btn btn-sm btn-info btn-custom-sm" title="Edit"><i class="fa fa-edit"></i></a> `;
                 
                 // --- UPDATED: Delete button is now a form ---
                 if(canDelete) {
                    rows += `<form action="${deleteUrl}" method="POST" class="d-inline-block form-delete-g" style="margin-bottom: 0;">
                                <input type="hidden" name="_token" value="${routesG.token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-sm btn-danger btn-custom-sm" title="Delete"><i class="fa fa-trash"></i></button>
                            </form>`; // Unique class form-delete-g
                 }
                 // --- End Update ---

                 rows += `</td>
                </tr>`;
            });
            $('#tableBodyG').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountG').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderGPagination(res); // Unique function
        });
    }

    // --- Render Pagination ---
    function renderGPagination(res) { // Unique function name
        let paginationHtml = '';
        if (res.last_page > 1) { /* ... Same pagination logic ... */
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-g" href="#" data-page="1">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-g" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const startPage = Math.max(1, res.current_page - 2);
            const endPage = Math.min(res.last_page, res.current_page + 2);
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-g" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-g" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-g" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationG').html(paginationHtml); // Use unique ID
    }

    // --- Debounced Search Input Handler ---
     let searchTimeoutG;
    $('#searchInputG').on('keyup', function () { // Use unique ID
        clearTimeout(searchTimeoutG); searchTermG = $(this).val(); // Use unique var
        searchTimeoutG = setTimeout(function() { currentPageG = 1; fetchGData(); }, 300); // Use unique vars/funcs
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableG', function () { // Use unique class if needed
        let col = $(this).data('column'); sortDirectionG = (sortColumnG === col && sortDirectionG === 'asc') ? 'desc' : 'asc'; sortColumnG = col;
        $('.sortableG').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirectionG === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchGData();
    });

    // --- Pagination Click Handler ---
    $('#paginationG').on('click', '.page-link-g', function (e) { // Use unique ID and class
        e.preventDefault(); const page = parseInt($(this).data('page'));
        const isDisabled = $(this).parent().hasClass('disabled'); const isActive = $(this).parent().hasClass('active');
        if (!isNaN(page) && !isDisabled && !isActive) { currentPageG = page; fetchGData(); }
    });

    // --- UPDATED: Delete Form Handler (replaces btn-delete-g click handler) ---
    $('#tableBodyG').on('submit', '.form-delete-g', function (e) { // Use unique ID and class, on 'submit'
        e.preventDefault(); // Prevent the form from submitting immediately
        
        const form = this; // Get the form element
        const button = $(form).find('button[type="submit"]');

        Swal.fire({
            title: 'Delete this gallery item?', 
            text: "The associated image file (if any) will also be deleted. This cannot be undone!", 
            icon: 'warning',
            showCancelButton: true, 
            confirmButtonColor: '#d33', 
            cancelButtonColor: '#3085d6', 
            confirmButtonText: 'Yes, delete it!',
            focusCancel: true // Focus cancel button by default
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state on button
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
                
                // Submit the form
                form.submit();
            }
        });
    });

    // --- REMOVED checkAndReloadGData() function ---

    // --- Initial Data Load ---
    fetchGData(); // Use unique function name

</script>