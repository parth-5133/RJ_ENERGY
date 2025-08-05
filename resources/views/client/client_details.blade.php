@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y ">
        <!-- Back Button -->
        <a href="{{ route('client') }}" class="btn btn-primary waves-effect waves-light text-white mb-2">
            <i class="mdi mdi-arrow-left"></i> Back
        </a>
        <div class="row">
            <div class="col-xxl-3 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Solar Installation Details</h5>
                        <div class="list-group mb-4">
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Client</span>
                                    <p class="mb-0 fw-medium">{{ $client[0]['customer_name'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Customer Number</span>
                                    <p class="mb-0 fw-medium">{{ $client[0]['customer_number'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Mobile</span>
                                    <p class="mb-0 fw-medium">{{ $client[0]['mobile'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Solar Total Amount</span>
                                    <p class="mb-0 fw-medium">₹{{ number_format($client[0]['solar_total_amount'] ?? 0, 2) }}
                                    </p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Registration Date</span>
                                    <p class="mb-0 fw-medium">
                                        {{ date('d/m/Y', strtotime($client[0]['registration_date'] ?? '')) }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>System Capacity</span>
                                    <p class="mb-0 fw-medium">{{ $client[0]['capacity'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Status</span>
                                    <p class="mb-0 fw-medium">
                                        <span
                                            class="badge bg-label-{{ $client[0]['status'] == 'Agreed' ? 'success' : 'warning' }}">
                                            {{ $client[0]['status'] ?? 'N/A' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Installer</span>
                                    <p class="mb-0 fw-medium">{{ $client[0]['installer_name'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-9 col-xl-8">
                <div class="card mb-6">
                    <div class="card-body">
                        <div class="bg-body rounded p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <a href="javascript:void(0)" class="flex-shrink-0 me-2">
                                    <img src="https://smarthr.dreamstechnologies.com/laravel/template/public/build/img/social/project-01.svg"
                                        alt="Solar Project">
                                </a>
                                <div>
                                    <h5 class="mb-0">
                                        <a class="text-black" href="javascript:void(0)">
                                            Solar Installation - {{ $client[0]['customer_name'] ?? 'N/A' }}
                                        </a>
                                    </h5>
                                    <p class="text-dark mb-0">Customer ID :
                                        <span class="text-primary">{{ $client[0]['customer_number'] ?? 'N/A' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- First Row -->
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-solar-power me-2 p-1 bg-label-warning rounded"></i>
                                            Solar Capacity
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <span
                                            class="badge rounded bg-label-warning">{{ $client[0]['capacity'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-home-roof me-2 p-1 bg-label-info rounded"></i>
                                            Roof Type
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $client[0]['roof_type'] ?? 'N/A' }}</span></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Second Row -->
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-view-grid-outline me-2 p-1 bg-label-primary rounded"></i>
                                            Roof Area
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <span class="badge rounded bg-label-primary">{{ $client[0]['roof_area'] ?? 'N/A' }}
                                            sq ft</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-flash me-2 p-1 bg-label-success rounded"></i>
                                            Solar Company
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $client[0]['solar_company'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Third Row -->
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-lightning-bolt me-2 p-1 bg-label-info rounded"></i>
                                            Inverter Company
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $client[0]['inverter_company'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-factory me-2 p-1 bg-label-secondary rounded"></i>
                                            Usage Pattern
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <span
                                            class="badge rounded bg-label-secondary">{{ $client[0]['usage_pattern'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fourth Row -->
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-bank me-2 p-1 bg-label-primary rounded"></i>
                                            Loan Required
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <span
                                            class="badge rounded bg-label-{{ $client[0]['loan_required'] == 'Yes' ? 'success' : 'secondary' }}">{{ $client[0]['loan_required'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-cash me-2 p-1 bg-label-warning rounded"></i>
                                            Loan Status
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <span
                                            class="badge rounded bg-label-{{ $client[0]['loan_status'] == 'Disbursed' ? 'success' : 'warning' }}">{{ $client[0]['loan_status'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Fifth Row -->
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-gift me-2 p-1 bg-label-info rounded"></i>
                                            Subsidy Status
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <span
                                            class="badge rounded bg-label-{{ $client[0]['subsidy_status'] == 'Approved' ? 'success' : 'warning' }}">{{ $client[0]['subsidy_status'] ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            <i class="mdi mdi-account-tie me-2 p-1 bg-label-success rounded"></i>
                                            Channel Partner
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $client[0]['channel_partner_name'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="mb-4 mt-4">
                                    <h6 class="mb-3 text-primary">
                                        <i class="mdi mdi-map-marker me-2"></i>
                                        Address Information
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover">
                                            <tbody>
                                                <tr>
                                                    <td class="fw-semibold text-muted" style="width: 30%;">
                                                        <i class="mdi mdi-home-map-marker me-2 text-info"></i>Customer
                                                        Address
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span
                                                                class="text-dark">{{ $client[0]['customer_address'] ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-semibold text-muted">
                                                        <i class="mdi mdi-home-city me-2 text-success"></i>Residential
                                                        Address
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span
                                                                class="text-dark">{{ $client[0]['customer_residential_address'] ?? 'N/A' }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-6 rounded">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">Solar Installation Documents</h5>
                        <div class="d-flex align-items-center gap-4">
                            {{-- <a href="#" class="btn btn-primary waves-effect waves-light text-white">
                                <i class="mdi mdi-plus me-1"></i>Add New
                            </a> --}}

                            <a onClick="fnAddEdit(this, '{{ url('/client/documents/upload') }}', {{ $client[0]['customer_id']}}, 'Upload Files')"
                                class="btn btn-primary waves-effect waves-light text-white">
                                <i class="mdi mdi-plus me-1"></i>Add New
                            </a>
                            <i class="mdi mdi-chevron-down me-1" id="toggle-open" style="display: none;"></i>
                            <i class="mdi mdi-chevron-up me-1" id="toggle-close"></i>
                        </div>
                    </div>
                    <div class="card-body mt-6" id="file-section">
                        <div class="row">
                            @if ($client[0]['cancel_cheque'])
                                <div class="col-sm-4">
                                    <div class="card shadow-none border rounded mb-4">
                                        <div class="card-body">
                                            <div
                                                class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0)">
                                                        <i
                                                            class="mdi mdi-file-document-outline me-2 p-2 bg-label-info rounded"></i>
                                                    </a>
                                                    <div>
                                                        <h6 class="fw-bold mb-0">Cancel Cheque</h6>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ asset('storage/' . $client[0]['cancel_cheque']) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-text-secondary rounded-pill btn-icon"
                                                        download>
                                                        <i class="mdi mdi-tray-arrow-down"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-medium mb-0">
                                                    {{ date('d/m/Y', strtotime($client[0]['created_at'])) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($client[0]['light_bill'])
                                <div class="col-sm-4">
                                    <div class="card shadow-none border rounded mb-4">
                                        <div class="card-body">
                                            <div
                                                class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0)">
                                                        <i
                                                            class="mdi mdi-file-document-outline me-2 p-2 bg-label-warning rounded"></i>
                                                    </a>
                                                    <div>
                                                        <h6 class="fw-bold mb-0">Light Bill</h6>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ asset('storage/' . $client[0]['light_bill']) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-text-secondary rounded-pill btn-icon"
                                                        download>
                                                        <i class="mdi mdi-tray-arrow-down"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <p class="fw-medium mb-0">
                                                    {{ date('d/m/Y', strtotime($client[0]['created_at'])) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            function toggleSection(toggleOpenId, toggleCloseId, sectionId) {
                $(toggleOpenId).click(function() {
                    $(sectionId).slideDown();
                    $(toggleOpenId).hide();
                    $(toggleCloseId).show();
                });

                $(toggleCloseId).click(function() {
                    $(sectionId).slideUp();
                    $(toggleOpenId).show();
                    $(toggleCloseId).hide();
                });
            }
            toggleSection('#toggle-open', '#toggle-close', '#file-section');
            toggleSection('#toggle-open_second', '#toggle-close_second', '#file-section_second');
        });
    </script>
@endsection
