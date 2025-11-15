@extends('admin.master.master')

@section('title')
Permission Management | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Add a little style to the permission rows */
    .permission-row {
        transition: all 0.2s ease-in-out;
    }
    .permission-row:hover {
        background-color: #f8f9fa; /* Light hover for the row */
    }
</style>
@endsection

@section('body')

<div class="container-fluid px-4 py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">General</li>
            <li class="breadcrumb-item"><a href="{{ route('permissions.index') }}">Permissions</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Permission</li>
        </ol>
    </nav>


        <div class="row justify-content-center">
            <div class="col-lg-12 col-xl-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Update Permission Group</h5>
                    </div>
                    <div class="card-body">
                        <form id="form" method="post" action="{{ route('permissions.update', $pers) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label for="group_name" class="form-label">Group Name <span class="text-danger">*</span></label>
                                    <input type="text" id="group_name" name="group_name" value="{{ $pers }}" class="form-control" placeholder="e.g., Blog Management" required>
                                    <small class="text-muted">This groups all related permissions (e.g., blogView, blogCreate, blogEdit).</small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Permissions <span class="text-danger">*</span></label>
                                    <div id="permission-rows-container">
                                        
                                        @if(count($persEdit) > 0)
                                            @foreach($persEdit as $allPermissionList)
                                            <div class="input-group mb-2 permission-row">
                                                <input type="text" name="name[]" value="{{ $allPermissionList->name }}" placeholder="Permission Name (e.g., blogView)" class="form-control" required />
                                                <button type="button" class="btn btn-outline-danger remove-permission-row"><i class="fa fa-trash"></i></button>
                                            </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-2 permission-row">
                                                <input type="text" name="name[]" value="" placeholder="Permission Name (e.g., blogView)" class="form-control" required />
                                                <button type="button" class="btn btn-outline-danger remove-permission-row"><i class="fa fa-trash"></i></button>
                                            </div>
                                        @endif

                                    </div>
                                    
                                    <button type="button" id="add-permission-row" class="btn btn-outline-primary btn-sm mt-2">
                                        <i class="fa fa-plus me-1"></i> Add Permission
                                    </button>
                                </div>
                            </div>
                            
                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back to List
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update Permissions
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
   
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {

        // --- Add New Permission Row ---
        $("#add-permission-row").click(function () {
            var html = '<div class="input-group mb-2 permission-row">' +
                       '<input type="text" name="name[]" placeholder="Permission Name (e.g., blogDelete)" class="form-control" required />' +
                       '<button type="button" class="btn btn-outline-danger remove-permission-row"><i class="fa fa-trash"></i></button>' +
                       '</div>';
            
            // Append the new row
            $('#permission-rows-container').append(html);
        });

        // --- Remove Permission Row ---
        // We use event delegation on the container to handle clicks on
        // dynamically added buttons.
        $('#permission-rows-container').on('click', '.remove-permission-row', function () {
            // Check if it's the last row. Don't remove the last one, just clear it.
            if ($('#permission-rows-container .permission-row').length > 1) {
                $(this).closest('.permission-row').remove();
            } else {
                // If it's the last one, just clear the input
                $(this).closest('.permission-row').find('input[type="text"]').val('');
                // You might want to add a toastr notification here:
                // toastr.warning('At least one permission is required.');
            }
        });

    });
</script>
@endsection