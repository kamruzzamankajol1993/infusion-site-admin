@extends('admin.master.master')

@section('title')
Permission Management | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Optional: Style for delete button */
    .btn-delete-selected {
        margin-left: 10px; /* Space between search and delete button */
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">General</li>
                <li class="breadcrumb-item active" aria-current="page">Permission</li>
            </ol>
        </nav>

        {{-- Add Button --}}
        <div>
            @if (Auth::user()->can('permissionAdd'))
            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Permission
            </button>
            @endif
        </div>
    </div>

    <div class="card shadow-sm"> {{-- Added shadow --}}
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Permission List</h5>

            <div class="d-flex flex-wrap gap-2"> {{-- Container for search and delete --}}
                {{-- Search Form --}}
                <form class="d-flex" role="search" onsubmit="return false;">
                    <input class="form-control" id="searchInput" type="search" placeholder="Search group name..." aria-label="Search">
                </form>

                {{-- Delete Selected Button (Initially Hidden) --}}
                @if (Auth::user()->can('permissionDelete'))
                    <button class="btn btn-danger btn-delete-selected" id="deleteSelectedBtn" style="display: none;">
                        <i class="fa fa-trash me-1"></i> Delete Selected
                    </button>
                @endif
            </div>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered"> {{-- Added table-bordered --}}
                    <thead class="table-light"> {{-- Added class --}}
                        <tr>
                            {{-- Check All Checkbox --}}
                            <th style="width: 3%;">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                            </th>
                            <th scope="col" style="width: 5%;">Sl</th>
                            <th scope="col" style="width: 30%;" class="sortable" data-column="group_name">Group Name</th> {{-- Made sortable --}}
                            <th scope="col" style="width: 47%;">Permission Names</th>
                            <th scope="col" style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        {{-- AJAX data here --}}
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center"> {{-- Added flex-wrap --}}
            <div class="text-muted" id="tableRowCount"></div> {{-- Added row count display --}}
            <nav>
                <ul class="pagination justify-content-center mb-0" id="pagination"></ul> {{-- Removed justify-content-center if needed --}}
            </nav>
        </div>
    </div>
</div>

@include('admin.permission._partial.addModal')
@endsection

@section('script')
@include('admin.permission._partial.script') {{-- Include script partial --}}

{{-- Script for Add Modal Repeater (Keep this if addModal uses it) --}}
<script type="text/javascript">
    var i = 0;
    $("#dynamic-ar").click(function () {
        ++i;
        $("#dynamicAddRemove").append('<tr><td><input type="text" name="name[]" placeholder="Permission Name" class="form-control" required/></td><td><button type="button" class="btn btn-danger btn-sm remove-input-field"><i class="fa fa-trash"></i></button></td></tr>'
            ); // Added required
    });
    $(document).on('click', '.remove-input-field', function () {
        // Prevent removing the last input field if desired
        if ($("#dynamicAddRemove tr").length > 1) {
            $(this).parents('tr').remove();
        } else {
             $(this).parents('tr').find('input').val(''); // Clear instead
             // alert("At least one permission name is required.");
        }
    });
    // Reset repeater on modal close
     $('#exampleModal').on('hidden.bs.modal', function () {
        $("#dynamicAddRemove").find("tr:gt(0)").remove(); // Remove all but the first row
        $("#dynamicAddRemove").find("tr:first input").val(''); // Clear the first row's input
        $('#addPermissionForm')[0].reset(); // Reset the rest of the form
        i = 0; // Reset counter
     });
</script>
@endsection