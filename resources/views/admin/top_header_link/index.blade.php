@extends('admin.master.master')

@section('title')
Manage Top Header Title | {{ $ins_name ?? 'IIFC' }}
@endsection

@section('css')
<style>
    .card-header-custom {
        background-color: #f0f2f5;
        border-bottom: 2px solid var(--primary-color, #175A3A);
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Frontend</li>
            <li class="breadcrumb-item active" aria-current="page">Top Header Title</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Manage Top Header Title</h5>
        </div>
        <div class="card-body">
            @include('flash_message')
            
            <form action="{{ route('topHeaderLink.storeOrUpdate') }}" method="POST">
                @csrf
                <div class="row g-4">
                    {{-- Link 1 Card --}}
                    <div class="col-md-12">
                        <div class="card shadow-sm border">
                            <div class="card-header card-header-custom">
                                <h6 class="mb-0">Header Link </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="link1_title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('link1_title') is-invalid @enderror" id="link1_title" name="link1_title" value="{{ old('link1_title', $link1->title) }}" required>
                                    @error('link1_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- <div class="mb-3">
                                    <label for="link1_link" class="form-label">Link (URL) <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('link1_link') is-invalid @enderror" id="link1_link" name="link1_link" value="{{ old('link1_link', $link1->link) }}" placeholder="https://example.com" required>
                                    @error('link1_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}
                            </div>
                        </div>
                    </div>

                    {{-- Link 2 Card --}}
                    {{-- <div class="col-md-6">
                        <div class="card shadow-sm border">
                            <div class="card-header card-header-custom">
                                <h6 class="mb-0">Header Link 2</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="link2_title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('link2_title') is-invalid @enderror" id="link2_title" name="link2_title" value="{{ old('link2_title', $link2->title) }}" required>
                                    @error('link2_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="link2_link" class="form-label">Link (URL) <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control @error('link2_link') is-invalid @enderror" id="link2_link" name="link2_link" value="{{ old('link2_link', $link2->link) }}" placeholder="https://example.com" required>
                                    @error('link2_link')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div> --}}
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
@endsection

@section('script')
<script>
    // No specific JS needed for this form, but feather icons should be re-initialized
    try { feather.replace() } catch (e) {}
</script>
@endsection