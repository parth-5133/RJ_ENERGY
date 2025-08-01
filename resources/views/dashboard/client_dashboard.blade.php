@extends('layouts.layout')
@section('content')
    {{-- <div class="container-fluid flex-grow-1 container-p-y">

        <div class="row mb-4 g-3">
            <div class="col-12 col-sm-6 col-md-3">
                <a href="#"
                    onClick="fnAddEdit(this, '{{ url('applications/create') }}', 0, 'Apply for Solar Rooftop',true)"
                    class="btn btn-primary w-100 py-3 fs-6 fw-semibold rounded-3 text-nowrap text-truncate">
                    Apply for Solar Rooftop →
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('My-applications.list') }}" class="btn btn-primary w-100 py-3 fs-6 fw-semibold rounded-3 text-nowrap text-truncate">
                    My Applications →
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('My-documents') }}"
                    class="btn btn-primary w-100 py-3 fs-6 fw-semibold rounded-3 text-nowrap text-truncate">
                    My Documents →
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="{{ route('customer.benefits') }}"
                    class="btn btn-primary w-100 py-3 fs-6 fw-semibold rounded-3 text-nowrap text-truncate">
                    Benefits →
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="mb-4">Application Journey</h5>
                <div class="journey-wrapper">
                    <div class="journey-step active">
                        <div class="journey-number">1</div>
                        <div class="journey-label">Consumer Registration</div>
                    </div>
                    <div class="journey-step active">
                        <div class="journey-number">2</div>
                        <div class="journey-label">Application Submission</div>
                    </div>
                    <div class="journey-step active">
                        <div class="journey-number">3</div>
                        <div class="journey-label">Feasibility Approval</div>
                    </div>
                    <div class="journey-step">
                        <div class="journey-number">4</div>
                        <div class="journey-label">Vendor Selection</div>
                    </div>
                    <div class="journey-step">
                        <div class="journey-number">5</div>
                        <div class="journey-label">Upload Agreement</div>
                    </div>
                    <div class="journey-step">
                        <div class="journey-number">6</div>
                        <div class="journey-label">Solar Installation</div>
                    </div>
                    <div class="journey-step">
                        <div class="journey-number">7</div>
                        <div class="journey-label">Project Inspection</div>
                    </div>
                    <div class="journey-step">
                        <div class="journey-number">8</div>
                        <div class="journey-label">Commissioning</div>
                    </div>
                    <div class="journey-step">
                        <div class="journey-number">9</div>
                        <div class="journey-label">Subsidy Request</div>
                    </div>
                    <div class="journey-step">
                        <div class="journey-number">10</div>
                        <div class="journey-label">Subsidy Disbursal</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gy-4">
            <div class="col-md-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="mdi mdi-domain fs-2 text-primary"></i>
                        <h6 class="mt-2">My Applications ID</h6>
                        <h4> <span>
                                {{ empty($applicationId) ? 'No Applications' : (is_array($applicationId) || $applicationId instanceof \Illuminate\Support\Collection ? implode(', ', $applicationId->toArray()) : $applicationId) }}
                            </span></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="mdi mdi-file-document-outline fs-2 text-success"></i>
                        <h6 class="mt-2">Total Applications</h6>
                        <h4><span></span></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="mdi mdi-clock-outline fs-2 text-warning"></i>
                        <h6 class="mt-2">Pending Applications</h6>
                        <h4><span></span></h4>
                    </div>
                </div>
            </div>

        </div>
    </div> --}}
@endsection
