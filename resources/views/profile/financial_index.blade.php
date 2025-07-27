@php
    $id = request()->query('id');
    $controller = new App\Http\Controllers\Web\ProfileController();
    $profileHeader = $controller->profileHeader($id);
    $disabled = $role_code == config('roles.EMPLOYEE') ? 'disabled' : '';
@endphp
@extends('layouts.layout')
@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6><span class="text-muted fw-light">Account Settings /</span> Financial Information</h6>
        {{ $profileHeader }}
        <div class="row">
            <div class="col-md-12">
                @include('profile.nav-tabs')
                <div class="card mb-4">
                    <h5 class="card-header mb-4">Financial Details</h5>
                    <div class="card-body">
                        <form id="BankDetailsForm" name="commonform" method="POST">
                            <input type="hidden" id="user_uuid" name="user_uuid" value="{{ request()->get('id') }}" />
                            <div class="row gy-4">
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="base_salary" name="base_salary"
                                            placeholder="Base Salary" {{ $disabled }} />
                                        <label for="base_salary">Base Salary</label>
                                        <span class="text-danger" id="base_salary-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="bonus_eligibility" name="bonus_eligibility"
                                            {{ $disabled }}>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <label for="bonus_eligibility">Bonus Eligibility</label>
                                        <span class="text-danger" id="bonus_eligibility-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="pay_grade" name="pay_grade"
                                            placeholder="Pay Grade" {{ $disabled }} />
                                        <label for="pay_grade">Pay Grade </label>
                                        <span class="text-danger" id="pay_grade-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="currency" name="currency"
                                            placeholder="Currency" {{ $disabled }} />
                                        <label for="currency">Currency</label>
                                        <span class="text-danger" id="currency-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="payment_mode" name="payment_mode"
                                            {{ $disabled }}>
                                        </select>
                                        <label for="payment_mode">Payment Mode</label>
                                        <span class="text-danger" id="payment_mode-error"></span>
                                    </div>
                                </div>

                            </div>
                    </div>
                    <h5 class="card-header mb-4">Bank Account Details</h5>
                    <div class="card-body">
                        <div class="row gy-4">
                            <div class="col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control onlyLetter" type="text" id="bank_name" name="bank_name"
                                        placeholder="Bank Name" />
                                    <label for="bank_name">Bank Name <span style="color:red">*</span></label>
                                    <span class="text-danger" id="bank_name-error"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control onlyLetter" type="text" id="account_name"
                                        name="account_name" placeholder="Account Holder Name" />
                                    <label for="account_name">Account Holder Name <span style="color:red">*</span></label>
                                    <span class="text-danger" id="account_name-error"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control onlyNumber" type="text" id="account_number"
                                        name="account_number" placeholder="Account Number" />
                                    <label for="account_number">Account Number <span style="color:red">*</span></label>
                                    <span class="text-danger" id="account_number-error"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="ifsc_code" name="ifsc_code"
                                        placeholder="IFSC Code" />
                                    <label for="ifsc_code">IFSC Code <span style="color:red">*</span></label>
                                    <span class="text-danger" id="ifsc_code-error"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-floating form-floating-outline">
                                    <input class="form-control" type="text" id="swift_code" name="swift_code"
                                        placeholder="SWIFT Code" />
                                    <label for="swift_code">SWIFT Code <span style="color:red">*</span></label>
                                    <span class="text-danger" id="swift_code-error"></span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center p-3 mt-5">
                                <button class="btn rounded-pill btn-outline-secondary me-2" type="button"
                                    data-bs-dismiss="offcanvas" onclick="window.history.back();">
                                    <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
                                </button>
                                <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light"
                                    id="submitButton">
                                    <span class="tf-icons mdi mdi-checkbox-marked-circle-outline me-1"></span>Submit
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->

    <script type="text/javascript">
        $(document).ready(function() {
            installInputFilters();
            loadEmployeeFinancialData();
            loadPaymentModeDropdown();
        });

        function installInputFilters() {
            $(".onlyNumber").inputFilter(function(value) {
                return /^\d*$/.test(value);
            }, "Must be an unsigned integer");

            $(".onlyLetter").inputFilter(function(value) {
                return /^[a-z]*$/i.test(value);
            }, "Must use alphabetic latin characters");
        }

        function loadPaymentModeDropdown(selectedValue = null) {
            let url = `{{ config('apiConstants.PROFILE_URLS.PAYMENT_MODE') }}`;
            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    let paymentMode = response.data;
                    let options = '<option value="">Select Payment Mode</option>';
                    for (let i = 0; i < paymentMode.length; i++) {
                        let selected = (selectedValue && selectedValue == paymentMode[i].id) ? 'selected' : '';
                        options +=
                            `<option value="${paymentMode[i].id}" ${selected}>${paymentMode[i].name}</option>`;
                    }
                    $("#payment_mode").html(options);
                }
            });
        }

        function loadEmployeeFinancialData() {
            let url =
                `{{ config('apiConstants.PROFILE_URLS.PROFILE') }}?id={{ request()->get('id') }}&Params='Financial'`;

            // Call AJAX function
            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data.employeeFinancial) {
                    populateFormFields(response.data.employeeFinancial);
                }
            });
        }

        function populateFormFields(financialData) {
            loadPaymentModeDropdown(financialData.payment_mode);
            $("#base_salary").val(financialData.base_salary);
            $("#bonus_eligibility").val(financialData.bonus_eligibility);
            $("#pay_grade").val(financialData.pay_grade);
            $("#currency").val(financialData.currency);
            $("#bank_name").val(financialData.bank_name);
            $("#account_name").val(financialData.account_name);
            $("#account_number").val(financialData.account_number);
            $("#ifsc_code").val(financialData.ifsc_code);
            $("#swift_code").val(financialData.swift_code);
        }

        $("#BankDetailsForm").validate({
            rules: {
                bank_name: {
                    required: true,
                    maxlength: 100,
                },
                account_name: {
                    required: true,
                    maxlength: 100,
                },
                account_number: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 20, // Modify based on account number length constraints
                },
                ifsc_code: {
                    required: true,
                    maxlength: 11, // Usually 11 characters for IFSC code
                },
                swift_code: {
                    required: true,
                    maxlength: 11, // Usually 11 characters for SWIFT code
                },
            },
            messages: {
                bank_name: {
                    required: "Bank name is required",
                    maxlength: "Bank name cannot be more than 100 characters",
                },
                account_name: {
                    required: "Account name is required",
                    maxlength: "Account name cannot be more than 100 characters",
                },
                account_number: {
                    required: "Account number is required",
                    digits: "Account number must contain only digits",
                    minlength: "Account number must be at least 10 digits long",
                    maxlength: "Account number cannot be more than 20 digits long",
                },
                ifsc_code: {
                    required: "IFSC code is required",
                    maxlength: "IFSC code must be 11 characters long",
                },
                swift_code: {
                    required: "SWIFT code is required",
                    maxlength: "SWIFT code must be 11 characters long",
                },
            },
            errorPlacement: function(error, element) {
                var errorId = element.attr("name") + "-error";
                $("#" + errorId).text(error.text());
                $("#" + errorId).show();
                element.addClass("is-invalid");
            },
            success: function(label, element) {
                var errorId = $(element).attr("name") + "-error";
                $("#" + errorId).text("");
                $(element).removeClass("is-invalid");
            },
            submitHandler: function(form) {
                event.preventDefault();

                const postData = collectFormData();
                const url =
                    `{{ config('apiConstants.PROFILE_URLS.BANK_DETAILS') }}/{{ request()->get('id') }}`;

                fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                    if (response.status === 200) {
                        ShowMsg("bg-success", response.message);
                    } else {
                        ShowMsg("bg-warning",
                            'The record could not be processed.');
                    }
                });
            }
        });

        function collectFormData() {
            return {
                user_uuid: $("#user_uuid").val(),
                bank_name: $("#bank_name").val(),
                account_name: $("#account_name").val(),
                account_number: $("#account_number").val(),
                ifsc_code: $("#ifsc_code").val(),
                swift_code: $("#swift_code").val(),
                base_salary: $("#base_salary").val(),
                bonus_eligibility: $("#bonus_eligibility").val(),
                pay_grade: $("#pay_grade").val(),
                currency: $("#currency").val(),
                payment_mode: $("#payment_mode").val(),
            };
        }
    </script>
@endsection
