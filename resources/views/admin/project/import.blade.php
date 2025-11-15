@extends('admin.master.master')

@section('title')
Import Projects | {{ $ins_name ?? 'Admin Panel' }}
@endsection

@section('css')
<style>
    /* Optional: Add custom styles if needed */
    .list-group-item strong {
        color: var(--bs-primary);
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('project.index') }}">Projects</a></li>
            <li class="breadcrumb-item active" aria-current="page">Import Projects</li>
        </ol>
    </nav>

    <div class="row">
        {{-- Upload Form Column --}}
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Import Projects from Excel</h5>
                </div>
                <div class="card-body">
                    {{-- Flash Messages (Success/Error from Controller Redirects) --}}
                    @include('flash_message')

                    {{-- Display Specific Import Validation Errors --}}
                    @if (session('import_errors'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                             <h6 class="alert-heading fw-bold"><i data-feather="alert-triangle" class="me-1"></i>Import failed with the following errors:</h6>
                            <ul class="mb-0 small">
                                @foreach (session('import_errors') as $error)
                                    <li>{!! $error !!}</li> {{-- Use {!! !!} if error messages might contain HTML --}}
                                @endforeach
                            </ul>
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Upload Form --}}
                    <form action="{{ route('project.storeImport') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="file" class="form-label fw-bold">Upload Excel File (.xlsx, .xls)</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".xlsx, .xls, .csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <small class="form-text text-muted">Please use the provided template file.</small>
                            @enderror
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="upload" class="me-1" style="width:16px;"></i>
                                Start Import
                            </button>
                             <a href="{{ route('project.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Instructions Column --}}
        <div class="col-md-5">
            <div class="card shadow-sm border-info">
                <div class="card-header bg-info-soft text-info">
                    <h6 class="mb-0 fw-bold"><i data-feather="info" class="me-1" style="width:18px;"></i> Import Instructions</h6>
                </div>
                <div class="card-body">
                    <p>Follow these instructions carefully to ensure a successful import.</p>

                    {{-- Download Template Button --}}
                    <a href="{{ route('project.downloadTemplate') }}" class="btn btn-sm btn-outline-success w-100 mb-3">
                        <i data-feather="download" class="me-1" style="width:16px;"></i> Download Template File (.xlsx)
                    </a>

                    <h6 class="text-dark fw-bold">Excel Header & Data Requirements:</h6>
                    <p class="small">The first row of your file <strong>must</strong> contain the headers exactly as shown in the template. Data must follow these rules:</p>

                    {{-- Detailed Instructions --}}
                    <ul class="list-group list-group-flush small mb-3">
                        <li class="list-group-item px-0"><strong>title:</strong> Must be unique. The import will fail if a project with this title already exists.</li>
                        <li class="list-group-item px-0"><strong>description:</strong> (Required) The project description.</li>
                        <li class="list-group-item px-0"><strong>category_name:</strong> (Required) Enter the exact category name. If it doesn't exist, a new category will be created automatically (without an image).</li>
                        <li class="list-group-item px-0"><strong>client_name:</strong> (Required) If the client name doesn't exist, a new client will be created automatically.</li>
                        <li class="list-group-item px-0"><strong>country_name:</strong> (Required) Enter the exact country name. If it doesn't exist, a new country will be created automatically (with 'Active' status).</li>
                        <li class="list-group-item px-0"><strong>status:</strong> Must be one of these three exact (lowercase) words: `pending`, `ongoing`, or `complete`.</li>
                        <li class="list-group-item px-0"><strong>agreement_signing_date:</strong> Must be a valid date. Excel's default date formats (like `10/28/2025`) or a text string (like `2025-10-28`) will work.</li>
                        <li class="list-group-item px-0"><strong>is_flagship:</strong> Must be `1` (for Yes) or `0` (for No). Other values like `TRUE`/`FALSE` might also work.</li>
                    </ul>

                    <strong class="text-danger d-block">Important Notes:</strong>
                    <ul class="small text-danger">
                        <li>Do not change the header names or order in the template file.</li>
                        <li>Ensure names (category, client, country) match exactly or new records will be created.</li>
                        <li>Remove any example rows from the template before uploading your data.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Include Feather Icons if not globally initialized --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endsection