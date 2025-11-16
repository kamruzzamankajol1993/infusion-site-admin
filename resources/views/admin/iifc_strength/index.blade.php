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

                {{-- *** UPDATED FORM FIELDS *** --}}
                <div class="row g-3">
                    
                    {{-- Projects --}}
                    <div class="col-md-6 col-lg-4">
                        <label for="projects" class="form-label">Projects <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('projects') is-invalid @enderror" id="projects" name="projects" value="{{ old('projects', $strength->projects) }}" required>
                        @error('projects') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Products --}}
                    <div class="col-md-6 col-lg-4">
                        <label for="products" class="form-label">Products <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('products') is-invalid @enderror" id="products" name="products" value="{{ old('products', $strength->products) }}" required>
                        @error('products') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Experts --}}
                    <div class="col-md-6 col-lg-4">
                        <label for="experts" class="form-label">Experts <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('experts') is-invalid @enderror" id="experts" name="experts" value="{{ old('experts', $strength->experts) }}" required>
                        @error('experts') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Countries --}}
                    <div class="col-md-6 col-lg-4">
                        <label for="countries" class="form-label">Countries <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('countries') is-invalid @enderror" id="countries" name="countries" value="{{ old('countries', $strength->countries) }}" required>
                        @error('countries') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Happy Clients --}}
                    <div class="col-md-6 col-lg-4">
                        <label for="happy_clients" class="form-label">Happy Clients <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('happy_clients') is-invalid @enderror" id="happy_clients" name="happy_clients" value="{{ old('happy_clients', $strength->happy_clients) }}" required>
                        @error('happy_clients') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Years Experienced --}}
                    <div class="col-md-6 col-lg-4">
                        <label for="yrs_experienced" class="form-label">Years Experienced <span class="text-danger">*</span></label>
                        <input type="number" min="0" step="1" class="form-control @error('yrs_experienced') is-invalid @enderror" id="yrs_experienced" name="yrs_experienced" value="{{ old('yrs_experienced', $strength->yrs_experienced) }}" required>
                        @error('yrs_experienced') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
    // This script is generic and does not need changes
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