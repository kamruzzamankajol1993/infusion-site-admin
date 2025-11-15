@extends('admin.master.master')

@section('title')
View Applicant: {{ $jobApplicant->full_name }} | {{ $ins_name ?? 'Admin Panel' }}
@endsection

@section('css')
<style>
    .applicant-details dt { color: #6c757d; font-weight: 500; }
    .applicant-details dd { margin-bottom: 1rem; }
    /* Prevent extra margin below last paragraph inside dd if using nl2br */
    .applicant-details dd > *:last-child { margin-bottom: 0; }
    .cv-link { font-size: 1.1rem; }
</style>
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    {{-- Header & Breadcrumb --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('jobApplicant.index') }}">Job Applicants</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $jobApplicant->full_name }}</li>
            </ol>
        </nav>
        <div>
            <a href="{{ route('jobApplicant.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left" class="me-1" style="width:16px;"></i> Back to List
            </a>
             @if (Auth::user()->can('jobApplicantDelete'))
             {{-- Add delete button directly here --}}
              <button type="button" class="btn btn-danger ms-2 btn-delete-ja-show" data-id="{{ $jobApplicant->id }}">
                   <i data-feather="trash-2" class="me-1" style="width:16px;"></i> Delete Applicant
              </button>
             @endif
        </div>
    </div>

    {{-- Details Card --}}
    <div class="card shadow-sm">
        <div class="card-header bg-light">
             <h4 class="mb-0">Applicant Details</h4>
        </div>
        <div class="card-body applicant-details">
             <dl class="row">
                {{-- Basic Info --}}
                <dt class="col-sm-3 col-lg-2">Full Name</dt>
                <dd class="col-sm-9 col-lg-10">{{ $jobApplicant->full_name }}</dd>

                {{-- NEW: Date of Birth --}}
                <dt class="col-sm-3 col-lg-2">Date of Birth</dt>
                {{-- --- vvv THIS LINE IS FIXED vvv --- --}}
                <dd class="col-sm-9 col-lg-10">{{ $jobApplicant->date_of_birth ? \Carbon\Carbon::parse($jobApplicant->date_of_birth)->format('d M, Y') : 'N/A' }}</dd>

                <dt class="col-sm-3 col-lg-2">Email</dt>
                <dd class="col-sm-9 col-lg-10"><a href="mailto:{{ $jobApplicant->email }}">{{ $jobApplicant->email }}</a></dd>

                <dt class="col-sm-3 col-lg-2">Phone</dt>
                <dd class="col-sm-9 col-lg-10">{{ $jobApplicant->phone_number }}</dd>

                {{-- NEW: Address --}}
                 <dt class="col-sm-3 col-lg-2">Address</dt>
                <dd class="col-sm-9 col-lg-10">{!! $jobApplicant->address ? nl2br(e($jobApplicant->address)) : 'N/A' !!}</dd>

                <dt class="col-sm-3 col-lg-2">Applied For</dt>
                <dd class="col-sm-9 col-lg-10">
                    @if($jobApplicant->career)
                        {{-- Assuming 'career.show' route exists for viewing the job post --}}
                        <a href="{{ route('career.show', $jobApplicant->job_id) }}" title="View Job Posting">{{ $jobApplicant->career->title }}</a>
                        ({{ $jobApplicant->career->position }})
                    @else
                        <span class="text-muted">Job posting not found or deleted</span>
                    @endif
                </dd>

                <dt class="col-sm-3 col-lg-2">Applied On</dt>
                <dd class="col-sm-9 col-lg-10">{{ $jobApplicant->created_at->format('d M, Y H:i A') }}</dd>

                 <dt class="col-sm-3 col-lg-2">CV / Resume</dt>
                <dd class="col-sm-9 col-lg-10">
                    {{-- Use cv_url accessor (Ensure Model uses Storage::url()) --}}
                    @if($jobApplicant->cv_url)
                        <a href="{{ $jobApplicant->cv_url }}" target="_blank" class="cv-link">
                            <i class="fa fa-file-pdf text-danger me-1"></i> View/Download CV
                        </a>
                     @else
                        <span class="text-muted">No CV file uploaded.</span>
                     @endif
                </dd>

                {{-- Qualification (Existing) --}}
                @if($jobApplicant->qualification)
                    <dt class="col-12 mt-3 text-primary border-bottom pb-1 mb-2">Qualification Summary</dt>
                    <dd class="col-12">{!! nl2br(e($jobApplicant->qualification)) !!}</dd>
                @endif

                {{-- NEW: Educational Background --}}
                @if($jobApplicant->educational_background)
                    <dt class="col-12 mt-3 text-primary border-bottom pb-1 mb-2">Educational Background</dt>
                    <dd class="col-12">{!! nl2br(e($jobApplicant->educational_background)) !!}</dd>
                @endif

                {{-- NEW: Working Experience --}}
                @if($jobApplicant->working_experience)
                    <dt class="col-12 mt-3 text-primary border-bottom pb-1 mb-2">Working Experience</dt>
                    <dd class="col-12">{!! nl2br(e($jobApplicant->working_experience)) !!}</dd>
                @endif
                
                {{-- --- vvv ADDED THIS BLOCK vvv --- --}}
                @if($jobApplicant->total_year_of_experience)
                    <dt class="col-12 mt-3 text-primary border-bottom pb-1 mb-2">Total Year of Experience</dt>
                    <dd class="col-12">{{ $jobApplicant->total_year_of_experience }}</dd>
                @endif


                {{-- Additional Information --}}
                @if($jobApplicant->additional_information)
                    <dt class="col-12 mt-3 text-primary border-bottom pb-1 mb-2">Additional Information</dt>
                    <dd class="col-12">{!! nl2br(e($jobApplicant->additional_information)) !!}</dd>
                @endif

            </dl>
        </div>
    </div>

</div>
@endsection

@section('script')
{{-- SweetAlert JS (ensure it's loaded in master layout or uncomment) --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script>
         // Initialize Feather icons if used
         document.addEventListener('DOMContentLoaded', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
         });

         // --- Delete Button Handler (from Show Page) ---
         $(document).on('click', '.btn-delete-ja-show', function () {
            const id = $(this).data('id');
            const button = $(this); // Reference the button

            // Define delete route URL
            const deleteUrl = `{{ route('jobApplicant.destroy', ':id') }}`.replace(':id', id);
            const listUrl = `{{ route('jobApplicant.index') }}`; // URL to redirect after delete

            Swal.fire({
                title: 'Delete this applicant?',
                text: "The associated CV file will also be deleted! This cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state on button
                    button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Deleting...');

                    // Perform AJAX delete request
                    $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}" // Ensure CSRF token is available
                        },
                        success: function(response) {
                            // Show success message and redirect
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message || 'Applicant deleted successfully.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = listUrl; // Redirect to index page
                            });
                        },
                        error: function(xhr) {
                            // Show error message
                            Swal.fire(
                                'Error!',
                                xhr.responseJSON?.error || 'Could not delete the applicant. Please try again.',
                                'error'
                            );
                            // Reset button state on error
                            button.prop('disabled', false).html('<i data-feather="trash-2" class="me-1" style="width:16px;"></i> Delete Applicant');
                             try { if (typeof feather !== 'undefined') { feather.replace() } } catch (e) {} // Re-render icon
                        }
                    });
                }
            });
        });

    </script>
@endsection