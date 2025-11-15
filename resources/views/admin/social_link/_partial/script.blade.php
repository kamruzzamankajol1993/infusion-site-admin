{{-- resources/views/admin/social_link/_partial/script.blade.php --}}
<script>
    // Use unique variables
    var currentPageSL = 1, searchTermSL = '', sortColumnSL = 'title', sortDirectionSL = 'asc';
    var editModalSL = new bootstrap.Modal(document.getElementById('editSocialLinkModal')); // Unique modal instance

    // --- Define Routes ---
    var routesSL = { // Unique prefix
        fetch: "{{ route('ajax.socialLink.data') }}",
        show: id => `{{ route('socialLink.show', ':id') }}`.replace(':id', id), // Fetches data for edit modal
        update: id => `{{ route('socialLink.update', ':id') }}`.replace(':id', id), // Uses PUT
        delete: id => `{{ route('socialLink.destroy', ':id') }}`.replace(':id', id), // Uses DELETE
        token: "{{ csrf_token() }}"
    };

    // --- Platform Name to Bootstrap Icon Mapping ---
    const platformIcons = {
        'Facebook': 'bi bi-facebook', 'Twitter': 'bi bi-twitter-x', 'Instagram': 'bi bi-instagram',
        'LinkedIn': 'bi bi-linkedin', 'YouTube': 'bi bi-youtube', 'TikTok': 'bi bi-tiktok',
        'Pinterest': 'bi bi-pinterest', 'Snapchat': 'bi bi-snapchat', 'Reddit': 'bi bi-reddit',
        'WhatsApp': 'bi bi-whatsapp', 'Telegram': 'bi bi-telegram', 'Vimeo': 'bi bi-vimeo',
        'GitHub': 'bi bi-github', 'Stack Overflow': 'bi bi-stack-overflow', 'Flickr': 'bi bi-flickr',
        'Tumblr': 'bi bi-tumblr', 'Discord': 'bi bi-discord', 'default': 'bi bi-link-45deg'
    };

    // --- Fetch and Render Table Data ---
    function fetchSLData() { // Unique function name
        $('#tableBodySL').html('<tr><td colspan="5" class="text-center py-4"><span class="spinner-border spinner-border-sm"></span> Loading...</td></tr>'); // Colspan 5

        $.get(routesSL.fetch, {
            page: currentPageSL, search: searchTermSL, sort: sortColumnSL, direction: sortDirectionSL
        }, function (res) {
            let rows = ''; $('#tableBodySL').empty(); // Use unique ID

            if (!res.data || res.data.length === 0) {
                 rows = `<tr><td colspan="5" class="text-center text-muted py-4">No social links found.</td></tr>`; // Colspan 5
                 $('#tableBodySL').html(rows); $('#tableRowCountSL').text(`Showing 0 to 0 of 0 entries`); $('#paginationSL').empty();
                 return;
            }

            res.data.forEach((item, i) => {
                let iconClass = platformIcons[item.title] || platformIcons['default'];
                let iconHtml = `<i class="${iconClass}" style="font-size: 1.2rem;"></i>`; // Display icon
                let linkPreview = item.link ? (item.link.length > 60 ? item.link.substring(0, 60) + '...' : item.link) : 'N/A';
                
                let deleteUrl = routesSL.delete(item.id); // Get delete URL for the form

                let editBtnHtml = ''; let deleteBtnHtml = '';
                @if(Auth::user()->can('socialLinkUpdate'))
                // Edit button now just toggles the modal
                editBtnHtml = `<button class="btn btn-sm btn-info btn-edit-sl btn-custom-sm" data-id="${item.id}" title="Edit"><i class="fa fa-edit"></i></button> `; // Unique class
                @endif
                @if(Auth::user()->can('socialLinkDelete'))
                // Delete button is now a form
                deleteBtnHtml = `<form action="${deleteUrl}" method="POST" class="d-inline delete-form-sl" data-title="${item.title || 'this link'}">
                                    <input type="hidden" name="_token" value="${routesSL.token}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-sm btn-danger btn-custom-sm" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                 </form>`; // Unique class
                @endif

                rows += `<tr>
                    <td>${(res.current_page - 1) * (res.per_page || 10) + i + 1}</td>
                    <td class="text-center">${iconHtml}</td>
                    <td>${item.title || 'N/A'}</td>
                    <td><a href="${item.link}" target="_blank" title="${item.link}">${linkPreview}</a></td>
                    <td>${editBtnHtml}${deleteBtnHtml}</td>
                </tr>`;
            });
            $('#tableBodySL').html(rows);

            // Update Row Count & Pagination
             const startEntry = (res.current_page - 1) * (res.per_page || 10) + 1;
             const endEntry = startEntry + res.data.length - 1;
             $('#tableRowCountSL').text(`Showing ${startEntry} to ${endEntry} of ${res.total} entries`);
            renderSLPagination(res); // Unique function
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
             console.error("AJAX Error loading social links:", textStatus, errorThrown);
             $('#tableBodySL').html('<tr><td colspan="5" class="text-center text-danger py-4">Could not load data. Please try again.</td></tr>');
             $('#tableRowCountSL').text(`Showing 0 to 0 of 0 entries`); $('#paginationSL').empty();
        });
    }

    // --- Render Pagination ---
    function renderSLPagination(res) { // Unique function name
        let paginationHtml = '';
        if (res.last_page > 1) { /* ... Same pagination logic ... */
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-sl" href="#" data-page="1">&laquo;&laquo;</a></li>`; // Unique class
            paginationHtml += `<li class="page-item ${res.current_page === 1 ? 'disabled' : ''}"><a class="page-link page-link-sl" href="#" data-page="${res.current_page - 1}">&laquo;</a></li>`;
            const maxVisiblePages=5; let startPage = Math.max(1, res.current_page - Math.floor(maxVisiblePages / 2)); let endPage = Math.min(res.last_page, startPage + maxVisiblePages - 1); if(endPage === res.last_page){ startPage = Math.max(1, endPage - maxVisiblePages + 1);}
            for (let i = startPage; i <= endPage; i++) { paginationHtml += `<li class="page-item ${i === res.current_page ? 'active' : ''}"><a class="page-link page-link-sl" href="#" data-page="${i}">${i}</a></li>`; }
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-sl" href="#" data-page="${res.current_page + 1}">&raquo;</a></li>`;
            paginationHtml += `<li class="page-item ${res.current_page === res.last_page ? 'disabled' : ''}"><a class="page-link page-link-sl" href="#" data-page="${res.last_page}">&raquo;&raquo;</a></li>`;
        }
         $('#paginationSL').html(paginationHtml); // Use unique ID
    }

    // --- Debounced Search Input Handler ---
     let searchTimeoutSL;
    $('#searchInputSL').on('keyup', function () { // Use unique ID
        clearTimeout(searchTimeoutSL); searchTermSL = $(this).val(); // Use unique var
        searchTimeoutSL = setTimeout(function() { currentPageSL = 1; fetchSLData(); }, 300); // Use unique vars/funcs
    });

    // --- Column Sorting Handler ---
    $(document).on('click', '.sortableSL', function () { // Use unique class
        let col = $(this).data('column'); sortDirectionSL = (sortColumnSL === col && sortDirectionSL === 'asc') ? 'desc' : 'asc'; sortColumnSL = col;
        $('.sortableSL').removeClass('sorting_asc sorting_desc'); $(this).addClass(sortDirectionSL === 'asc' ? 'sorting_asc' : 'sorting_desc'); fetchSLData();
    });

    // --- Pagination Click Handler ---
    $('#paginationSL').on('click', '.page-link-sl', function (e) { // Use unique ID and class
        e.preventDefault(); const page = parseInt($(this).data('page'));
        const isDisabled = $(this).parent().hasClass('disabled'); const isActive = $(this).parent().hasClass('active');
        if (!isNaN(page) && !isDisabled && !isActive) { currentPageSL = page; fetchSLData(); }
    });

    // --- MODIFIED Edit Button Click Handler ---
    $(document).on('click', '.btn-edit-sl', function () { // Use unique class
        const id = $(this).data('id');
        const form = $('#editSocialLinkForm');

        // 1. Reset form and clear validation
        form[0].reset(); 
        form.find('.is-invalid').removeClass('is-invalid');
        form.find('.invalid-feedback').text('');

        // 2. Set the form's action URL dynamically
        form.attr('action', routesSL.update(id));

        // 3. Use AJAX (from 'show' route) to get data and populate the form
        $.get(routesSL.show(id), function (data) {
            $('#editTitle').val(data.title); // Set select value
            $('#editLink').val(data.link);   // Set input value
            editModalSL.show(); // Show modal
        }).fail(function(xhr) { Swal.fire('Error!', xhr.responseJSON?.error || 'Could not fetch link data.', 'error'); });
    });

    // --- Edit Form Submit Handler (REMOVED) ---
    // The form now submits normally, so no AJAX handler is needed.

    // --- NEW: SweetAlert Form Submission Handler (for Delete) ---
    $('#tableBodySL').on('submit', '.delete-form-sl', function (e) {
        e.preventDefault(); // Stop the form from submitting immediately
        
        const form = this; // Get the form element
        const title = $(form).data('title'); // Get the title from data attribute
        
        Swal.fire({
            title: `Delete this social link?`,
            text: `You are about to delete the link for '${title}'. This cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the original form
                form.submit();
            }
        });
    });
    
    // --- OLD AJAX DELETE HANDLER (REMOVED) ---
    // --- 'checkAndReloadSLData' FUNCTION (REMOVED) ---

    // --- Initial Data Load ---
    fetchSLData(); // Use unique function name

</script>