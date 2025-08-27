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
                        <h5 class="mb-3">Client Details</h5>
                        <div class="list-group mb-4" style="height: 305px;overflow: auto;">
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Customer Number</span>
                                    <p class="mb-0 fw-medium">{{ $client['customer_number'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Full Name</span>
                                    <p class="mb-0 fw-medium">
                                        {{ trim(($client['first_name'] ?? '') . ' ' . ($client['middle_name'] ?? '') . ' ' . ($client['last_name'] ?? '')) ?: 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Email</span>
                                    <p class="mb-0 fw-medium">{{ $client['email'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Mobile</span>
                                    <p class="mb-0 fw-medium">{{ $client['mobile'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Alternate Mobile</span>
                                    <p class="mb-0 fw-medium">{{ $client['alternate_mobile'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>PAN Number</span>
                                    <p class="mb-0 fw-medium">{{ $client['pan_number'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Aadhar Number</span>
                                    <p class="mb-0 fw-medium">{{ $client['aadhar_number'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Age</span>
                                    <p class="mb-0 fw-medium">{{ $client['age'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Gender</span>
                                    <p class="mb-0 fw-medium">{{ $client['gender'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Marital Status</span>
                                    <p class="mb-0 fw-medium">{{ $client['marital_status'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Permanent Address</span>
                                    <p class="mb-0 fw-medium text-truncate" style="max-width: 190px;"
                                        title="{{ $client['customer_address'] ?? 'N/A' }}{{ $client['PerAdd_city'] ?? '' }}
                                        {{ $client['district'] ?? '' }}
                                        {{ $client['PerAdd_state'] ?? '' }}
                                        {{ $client['PerAdd_pin_code'] ?? '' }}">
                                        {{ $client['customer_address'] ?? 'N/A' }},
                                        {{ $client['PerAdd_city'] ?? '' }},
                                        {{ $client['district'] ?? '' }},
                                        {{ $client['PerAdd_state'] ?? '' }},
                                        {{ $client['PerAdd_pin_code'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Residential Address</span>
                                    <p class="mb-0 fw-medium text-truncate" style="max-width: 190px;"
                                        title="{{ $client['customer_residential_address'] ?? 'N/A' }}">
                                        {{ $client['customer_residential_address'] ?? 'N/A' }}</p>
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
                                            Solar Installation -
                                            {{ trim(($client['first_name'] ?? '') . ' ' . ($client['middle_name'] ?? '') . ' ' . ($client['last_name'] ?? '')) ?: 'N/A' }}
                                        </a>
                                    </h5>
                                    <p class="text-dark mb-0">Customer ID :
                                        <span class="text-primary">{{ $client['customer_number'] ?? 'N/A' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="height: 280px;overflow: auto;">
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Solar Type
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['solar_type'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Roof Type
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['roof_type'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Roof Area
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['roof_area'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Solar Capacity
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['capacity'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Solar Company
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['solar_company'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Panel Type
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['panel_type'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Number of Panel
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['number_of_panels'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Panel Voltage
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['panel_voltage'] ?? 'N/A' }}</span>
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
                                            Inverter Company
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['inverter_company'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Inverter Capacity
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['inverter_capacity'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Inverter Serial Number
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['inverter_serial_number'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Application Reference No.
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['application_ref_no'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Light Bill No.
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['light_bill_no'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Payment Mode
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['payment_mode'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Solar Total Amount
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['solar_total_amount'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Total Received Amount
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['total_received_amount'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Date of Full Payment
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['date_full_payment'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Registration Date
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['registration_date'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-5">
                                        <p class="d-flex align-items-center mb-0">
                                            Channel Partner
                                        </p>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="px-3 rounded d-flex align-items-center">
                                            <h6 class="mb-0"><span
                                                    class="text-black">{{ $solar_detail['channel_partner_name'] ?? 'N/A' }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Solar Subsidy Info</h5>
                        <div class="list-group mb-4" style="height: 150px;">
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Subsidy Amount (₹)</span>
                                    <p class="mb-0 fw-medium">{{ $subsidy['subsidy_amount'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Subsidy Status</span>
                                    <p class="mb-0 fw-medium">{{ $subsidy['subsidy_status'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Token ID No.</span>
                                    <p class="mb-0 fw-medium">{{ $subsidy['token_id'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Customer Bank Details</h5>
                        <div class="list-group mb-4" style="height: 150px;overflow: auto;">
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Bank Name</span>
                                    <p class="mb-0 fw-medium">{{ $customer_bank_detail['bank_name'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Branch</span>
                                    <p class="mb-0 fw-medium">{{ $customer_bank_detail['bank_branch'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Account Number</span>
                                    <p class="mb-0 fw-medium">{{ $customer_bank_detail['account_number'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>IFSC Code</span>
                                    <p class="mb-0 fw-medium">{{ $customer_bank_detail['ifsc_code'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Loan Applicants Bank Details</h5>
                        <div class="list-group mb-4" style="height: 150px;overflow: auto;">
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Loan Type</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['loan_type'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Jan-Samarth ID</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['customer_number'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Jan-Samarth Registration Date</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['mobile'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Bank Name</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['bank_name'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Branch</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['bank_branch'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Account Number</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['account_number'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>IFSC Code</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['ifsc_code'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Branch Manager Phone</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['branch_manager_phone'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Loan Manager Phone</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['loan_manager_phone'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Loan Status</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['loan_status'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Loan Sanction Date</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['loan_sanction_date'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Loan Disbursal Date</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['loan_disbursed_date'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Managed By</span>
                                    <p class="mb-0 fw-medium">{{ $loan_bank_detail['managed_by_name'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            @if (!empty($loan_bank_detail['co_full_name']))
                                <h5 class="mb-3">Co-Loan Applicants Details</h5>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Full Name</span>
                                        <p class="mb-0 fw-medium">{{ $loan_bank_detail['co_full_name'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Age</span>
                                        <p class="mb-0 fw-medium">{{ $loan_bank_detail['co_age'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Aadhar Number</span>
                                        <p class="mb-0 fw-medium">{{ $loan_bank_detail['co_aadhar'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>PAN Number</span>
                                        <p class="mb-0 fw-medium">{{ $loan_bank_detail['co_pan'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <span>Mobile No</span>
                                        <p class="mb-0 fw-medium">{{ $loan_bank_detail['co_mobile'] ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-3 col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Solar Installation Details</h5>
                        <div class="list-group mb-4" style="height: 150px;overflow: auto;">
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Installers</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['installer_name'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Installation Date</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['installation_date'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Structure Department Name</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['structure_department_name'] ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Wiring Department Name</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['wiring_department_name'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>SR Number</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['sr_number'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Meter Payment Receipt No.</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['meter_payment_receipt_number'] ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Meter Payment Date</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['meter_payment_date'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Meter Payment Amount</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['meter_payment_amount'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>Panel Serial Numbers</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['panel_serial_numbers'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span>DCR Certificate Number</span>
                                    <p class="mb-0 fw-medium">{{ $solar_detail['dcr_certificate_number'] ?? 'N/A' }}</p>
                                </div>
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
                    <a onClick="fnAddEdit(this, '{{ url('/client/documents/upload') }}', {{ $client['id'] }}, 'Upload Files')"
                        class="btn btn-primary waves-effect waves-light text-white">
                        <i class="mdi mdi-plus me-1"></i>Add New
                    </a>
                    <i class="mdi mdi-chevron-down me-1" id="toggle-open" style="display: none;"></i>
                    <i class="mdi mdi-chevron-up me-1" id="toggle-close"></i>
                </div>
            </div>
            <div class="card-body mt-6" id="file-section">
                <div class="row">
                    @foreach ($appDocument as $file)
                        <div class="col-sm-4">
                            <div class="card shadow-none border rounded mb-4">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0)">
                                                <i
                                                    class="mdi mdi-file-document-outline me-2 p-2 bg-label-info rounded"></i>
                                            </a>
                                            <div>
                                                <h6 class="fw-bold mb-0">{{ $file['file_display_name'] }}</h6>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url($file['relative_path']) }}" target="_blank"
                                                class="btn btn-sm btn-text-secondary rounded btn-icon" download>
                                                <i class="mdi mdi-tray-arrow-down"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="fw-medium mb-0">
                                            {{ \Carbon\Carbon::parse($file['created_at'])->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
