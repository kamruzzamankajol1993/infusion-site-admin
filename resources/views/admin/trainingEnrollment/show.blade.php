@extends('admin.master.master')

@section('title')
View Enrollment: {{ $trainingEnrollment->name }} | {{ $ins_name }}
@endsection

@section('body')
<div class="container-fluid px-4 py-4">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('trainingEnrollment.index') }}">Enrollments</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Details</li>
        </ol>
    </nav>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Enrollment Details</h5>
            <a href="{{ route('trainingEnrollment.index') }}" class="btn btn-sm btn-secondary">
                <i data-feather="arrow-left" class="me-1" style="width:16px;"></i> Back to List
            </a>
        </div>
        <div class="card-body">
            <div class="row g-4">
                {{-- Left Column --}}
                <div class="col-md-7">
                    <dl class="row">
                        <dt class="col-sm-4">Applicant Name</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->name }}</dd>

                        <dt class="col-sm-4">Designation</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->designation ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Organization</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->organization ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Years of Experience</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->experience ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Highest Degree</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->highest_degree ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-4 text-truncate">Address</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->address ?? 'N/A' }}</dd>
                    </dl>
                </div>

                {{-- Right Column --}}
                <div class="col-md-5">
                    <dl class="row">
                        <dt class="col-sm-4">Training</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('training.show', $trainingEnrollment->training_id) }}" target="_blank">
                                {{ $trainingEnrollment->training->title }}
                            </a>
                        </dd>
                        
                        <dt class="col-sm-4">Email</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->email }}</dd>

                        <dt class="col-sm-4">Mobile</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->mobile }}</dd>

                        <dt class="col-sm-4">Telephone</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->telephone ?? 'N/A' }}</dd>

                        <dt class="col-sm-4">Fax</dt>
                        <dd class="col-sm-8">{{ $trainingEnrollment->fax ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-4">Payment Method</dt>
                        <dd class="col-sm-8 text-capitalize">{{ $trainingEnrollment->payment_method }}</dd>
                        
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                             @if ($trainingEnrollment->status === 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @elseif ($trainingEnrollment->status === 'cancelled')
                                <span class="badge bg-danger">Cancelled</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection