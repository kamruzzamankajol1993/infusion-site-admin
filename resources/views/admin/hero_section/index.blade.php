@extends('admin.master.master')

@section('title', 'Manage Hero Section')

@section('css')
<style>
    /* General uploader styles */
    .image-uploader {
        position: relative;
        border: 2px dashed #dee2e6;
        border-radius: 0.5rem;
        background-color: #f8f9fa;
        transition: border-color 0.3s;
        overflow: hidden;
    }
    .image-uploader:hover {
        border-color: #0d6efd;
    }
    .image-uploader input[type="file"] {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0; cursor: pointer; z-index: 10;
    }
    .upload-placeholder {
        display: flex; flex-direction: column; justify-content: center;
        align-items: center; width: 100%; height: 100%;
        color: #6c757d; text-align: center; padding: 1.5rem;
    }
    .upload-placeholder i { font-size: 3rem; margin-bottom: 0.5rem; }
    .image-preview-wrapper {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        display: none;
    }
    .image-preview { width: 100%; height: 100%; object-fit: cover; }
    .image-uploader.has-image .upload-placeholder { display: none; }
    .image-uploader.has-image .image-preview-wrapper { display: block; }
    .right-uploader { min-height: 222px; }

    /* New styles for multiple image management */
    .multi-image-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        background-color: #f8f9fa;
        min-height: 200px;
    }
    .image-item {
        position: relative;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid #dee2e6;
    }
    .image-item img {
        width: 100%;
        height: auto;
        aspect-ratio: 16 / 9;
        object-fit: cover;
        display: block;
    }
    .image-item .delete-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background-color: rgba(220, 53, 69, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .image-item:hover .delete-btn {
        opacity: 1;
    }
    .add-image-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px dashed #dee2e6;
        border-radius: 0.5rem;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        padding: 1rem;
    }
    .add-image-btn:hover {
        border-color: #0d6efd;
        color: #0d6efd;
    }
    #new_left_images_input {
        display: none;
    }
</style>
@endsection

@section('body')
<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 my-4">
            <h2 class="mb-0">Manage Hero Section</h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('admin.hero.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-7 mb-4 mb-lg-0">
                            <h5 class="mb-3">Slide Image(s)</h5>
                            <p class="text-muted small mb-2">Manage your slide images below. You can delete existing images or add new ones (one by one or multiple at once).</p>
                            
                            <div class="multi-image-container" id="left_images_container">
                                @if($heroSection && $heroSection->left_image)
                                    @foreach($heroSection->left_image as $image)
                                        <div class="image-item">
                                            <img src="{{ asset('public/uploads/' . $image) }}" alt="Slide Image">
                                            <button type="button" class="delete-btn" title="Delete Image">
                                                <i data-feather="x" style="width:16px; height:16px;"></i>
                                            </button>
                                            <input type="hidden" name="existing_left_images[]" value="{{ $image }}">
                                        </div>
                                    @endforeach
                                @endif

                                <label for="new_left_images_input" class="add-image-btn">
                                    <i data-feather="plus" style="width:36px; height:36px;"></i>
                                    <span>Add New Images</span>
                                </label>
                                <input type="file" name="new_left_images[]" id="new_left_images_input" multiple>
                            </div>

                        </div>

                        <div class="col-lg-5">
                            <h5 class="mb-3">Side Images</h5>
                             <small class="text-muted">Single image only. 465px &times; 222px.</small>
                            {{-- This section for single images remains the same --}}
                            <div class="image-uploader right-uploader mb-3" id="top_right_uploader">
                                <input type="file" name="top_right_image" class="image-input" data-preview="top_right_image_preview">
                                <div class="upload-placeholder">
                                    <i data-feather="image"></i>
                                    <p class="mb-0 fw-bold">Click to upload image</p>
                                   
                                </div>
                                <div class="image-preview-wrapper">
                                    <img class="image-preview" id="top_right_image_preview" src="">
                                </div>
                            </div>
                            <div class="image-uploader right-uploader" id="bottom_right_uploader">
                                <input type="file" name="bottom_right_image" class="image-input" data-preview="bottom_right_image_preview">
                                <div class="upload-placeholder">
                                    <i data-feather="image"></i>
                                    <p class="mb-0 fw-bold">Click to upload image</p>
     
                                </div>
                                <div class="image-preview-wrapper">
                                    <img class="image-preview" id="bottom_right_image_preview" src="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary btn-lg"><i data-feather="save" class="me-2" style="width:18px;"></i>Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    feather.replace();

    const leftImagesContainer = document.getElementById('left_images_container');
    const newImagesInput = document.getElementById('new_left_images_input');
    const addImageButton = document.querySelector('.add-image-btn');

    // === Logic for deleting existing images ===
    leftImagesContainer.addEventListener('click', function (event) {
        const deleteButton = event.target.closest('.delete-btn');
        if (deleteButton) {
            const imageItem = deleteButton.closest('.image-item');
            if (imageItem) {
                imageItem.remove();
            }
        }
    });

    // === Logic for adding and previewing new images ===
    newImagesInput.addEventListener('change', function (event) {
        const files = event.target.files;
        if (!files) return;

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Create the preview element
                const imageItem = document.createElement('div');
                imageItem.className = 'image-item is-new'; // 'is-new' class to identify temporary previews

                const img = document.createElement('img');
                img.src = e.target.result;

                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.className = 'delete-btn';
                deleteBtn.title = 'Remove New Image';
                deleteBtn.innerHTML = '<i data-feather="x" style="width:16px; height:16px;"></i>';

                imageItem.appendChild(img);
                imageItem.appendChild(deleteBtn);
                
                // Insert the new preview before the "Add" button
                leftImagesContainer.insertBefore(imageItem, addImageButton);
                feather.replace();
            }
            reader.readAsDataURL(file);
        });

        // The files are held by the input. We just created previews.
        // NOTE: The 'delete' on new previews is visual only. The file remains in the input's FileList.
        // For a better UX, clicking delete on a new preview would require more complex JS to manage the FileList,
        // but for now, re-selecting files will clear the old selection.
    });


    // === Logic for single image uploaders (unchanged) ===
    function setSingleUploaderState(uploader, imageUrl) {
        if (imageUrl) {
            const previewImg = uploader.querySelector('.image-preview');
            previewImg.src = imageUrl;
            uploader.classList.add('has-image');
        }
    }
    setSingleUploaderState(document.getElementById('top_right_uploader'), '{{ $heroSection && $heroSection->top_right_image ? asset('public/uploads/' . $heroSection->top_right_image) : '' }}');
    setSingleUploaderState(document.getElementById('bottom_right_uploader'), '{{ $heroSection && $heroSection->bottom_right_image ? asset('public/uploads/' . $heroSection->bottom_right_image) : '' }}');

    document.querySelectorAll('.image-input').forEach(input => {
        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            const previewId = this.dataset.preview;
            const previewImg = document.getElementById(previewId);
            const uploader = this.closest('.image-uploader');

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                uploader.classList.add('has-image');
            }
            reader.readAsDataURL(file);
        });
    });

});
</script>
@endsection