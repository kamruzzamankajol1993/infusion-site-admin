@extends('admin.master.master')

@section('title')
System Settings - {{ $settings->ins_name ?? 'N/A' }}
@endsection


@section('css')
  <style>
        .image-upload-container {
            cursor: pointer;
            position: relative;
            background-color: #e9ecef;
            border: 2px dashed #ced4da;
        }
        .image-upload-container img {
            object-fit: contain;
            width: 100%;
            height: 100%;
        }
        .image-upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }
        .image-upload-container:hover .image-upload-overlay {
            opacity: 1;
        }
    </style>
@endsection


@section('body')
 <div class="container-fluid px-4 py-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">General</li>
                        <li class="breadcrumb-item active" aria-current="page">System Setting</li>
                    </ol>
                </nav>

                <div class="card shadow-sm">
                    <div class="card-body">
                        {{-- START: Add this code --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- END: Add this code --}}


    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <strong class="d-block">Please correct the following errors:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
                        <form action="{{ route('systemInformation.update', $settings->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                             
                          
                            <div class="row">
                                <div class="col-lg-7">
                                    <h5 class="mb-3">Branding</h5>
                                    <div class="mb-3">
                                        <label class="form-label">Site Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="ins_name" value="{{ $settings->ins_name }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4 mb-3">
                                            {{-- MODIFIED --}}
                                            <label class="form-label">Logo (316x316) <span class="text-danger">*</span></label>
                                            <label class="image-upload-container d-block" style="height: 100px;">
                                                <img id="logoPreview" src="{{ $settings->logo ? asset($settings->logo) : 'https://placehold.co/316x316/EFEFEF/AAAAAA&text=No+Image' }}" alt="logo">
                                                <input type="file" id="logoUpload" name="logo" class="d-none" accept="image/*">
                                                <div class="image-upload-overlay"><i class="bi bi-camera fs-3"></i></div>
                                            </label>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            {{-- MODIFIED --}}
                                            <label class="form-label">Icon (64x64) <span class="text-danger">*</span></label>
                                            <label class="image-upload-container d-block" style="height: 100px;">
                                                <img id="iconPreview" src="{{ $settings->icon ? asset($settings->icon) : 'https://placehold.co/316x316/EFEFEF/AAAAAA&text=No+Image' }}" alt="icon">
                                                <input type="file" id="iconUpload" name="icon" class="d-none" accept="image/*">
                                                 <div class="image-upload-overlay"><i class="bi bi-camera fs-3"></i></div>
                                            </label>
                                        </div>
                                        
                                        <div class="col-sm-4 mb-3">
                                            {{-- MODIFIED --}}
                                            <label class="form-label">Rectangular logo </label>
                                            <label class="image-upload-container d-block" style="height: 100px;">
                                                <img id="rectangularLogoPreview" src="{{ $settings->rectangular_logo ? asset($settings->rectangular_logo) : 'https://placehold.co/200x100/EFEFEF/AAAAAA&text=No+Image' }}" alt="rectangular logo">
                                                <input type="file" id="rectangularLogoUpload" name="rectangular_logo" class="d-none" accept="image/*">
                                                 <div class="image-upload-overlay"><i class="bi bi-camera fs-3"></i></div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5">
                                    <h5 class="mb-3">Contact Information</h5>
                                    <div class="mb-3">
                                        <label class="form-label">Address <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="address" value="{{ $settings->address }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Address Line 2</label>
                                        <input type="text" class="form-control" name="address_two" value="{{ $settings->address_two }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="phone" value="{{ $settings->phone }}">
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label">Phone 2</label>
                                            <input type="text" class="form-control" name="phone_two" value="{{ $settings->phone_two }}">
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ $settings->email }}">
                                        </div>
                                        <div class="col-sm-6 mb-3">
                                            <label class="form-label">Email 2</label>
                                            <input type="email" class="form-control" name="email_two" value="{{ $settings->email_two }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        {{-- MODIFIED --}}
                                        <label class="form-label">Short Description <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description" rows="3">{{ $settings->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    {{-- MODIFIED --}}
                                    <label class="form-label">Main URL (Admin) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="main_url" value="{{ $settings->main_url }}" placeholder="https://admin.example.com">
                                </div>
                                 <div class="col-md-6 mb-3">
                                    <label class="form-label">Frontend URL <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="front_url" value="{{ $settings->front_url }}" placeholder="https://example.com">
                                 </div>
                                <div class="col-md-6 mb-3">
                                    {{-- MODIFIED --}}
                                    <label class="form-label">Developed By <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="develop_by" value="{{ $settings->develop_by }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
@endsection


@section('script')
<script>
    function handleImageUpload(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            if (!input || !preview) return;

            input.addEventListener('change', function(e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        preview.src = event.target.result;
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });
        }
        
        handleImageUpload('logoUpload', 'logoPreview');
        handleImageUpload('iconUpload', 'iconPreview');
        handleImageUpload('rectangularLogoUpload', 'rectangularLogoPreview'); // Added listener for rectangular logo

</script>
@endsection