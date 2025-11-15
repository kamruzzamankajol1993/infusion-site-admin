@extends('admin.master.master')

@section('title')
View Publication: {{ $publication->title }} | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Styling for definition list */
    .publication-details dt {
        color: #6c757d; /* Muted text color for labels */
        font-weight: 500; /* Slightly bolder labels */
        margin-bottom: 0.5rem; /* Space below label */
    }
    .publication-details dd {
        margin-bottom: 1rem; /* Space below definition */
    }
    /* Style for PDF link */
    .pdf-link-lg {
        font-size: 1.1rem; /* Slightly larger link */
        text-decoration: none; /* Remove underline */
        color: #0d6efd; /* Standard link blue */
    }
    .pdf-link-lg:hover {
        text-decoration: underline; /* Underline on hover */
    }
    /* Style for the publication image */
    .publication-image {
        max-width: 100%;       /* Prevent image from exceeding container width */
        height: auto;          /* Maintain aspect ratio */
        max-height: 300px;     /* Limit the maximum height */
        object-fit: contain;   /* Ensure the entire image is visible */
        border-radius: .375rem;/* Bootstrap's standard border radius */
        border: 1px solid #dee2e6; /* Light gray border */
        margin-bottom: 1.5rem; /* Space below the image */
        background-color: #f8f9fa; /* Light background for transparent parts */
    }
    /* Ensure Summernote content line height is reasonable */
    .publication-details dd div {
        line-height: 1.6;
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header Row: Breadcrumb and Action Buttons --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        {{-- Breadcrumb Navigation --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('publication.index') }}">Publications</a></li>
                {{-- Limit title length in breadcrumb for readability --}}
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($publication->title, 50) }}</li>
            </ol>
        </nav>
        {{-- Action Buttons --}}
        <div>
            {{-- Back Button --}}
            <a href="{{ route('publication.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-1" style="width:16px;"></i> Back to List
            </a>
            {{-- Edit Button (conditional based on permission) --}}
            @if (Auth::user()->can('publicationUpdate'))
            <a href="{{ route('publication.edit', $publication->id) }}" class="btn btn-info text-white ms-2">
                <i data-feather="edit-2" class="me-1" style="width:16px;"></i> Edit
            </a>
            @endif
        </div>
    </div>

    {{-- Publication Details Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
             {{-- Publication Title --}}
             <h4 class="mb-0">{{ $publication->title }}</h4>
        </div>
        <div class="card-body publication-details">

            {{-- Image Display Section (only if image exists) --}}
            @if($publication->image)
            <div class="text-center mb-4 pb-3 border-bottom"> {{-- Center image and add bottom border --}}
                 {{-- Construct the asset URL correctly based on your ImageUploadTrait's storage path --}}
                 {{-- Assuming trait saves relative to 'public/uploads/' --}}
                 <img src="{{ asset('uploads/' . $publication->image) }}" alt="{{ $publication->title }}" class="publication-image">
                 {{-- If trait saves relative to 'public/', use: <img src="{{ asset($publication->image) }}" ... > --}}
            </div>
            @endif

            {{-- Details List using <dl> for semantics --}}
             <dl class="row">
                {{-- Date --}}
                <dt class="col-sm-3 col-lg-2">Date</dt>
                <dd class="col-sm-9 col-lg-10">{{ $publication->date ? date('d M, Y', strtotime($publication->date)) : 'N/A' }}</dd>

                {{-- PDF File Link --}}
                <dt class="col-sm-3 col-lg-2">PDF File</dt>
                <dd class="col-sm-9 col-lg-10">
                    @if($publication->pdf_file)
                        {{-- Use asset() helper assuming file is directly in public path --}}
                        <a href="{{ asset($publication->pdf_file) }}" target="_blank" class="pdf-link-lg" title="View/Download PDF">
                            <i class="fa fa-file-pdf text-danger me-1"></i> {{ basename($publication->pdf_file) }}
                        </a>
                     @else
                        <span class="text-muted">No PDF file uploaded.</span>
                     @endif
                </dd>

                {{-- Description (only show section if description exists) --}}
                @if($publication->description)
                    <dt class="col-sm-3 col-lg-2 mt-3">Description</dt>
                    <dd class="col-sm-9 col-lg-10 mt-3">
                        {{-- Use {!! !!} to render HTML content from Summernote safely --}}
                        {!! $publication->description !!}
                    </dd>
                @endif
            </dl>

            {{-- Timestamps Footer --}}
             <hr class="mt-4">
             <p class="text-muted small mb-0 text-end">
                Uploaded: {{ $publication->created_at->format('d M, Y H:i A') }} |
                Last Updated: {{ $publication->updated_at->format('d M, Y H:i A') }}
            </p>
        </div> {{-- End card-body --}}
    </div> {{-- End card --}}

</div> {{-- End container-fluid --}}
@endsection

@section('script')
    <script>
         // Initialize Feather icons if used in the layout
         try { feather.replace() } catch (e) { console.warn("Feather icons failed to initialize.")}
    </script>
@endsection