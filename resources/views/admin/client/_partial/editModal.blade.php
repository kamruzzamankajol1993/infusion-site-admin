{{-- resources/views/admin/client/_partial/editModal.blade.php --}}
<div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editClientModalLabel">Edit Client</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- MODIFIED: Set action to "" (will be set by JS) --}}
        <form id="editClientForm" method="POST" enctype="multipart/form-data" action="">
            @csrf
            @method('PUT') {{-- Method spoofing --}}
            <input type="hidden" id="editClientId">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editName" class="form-label">Client Name <span class="text-danger">*</span></label>
                    {{-- MODIFIED: Added @error class and old() value --}}
                    <input type="text" id="editName" name="name" class="form-control @error('name', 'update') is-invalid @enderror" value="{{ old('name') }}" required>
                    {{-- MODIFIED: Replaced AJAX error div with Blade @error directive --}}
                    @error('name', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- New Image Shape Field --}}
                <div class="mb-3">
                    <label for="editImageShape" class="form-label">Image Shape <span class="text-danger">*</span></label>
                     {{-- MODIFIED: Added @error class --}}
                    <select name="image_shape" id="editImageShape" class="form-select @error('image_shape', 'update') is-invalid @enderror" required>
                        <option value="" disabled>Select a shape</option>
                        {{-- MODIFIED: Added old() check for options --}}
                        <option value="square" {{ old('image_shape') == 'square' ? 'selected' : '' }}>Square (84x80 px)</option>
                        <option value="rectangular" {{ old('image_shape') == 'rectangular' ? 'selected' : '' }}>Rectangular (142x72 px)</option>
                    </select>
                    {{-- MODIFIED: Replaced AJAX error div with Blade @error directive --}}
                    @error('image_shape', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="editLogo" class="form-label">Client Logo</label>
                    {{-- MODIFIED: Added @error class --}}
                    <input type="file" id="editLogo" name="logo" class="form-control @error('logo', 'update') is-invalid @enderror" accept="image/*">
                    {{-- Dynamic Help Text --}}
                    <small id="editLogoHelpText" class="form-text text-muted">Size: Max: 512KB. Leave blank to keep current.</small>
                    {{-- Preview for current/new logo (styles updated) --}}
                    <div class="mt-2">
                        <img id="editLogoPreview" src="#" alt="Logo Preview" class="img-thumbnail" style="max-height: 90px; max-width: 150px; display: none; border: 1px solid #ddd; padding: 4px;">
                         <span id="editPreviewPlaceholder" class="text-muted" style="display:none;">No Logo</span>
                    </div>
                    {{-- MODIFIED: Replaced AJAX error div with Blade @error directive --}}
                    @error('logo', 'update')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" id="editSubmitBtn">Save Changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>

{{-- Script specifically for edit modal preview --}}
<script>
    // Logo file input change
    document.getElementById('editLogo').addEventListener('change', function(event) {
        // ... (existing logo change script remains the same) ...
        const preview = document.getElementById('editLogoPreview');
        const placeholder = document.getElementById('editPreviewPlaceholder');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
             placeholder.style.display = 'none';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
             // If deselected, try showing original if available (set by main script)
             const originalSrc = preview.dataset.originalSrc; // Use data attribute
            if (originalSrc) {
                 preview.src = originalSrc;
                 preview.style.display = 'block';
                 placeholder.style.display = 'none';
            } else {
                preview.src = '#';
                preview.style.display = 'none';
                 placeholder.style.display = 'block';
            }
        }
    });

    // --- New: Shape dropdown change ---
    document.getElementById('editImageShape').addEventListener('change', function(event) {
        const shape = event.target.value;
        const helpText = document.getElementById('editLogoHelpText');
        const preview = document.getElementById('editLogoPreview');
        
        if (shape === 'square') {
            helpText.textContent = 'Size: 84x80 px, Max: 512KB. Leave blank to keep current.';
            preview.style.maxWidth = '84px';
            preview.style.maxHeight = '80px';
        } else if (shape === 'rectangular') {
            helpText.textContent = 'Size: 142x72 px, Max: 512KB. Leave blank to keep current.';
            preview.style.maxWidth = '142px';
            preview.style.maxHeight = '72px';
        } else {
            helpText.textContent = 'Size: Max: 512KB. Leave blank to keep current.';
            preview.style.maxWidth = '150px'; // Reset
            preview.style.maxHeight = '90px'; // Reset
        }
    });

     // Clear preview/form on hide
    var editModalElement = document.getElementById('editClientModal');
    editModalElement.addEventListener('hidden.bs.modal', function () {
         document.getElementById('editClientForm').reset();
         const preview = document.getElementById('editLogoPreview');
         preview.src = '#';
         preview.style.display = 'none';
         preview.style.maxWidth = '150px'; // Reset
         preview.style.maxHeight = '90px'; // Reset
         document.getElementById('editPreviewPlaceholder').style.display = 'block';
         // --- New: Reset help text ---
         document.getElementById('editLogoHelpText').textContent = 'Size: Max: 512KB. Leave blank to keep current.';
         
         // --- MODIFIED: Clear validation errors ---
         $('#editClientForm .is-invalid').removeClass('is-invalid');
         // Remove Blade's dynamically added error blocks
         $('#editClientForm .invalid-feedback.d-block').remove(); 
         
         $('#editSubmitBtn').prop('disabled', false).text('Save Changes');
    });
</script>