@extends('admin.master.master')

@section('title')
IIFC’s Strength Settings | {{ $ins_name }}
@endsection

@section('css')
<style>
    /* Optional: Style adjustments for better spacing */
    .form-control[type=number]::-webkit-inner-spin-button,
    .form-control[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    .form-control[type=number] {
      -moz-appearance: textfield; /* Firefox */
    }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Frontend</li> {{-- Adjust parent if needed --}}
            <li class="breadcrumb-item active" aria-current="page">IIFC’s Strength</li>
        </ol>
    </nav>

    {{-- Form Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Update IIFC’s Strength Metrics</h5>
        </div>
        <div class="card-body">
            @include('flash_message')

            {{-- Display Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     Please fix the following errors:
                    <ul class="mb-0 mt-2">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Form points to the update route with the specific ID --}}
            <form action="{{ route('iifcStrength.update', $strength->id) }}" method="POST" novalidate id="strengthForm">
                @csrf
                @method('PUT') {{-- Use PUT method for update --}}

                <div class="row g-3">
                    {{-- Ongoing Project --}}
                    <div class="col-md-6 col-lg-3">
                        <label for="ongoing_project" class="form-label">Ongoing Projects <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('ongoing_project') is-invalid @enderror" id="ongoing_project" name="ongoing_project" value="{{ old('ongoing_project', $strength->ongoing_project) }}" required>
                        @error('ongoing_project') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Complete Projects --}}
                    <div class="col-md-6 col-lg-3">
                        <label for="complete_projects" class="form-label">Completed Projects <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('complete_projects') is-invalid @enderror" id="complete_projects" name="complete_projects" value="{{ old('complete_projects', $strength->complete_projects) }}" required>
                        @error('complete_projects') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Countries --}}
                    <div class="col-md-6 col-lg-3">
                        <label for="countries" class="form-label">Countries Active In <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('countries') is-invalid @enderror" id="countries" name="countries" value="{{ old('countries', $strength->countries) }}" required>
                        @error('countries') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Years in Business --}}
                    <div class="col-md-6 col-lg-3">
                        <label for="years_in_business" class="form-label">Years in Business <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('years_in_business') is-invalid @enderror" id="years_in_business" name="years_in_business" value="{{ old('years_in_business', $strength->years_in_business) }}" required>
                        @error('years_in_business') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Strength Metrics</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // --- Form Submission Validation Trigger ---
        $('form#strengthForm').submit(function(e) {
            let isValid = true;
            let $form = $(this);
            $('.is-invalid').removeClass('is-invalid'); // Clear previous errors

            // Standard HTML5 Validation handles required and number types
            if ($form[0].checkValidity() === false) {
                isValid = false;
                $form.find(':invalid').first().focus();
            }

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();
                 // Scroll to first error if needed
                 const firstError = $form.find('.is-invalid').first();
                 if (firstError.length) { $('html, body').animate({ scrollTop: firstError.offset().top - 100 }, 500); }
            }
            $form.addClass('was-validated');
        });

         // --- Clear Validation Feedback on Input ---
         $('form#strengthForm input').on('input change', function() {
             // Basic clearing for standard fields
              if ( $(this).val() !== '' && $(this).prop('min') <= parseFloat($(this).val()) ) { // Also check min value
                 $(this).removeClass('is-invalid');
             } else if ($(this).prop('required')) {
                  $(this).addClass('is-invalid'); // Re-add if invalid
             }
         });
    });
</script>
@endsection