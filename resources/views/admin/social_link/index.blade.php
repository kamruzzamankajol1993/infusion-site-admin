@extends('admin.master.master')

@section('title')
Footer Social Links | {{ $ins_name }}
@endsection

@section('css')
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row: Breadcrumb and Add Button --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item">Settings</li> {{-- Or Frontend --}}
                <li class="breadcrumb-item active" aria-current="page">Social Links</li>
            </ol>
        </nav>
        <div>
            @if (Auth::user()->can('socialLinkAdd')) {{-- Assuming permission --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#addSocialLinkModal" class="btn text-white" style="background-color: var(--primary-color); white-space: nowrap;">
                <i data-feather="plus" class="me-1" style="width:18px; height:18px;"></i> Add New Social Link
            </button>
            @endif
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0">Social Link List</h5>
            <form class="d-flex" role="search" onsubmit="return false;">
                <input class="form-control" id="searchInputSL" type="search" placeholder="Search links..." aria-label="Search"> {{-- Unique ID --}}
            </form>
        </div>
        <div class="card-body">
            @include('flash_message')
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 5%;">Sl</th>
                            <th style="width: 10%;">Icon</th>
                            <th class="sortableSL" data-column="title" style="width: 25%;">Platform Name</th> {{-- Unique class --}}
                            <th class="sortableSL" data-column="link" style="width: 45%;">Link URL</th> {{-- Unique class --}}
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBodySL"> {{-- Unique ID --}}
                        {{-- Rows will be loaded via AJAX --}}
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center">
            <div class="text-muted" id="tableRowCountSL"></div> {{-- Unique ID --}}
            <nav>
                <ul class="pagination justify-content-center mb-0" id="paginationSL"></ul> {{-- Unique ID --}}
            </nav>
        </div>
    </div>
</div>

{{-- Include Modals (Pass $socialMediaNames from controller) --}}
@include('admin.social_link._partial.addModal', ['socialMediaNames' => $socialMediaNames])
@include('admin.social_link._partial.editModal', ['socialMediaNames' => $socialMediaNames])

@endsection

@section('script')
{{-- Include the AJAX table script --}}
@include('admin.social_link._partial.script')
@endsection