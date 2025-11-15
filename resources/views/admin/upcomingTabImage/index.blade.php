@extends('admin.master.master')

@section('title')
Upcoming Tab Image | {{ $ins_name }}
@endsection

@section('css')
<style>
    .image-preview-box {
        position: relative;
        width: 100%;
        /* Using padding-top to create aspect ratio box (2761 / 2193 * 100%) */
        padding-top: 125.9%; 
        border: 2px dashed #ced4da;
        border-radius: .375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        color: #6c757d;
        overflow: hidden;
    }
    .image-preview-box img,
    .image-preview-box .placeholder-text {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .image-preview-box .placeholder-text {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        text-align: center;
        padding: 1rem;
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active" aria-current="page">Upcoming Tab Image</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Form Column --}}
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Manage Image</h5>
                </div>
                <div class="card-body">
                    @include('flash_message')
                    
                    <form action="{{ route('upcomingTabImage.storeOrUpdate') }}" method="POST" enctype="multipart/form-data" novalidate id="imageForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*" required>
                            <small class="form-text text-muted">
                                Required Size: <strong>2193px (Width) x 2761px (Height)</strong>. Max: 2MB.
                            </small>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="save" class="me-1" style="width:16px;"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        {{-- Preview Column --}}
        <div class="col-md-5 col-lg-6">
            <h6 class="text-muted">Current Image Preview</h6>
            <div class="image-preview-box shadow-sm">
                @if($imageRecord && $imageRecord->image)
                    <img id="imagePreview" src="{{ asset($imageRecord->image) }}?v={{ time() }}" alt="Current Image">
                    <span class="placeholder-text" style="display:none;">
                        Image Preview<br>(2193 x 2761)
                    </span>
                @else
                    <img id="imagePreview" src="#" alt="Image Preview" style="display:none;">
                    <span class="placeholder-text">
                        No image uploaded.<br>Preview will appear here.<br>(Recommended: 2193 x 2761)
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // --- Image Preview Logic ---
        $("#image").change(function() {
            const input = this;
            const preview = $('#imagePreview');
            const placeholder = $('.placeholder-text');
            // Get the original src (if it exists) to revert to if a file is deselected
            const originalSrc = '{{ $imageRecord && $imageRecord->image ? asset($imageRecord->image) . "?v=" . time() : "" }}';

            if (input.files && input.files[0]) {
                // Client-side file size check
                const fileSize = input.files[0].size / 1024 / 1024; // in MB
                if (fileSize > 2) {
                    Swal.fire('File Too Large', 'Image size should not exceed 2MB.', 'warning');
                    $(this).val(''); // Clear the input
                    
                    // Revert preview
                    if(originalSrc) {
                        preview.attr('src', originalSrc).show();
                        placeholder.hide();
                    } else {
                        preview.hide();
                        placeholder.show();
                    }
                    return;
                }

                // Read and display the new file
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.attr('src', e.target.result).show();
                    placeholder.hide();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                // If user deselects file, show the original image or placeholder
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
@endsection