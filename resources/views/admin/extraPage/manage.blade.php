@extends('admin.master.master')

@section('title')
Manage Extra Pages | {{ $ins_name ?? 'IIFC' }}
@endsection

@section('css')
{{-- Summernote CSS --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* --- Card & General Styling --- */
    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 1rem 1.25rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
    }
    .card-body {
        padding: 1.5rem 1.25rem;
    }
    .card-footer {
        background-color: #f8f9fa;
        padding: 1rem 1.25rem;
        border-top: 1px solid #dee2e6;
    }
    /* --- Form & Summernote --- */
    .form-label {
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #343a40;
    }
    .note-editor.note-frame {
        border-radius: 0.375rem;
        border-color: #ced4da;
    }
     .note-editor.note-frame.is-invalid {
         border-color: var(--danger-color, #dc3545) !important;
    }
    /* --- Action Button --- */
    .btn-submit-content {
        background-color: var(--primary-color, #175A3A);
        border: none;
        padding: 0.6rem 1.5rem;
        font-size: 0.95rem;
        font-weight: 500;
    }
     .form-error { font-size: 0.875em; margin-top: 0.25rem; color: var(--danger-color, #dc3545); }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active" aria-current="page">Extra Pages Content</li>
        </ol>
    </nav>

    {{-- Flash Messages & Validation Errors --}}
    @include('flash_message')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the errors:</strong>
            <ul class="mb-0 mt-2">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Determine form action/method --}}
    @php
        $formAction = isset($extraPage) && $extraPage->id
                        ? route('extraPage.update', $extraPage->id)
                        : route('extraPage.store');
        $formMethod = isset($extraPage) && $extraPage->id ? 'PUT' : 'POST';
    @endphp

    <form action="{{ $formAction }}" method="POST" novalidate id="extraPageForm">
        @csrf
        @if($formMethod === 'PUT')
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-header">
                <i data-feather="file-text" class="me-2 text-primary"></i>
                {{ isset($extraPage) && $extraPage->id ? 'Edit Extra Page Content' : 'Create Extra Page Content' }}
            </div>
            <div class="card-body">
                {{-- Privacy Policy Section --}}
                <div class="mb-4">
                    <label for="privacy_policy" class="form-label fs-5">Privacy Policy</label>
                    <textarea class="form-control summernote @error('privacy_policy') is-invalid @enderror" id="privacy_policy" name="privacy_policy" rows="10">{{ old('privacy_policy', $extraPage->privacy_policy ?? '') }}</textarea>
                    <div id="privacy_policy-error" class="form-error"></div>
                    @error('privacy_policy')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
                </div>

                <hr class="my-4"> {{-- Separator --}}

                {{-- Terms & Conditions Section --}}
                <div class="mb-4"> {{-- <-- Adjusted margin --}}
                    <label for="term_condition" class="form-label fs-5">Terms & Conditions</label>
                    <textarea class="form-control summernote @error('term_condition') is-invalid @enderror" id="term_condition" name="term_condition" rows="10">{{ old('term_condition', $extraPage->term_condition ?? '') }}</textarea>
                     <div id="term_condition-error" class="form-error"></div>
                     @error('term_condition')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
                </div>

                {{-- === ADD THIS NEW BLOCK === --}}
                <hr class="my-4"> 

                <div class="mb-3">
                    <label for="faq" class="form-label fs-5">FAQ</label>
                    <textarea class="form-control summernote @error('faq') is-invalid @enderror" id="faq" name="faq" rows="10">{{ old('faq', $extraPage->faq ?? '') }}</textarea>
                     <div id="faq-error" class="form-error"></div>
                     @error('faq')<div class="invalid-feedback d-block">{{$message}}</div>@enderror
                </div>
                {{-- === END NEW BLOCK === --}}

            </div>
            <div class="card-footer text-end">
                 <button type="submit" class="btn btn-primary btn-submit-content">
                    {{ isset($extraPage) && $extraPage->id ? 'Update Content' : 'Save Content' }}
                </button>
                 <a href="{{ route('home') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
{{-- Summernote JS --}}
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
{{-- Feather Icons --}}
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

<script>
    $(document).ready(function() {
        // --- Initialize Summernote ---
        try {
            // This class selector applies to all textareas, including the new 'faq' one
            $('.summernote').summernote({
                placeholder: 'Enter content here...',
                tabsize: 2,
                height: 300, // Adjust height as needed
                toolbar: [
                    ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    // Basic validation: remove error style if user types something
                    onChange: function(contents, $editable) {
                        const $textarea = $(this);
                         if (!$textarea.summernote('isEmpty')) {
                             $textarea.removeClass('is-invalid');
                             $textarea.siblings('.note-editor').removeClass('is-invalid');
                             $textarea.siblings('.form-error').empty();
                             $textarea.siblings('.invalid-feedback.d-block').remove();
                         }
                    }
                }
            });
        } catch(e) { console.warn("Summernote failed to initialize."); }

        // --- Initialize Feather Icons ---
        try { feather.replace() } catch(e) { console.warn("Feather icons failed.")}

        // --- Basic Form Validation (Optional, as fields are nullable) ---
        $('form#extraPageForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);
             $('.form-error').empty(); // Clear previous JS errors

            // No specific client-side validation needed since fields are nullable
            // Standard check is minimal here
            if ($form[0].checkValidity() === false) {
                 isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                 const firstError = $('.is-invalid, .form-error:not(:empty)').first();
                 if (firstError.length) { $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); }
            }
            // Add was-validated for Bootstrap styling if needed, less useful here
            // $form.addClass('was-validated');
        });

    });
</script>
@endsection