{{-- resources/views/admin/client/_partial/addModal.blade.php --}}
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="addClientModalLabel">Add New Client</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addClientForm" method="post" action="{{ route('client.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="addName" class="form-label">Client Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="addName" class="form-control" placeholder="Enter client name" required>
                    @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
                
                {{-- New Image Shape Field --}}
                <div class="mb-3">
                    <label for="addImageShape" class="form-label">Image Shape <span class="text-danger">*</span></label>
                    <select name="image_shape" id="addImageShape" class="form-select" required>
                        <option value="" selected disabled>Select a shape</option>
                        <option value="square">Square (84x80 px)</option>
                        <option value="rectangular">Rectangular (142x72 px)</option>
                    </select>
                    @error('image_shape') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="addLogo" class="form-label">Client Logo <span class="text-danger">*</span></label>
                    <input type="file" name="logo" id="addLogo" class="form-control" accept="image/*" required>
                    {{-- Dynamic Help Text --}}
                    <small id="addLogoHelpText" class="form-text text-muted">Please select a shape first. Max: 512KB</small>
                    {{-- Basic Preview (styles updated) --}}
                    <img id="addLogoPreview" src="#" alt="Logo Preview" class="img-thumbnail mt-2" style="display:none; max-height: 90px; max-width: 150px; border: 1px solid #ddd; padding: 4px;">
                     @error('logo') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Client</button>
            </div>
        </form>
      </div>
    </div>
  </div>

{{-- Script specifically for add modal preview --}}
<script>
    // Logo file input change
    document.getElementById('addLogo').addEventListener('change', function(event) {
        const preview = document.getElementById('addLogoPreview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onloadend = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    });

    // --- New: Shape dropdown change ---
    document.getElementById('addImageShape').addEventListener('change', function(event) {
        const shape = event.target.value;
        const helpText = document.getElementById('addLogoHelpText');
        const preview = document.getElementById('addLogoPreview');
        
        if (shape === 'square') {
            helpText.textContent = 'Size: 84px (Width) x 80px (Height), Max: 512KB';
            preview.style.maxWidth = '84px';
            preview.style.maxHeight = '80px';
        } else if (shape === 'rectangular') {
            helpText.textContent = 'Size: 142px (Width) x 72px (Height), Max: 512KB';
            preview.style.maxWidth = '142px';
            preview.style.maxHeight = '72px';
        } else {
            helpText.textContent = 'Please select a shape first. Max: 512KB';
            preview.style.maxWidth = '150px'; // Reset
            preview.style.maxHeight = '90px'; // Reset
        }
    });

    // Clear preview/form on hide
    var addModalElement = document.getElementById('addClientModal');
    addModalElement.addEventListener('hidden.bs.modal', function () {
        document.getElementById('addClientForm').reset(); // Reset form
        document.getElementById('addLogoPreview').src = '#';
        document.getElementById('addLogoPreview').style.display = 'none';
        // --- New: Reset help text and preview style ---
        document.getElementById('addLogoHelpText').textContent = 'Please select a shape first. Max: 512KB';
        const preview = document.getElementById('addLogoPreview');
        preview.style.maxWidth = '150px';
        preview.style.maxHeight = '90px';
    });
</script>