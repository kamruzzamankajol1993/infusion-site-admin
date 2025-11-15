<script>
    $(document).ready(function() {
        // --- Image Preview Logic ---
        $("#image").change(function() {
            const input = this;
            const preview = $('#imagePreview');
            const placeholder = $('.placeholder-text');
            const originalSrc = '{{ $entry->image ? asset($entry->image) . "?v=" . time() : "" }}';

            if (input.files && input.files[0]) {
                const fileSize = input.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    Swal.fire('File Too Large', 'Image size should not exceed 2MB.', 'warning');
                    $(this).val(''); // Clear the input
                    
                    if(originalSrc) {
                        preview.attr('src', originalSrc).show();
                        placeholder.hide();
                    } else {
                        preview.hide();
                        placeholder.show();
                    }
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show();
                    placeholder.hide();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                if(originalSrc) {
                    preview.attr('src', originalSrc).show();
                    placeholder.hide();
                } else {
                    preview.hide();
                    placeholder.show();
                }
            }
        });

        // --- Form Submission Validation Trigger ---
        $('form#imageForm').submit(function(e) {
            let $form = $(this);
            if ($form[0].checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                $form.find(':invalid').first().focus();
            }
            $form.addClass('was-validated');
        });
    });
</script>