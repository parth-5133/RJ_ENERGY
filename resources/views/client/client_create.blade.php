<form action="javascript:void(0)" id="customerForm" name="customerForm" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="clientId" value="{{ $clientId ?? '' }}">

    <!-- Section 1: Customer Basic Details -->
    <h5 class="fw-bold mb-3 mt-4">👤 Customer Basic Details</h5>
    <div class="row">
        <!-- Name -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Name" />
                <label for="customer_name">Name <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Age -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="age" id="age" placeholder="Age" />
                <label for="age">Age <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Mobile -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="tel" class="form-control" name="mobile" id="mobile" maxlength="10"
                    placeholder="Aadhar-linked Mobile" />
                <label for="mobile">Aadhar-linked Mobile <span class="text-danger">*</span></label>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Alternate Mobile -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="tel" class="form-control" name="alternate_mobile" id="alternate_mobile" maxlength="10"
                    placeholder="Alternate Mobile" />
                <label for="alternate_mobile">Alternate Mobile</label>
            </div>
        </div>
        <!-- Aadhar -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="aadhar" id="aadhar" placeholder="Aadhar Number" />
                <label for="aadhar">Aadhar Number <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- PAN -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="pan" id="pan" placeholder="PAN Number" />
                <label for="pan">PAN Number</label>
            </div>
        </div>
    </div>
    <!-- Section 3: 🧾 Quotation – By Accountant OR Registrar -->
    <h5 class="fw-bold mb-3 mt-4">🧾 Quotation</h5>
    <div class="row">
        <!-- Quotation  -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_" id="quotation_">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="quotation_">Is Quotation <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Quotation Amount -->
        <div class="col-md-4 mb-4 quotation-dependent">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="quotation_amount" id="quotation_amount"
                    placeholder="Quotation Amount">
                <label for="quotation_amount">Quotation Amount</label>
            </div>
        </div>
        <!-- Quotation Date -->
        <div class="col-md-4 mb-4 quotation-dependent">
            <div class="form-floating form-floating-outline">
                <input type="date" class="form-control" name="quotation_date" id="quotation_date"
                    placeholder="Quotation Date">
                <label for="quotation_date">Quotation Date</label>
            </div>
        </div>
    </div>
    <div class="row quotation-dependent">
        <!-- Entered By -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_by" id="quotation_by" aria-label="Entered By">
                    <option selected disabled value="">Select</option>
                    <option value="Accountant">John Smith</option>
                    <option value="Registrar">Jane Doe</option>
                </select>
                <label for="quotation_by">Entered By</label>
            </div>
        </div>
        <!-- Quotation Status -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_status" id="quotation_status">
                    <option value="">Select Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Agreed">Agreed</option>
                </select>
                <label for="quotation_status">Quotation Status</label>
            </div>
        </div>
    </div>
    <!-- Section 4: Solar Details -->
    <h5 class="fw-bold mb-3 mt-4">☀️ Solar Details</h5>
    <div class="row">
        <!-- Roof Type -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="roof_type" id="roof_type">
                    <option value="">Select Roof Type</option>
                    <option value="RCC">RCC</option>
                    <option value="Tin">Tin</option>
                    <option value="Asbestos">Asbestos</option>
                    <option value="Other">Other</option>
                </select>
                <label for="roof_type">Roof Type <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Roof Area -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="roof_area" id="roof_area"
                    placeholder="Roof Area" />
                <label for="roof_area">Roof Area (sq. ft) <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Usage Pattern -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="usage_pattern" id="usage_pattern">
                    <option value="">Select Usage Pattern</option>
                    <option value="Domestic">Domestic</option>
                    <option value="Commercial">Commercial</option>
                </select>
                <label for="usage_pattern">Usage Pattern <span class="text-danger">*</span></label>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Capacity -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="solar_capacity" id="solar_capacity"
                    placeholder="Capacity (e.g. 3KW)" />
                <label for="solar_capacity">Capacity (e.g. 3KW)</label>
            </div>
        </div>
        <!-- Solar Company -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="solar_company" id="solar_company"
                    placeholder="Solar Company Name" />
                <label for="solar_company">Solar Company <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Inverter Company -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="inverter_company" id="inverter_company"
                    placeholder="Inverter Company Name" />
                <label for="inverter_company">Inverter Company <span class="text-danger">*</span></label>
            </div>
        </div>

    </div>
    <div class="row">
        <!-- Subsidy -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="subsidy_claimed" id="subsidy_claimed">
                    <option value="">Select Option</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="subsidy_claimed">Is Subsidy to be claimed? <span class="text-danger">*</span></label>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="jan_samarth_id" id="jan_samarth_id"
                    placeholder="Jan-Samarth ID" />
                <label for="jan_samarth_id">Jan-Samarth ID</label>
            </div>
        </div>
        <!-- Acknowledge No. -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="acknowledge_no" id="acknowledge_no"
                    placeholder="Acknowledgement No." />
                <label for="acknowledge_no">Acknowledge No.</label>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Loan  -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="loan_" id="loan_">
                    <option value="">Loan ?</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="loan_">Loan ? <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Payment Mode -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="payment_mode" id="payment_mode">
                    <option value="">Select Payment Mode</option>
                    <option value="cash">Cash</option>
                    <option value="loan">Loan</option>
                </select>
                <label for="payment_mode">Payment Mode <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Cancelled Cheque -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="file" class="form-control" name="cancel_cheque" id="cancel_cheque" />
                <label for="cancel_cheque">Cancelled Cheque (Upload)</label>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Light Bill -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="file" class="form-control" name="light_bill" id="light_bill" />
                <label for="light_bill">Light Bill (Upload)</label>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="consumer_no" id="consumer_no"
                    placeholder="Consumer No." />
                <label for="consumer_no">Light Bill No. / Consumer No. <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Application Reference No -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="application_ref_no" id="application_ref_no"
                    placeholder="Application Reference No." />
                <label for="application_ref_no">Application Reference No.</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="channel_partner" id="channel_partner">
                    <option value="">Select Channel Partner</option>
                    <!-- Dynamic options -->
                </select>
                <label for="channel_partner">Channel Partner <span class="text-danger">*</span></label>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="date" class="form-control" name="registration_date" id="registration_date" />
                <label for="registration_date">Registration Date <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Solar Total Amount -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="solar_total_amount" id="solar_total_amount"
                    placeholder="Total Amount" />
                <label for="solar_total_amount">Solar Total Amount (₹) <span class="text-danger">*</span></label>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Customer Address -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="installers" id="installers">
                    <option value="">Select Installers</option>
                </select>
                <label for="installers">Installers<span class="text-danger">*</span></label>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Customer Address -->
        <div class="col-md-8 mb-4">
            <div class="form-floating form-floating-outline">
                <textarea class="form-control" name="customer_address" id="customer_address" placeholder="Enter Address"
                    style="height: 100px;"></textarea>
                <label for="customer_address">Customer Address (Current & Residential) <span
                        class="text-danger">*</span></label>
            </div>
        </div>
    </div>
    <!-- Consumer Bank Details Section  -->
    <div id="bankDetailsSection" class="mb-4">
        <h6 class="fw-bold mb-3">🏦 Customer Bank Details</h6>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <select class="form-select" name="bank_name" id="bank_name">
                        <option value="">Select Bank</option>
                    </select>
                    <label for="bank_name">Bank Name <span style="color:red">*</span></label>
                    <span class="text-danger" id="bank_name-error"></span>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="bank_branch" id="bank_branch"
                        placeholder="Branch">
                    <label for="bank_branch">Branch <span class="text-danger">*</span></label>
                    <span class="text-danger" id="bank_branch-error"></span>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="account_number" id="account_number"
                        placeholder="Account Number">
                    <label for="account_number">Account Number <span class="text-danger">*</span></label>
                    <span class="text-danger" id="account_number-error"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="ifsc_code" id="ifsc_code"
                        placeholder="IFSC Code">
                    <label for="ifsc_code">IFSC Code <span class="text-danger">*</span></label>
                    <span class="text-danger" id="ifsc_code-error"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Loan Bank Details Section  -->
    <div id="bankDetailsSection" class="mb-4">
        <h6 class="fw-bold mb-3">🏦 Loan Applicants Bank Details</h6>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <select class="form-select" name="bank_name_loan" id="bank_name_loan">
                        <option value="">Select Bank</option>
                    </select>
                    <label for="bank_name_loan">Bank Name <span style="color:red">*</span></label>
                    <span class="text-danger" id="bank_name_loan-error"></span>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="bank_branch_loan" id="bank_branch_loan"
                        placeholder="Branch">
                    <label for="bank_branch_loan">Branch <span class="text-danger">*</span></label>
                    <span class="text-danger" id="bank_branch_loan-error"></span>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="account_number_loan" id="account_number_loan"
                        placeholder="Account Number">
                    <label for="account_number_loan">Account Number <span class="text-danger">*</span></label>
                    <span class="text-danger" id="account_number_loan-error"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="ifsc_code_loan" id="ifsc_code_loan"
                        placeholder="IFSC Code">
                    <label for="ifsc_code_loan">IFSC Code <span class="text-danger">*</span></label>
                    <span class="text-danger" id="ifsc_code_loan-error"></span>
                </div>
            </div>
            <!-- Branch Manager Phone -->
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="branch_manager_phone_loan"
                        id="branch_manager_phone_loan" placeholder="Branch Manager Phone" />
                    <label for="branch_manager_phone_loan">Branch Manager Phone <span
                            style="color:red">*</span></label>
                    <span class="text-danger" id="branch_manager_phone_loan-error"></span>
                </div>
            </div>

            <!-- Loan Manager Phone -->
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="loan_manager_phone_loan"
                        id="loan_manager_phone_loan" placeholder="Loan Manager Phone" />
                    <label for="loan_manager_phone_loan">Loan Manager Phone <span style="color:red">*</span></label>
                    <span class="text-danger" id="loan_manager_phone_loan-error"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Section: 📌 Application Status -->
    <h5 class="fw-bold mb-3 mt-4">📌 Application Status</h5>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="status_subsidy_approved"
                    id="status_subsidy_approved">
                <label class="form-check-label" for="status_subsidy_approved">Subsidy Approved</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="status_loan_approved"
                    id="status_loan_approved">
                <label class="form-check-label" for="status_loan_approved">Loan Approved</label>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="status_completed_fitting"
                    id="status_completed_fitting">
                <label class="form-check-label" for="status_completed_fitting">Completed Fitting</label>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
        <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas">
            <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
        </button>
        <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
            <span class="tf-icons mdi mdi-checkbox-marked-circle-outline"></span>&nbsp;Submit
        </button>
    </div>
</form>
<script type="text/javascript">
    var clientId = $("#clientId").val();
    $(document).ready(function() {
        // Declare a variable to hold bank data
        var bankDataMap = {};
        var bankDataMap2 = {};
        // Load banks via AJAX
        fnCallAjaxHttpGetEvent("{{ config('apiConstants.MANAGE_BANK_URLS.MANAGE_BANK') }}", null,
            true, true,
            function(response) {
                if (response.status === 200 && response.data) {
                    var $Dropdown = $("#bank_name");
                    var $Dropdown2 = $("#bank_name_loan");
                    $Dropdown.empty();
                    $Dropdown2.empty();
                    $Dropdown.append(new Option('Select Bank', ''));
                    $Dropdown2.append(new Option('Select Bank', ''));

                    // Save bank data by ID
                    response.data.forEach(function(bank) {
                        bankDataMap[bank.id] = bank;
                        $Dropdown.append(new Option(bank.bank_name, bank.id));
                    });

                    // Save bank data by ID
                    response.data.forEach(function(bank) {
                        bankDataMap2[bank.id] = bank;
                        $Dropdown2.append(new Option(bank.bank_name, bank.id));
                    });
                }
            });

        $("#bank_name").on("change", function() {
            var selectedBankId = $(this).val();
            if (selectedBankId && bankDataMap[selectedBankId]) {
                var bank = bankDataMap[selectedBankId];
                $("#bank_branch").val(bank.branch_name || '');
                $("#ifsc_code").val(bank.ifsc_code || '');
            } else {
                // Reset fields if nothing selected
                $("#bank_branch").val('');
                $("#ifsc_code").val('');
            }
        });
        $("#bank_name_loan").on("change", function() {
            var selectedBankId = $(this).val();
            if (selectedBankId && bankDataMap[selectedBankId]) {
                var bank = bankDataMap[selectedBankId];
                $("#bank_branch_loan").val(bank.branch_name || '');
                $("#ifsc_code_loan").val(bank.ifsc_code || '');
                $("#branch_manager_phone_loan").val(bank.branch_manager_phone || '');
                $("#loan_manager_phone_loan").val(bank.loan_manager_phone || '');
            } else {
                // Reset fields if nothing selected
                $("#bank_branch_loan").val('');
                $("#ifsc_code_loan").val('');
                $("#branch_manager_phone_loan").val('');
                $("#loan_manager_phone_loan").val('');
            }
        });

        // Load Channel Partner via AJAX
        fnCallAjaxHttpGetEvent("{{ config('apiConstants.CHANNEL_PARTNERS_URLS.CHANNEL_PARTNERS') }}", null,
            true, true,
            function(response) {
                if (response.status === 200 && response.data) {
                    var $Dropdown = $("#channel_partner");
                    $Dropdown.empty();
                    $Dropdown.append(new Option('Select Channel Partner', ''));

                    response.data.forEach(function(data) {
                        $Dropdown.append(new Option(data.legal_name, data.id));
                    });
                }
            });

        // Load Installers via AJAX
        fnCallAjaxHttpGetEvent("{{ config('apiConstants.INSTALLERS_URLS.INSTALLERS') }}", null,
            true, true,
            function(response) {
                if (response.status === 200 && response.data) {
                    var $Dropdown = $("#installers");
                    $Dropdown.empty();
                    $Dropdown.append(new Option('Select Installers', ''));

                    response.data.forEach(function(data) {
                        $Dropdown.append(new Option(data.name, data.id));
                    });
                }
            });
    });

    // jQuery Validation Setup
    $("#customerForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
            },
            age: {
                required: true,
                digits: true,
                minlength: 1,
                maxlength: 3
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            alternate_mobile: {
                required: false,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            aadhar: {
                required: true,
                digits: true,
                minlength: 12,
                maxlength: 12
            },
            pan: {
                required: true,
                minlength: 10,
                maxlength: 10
            },
            quotation_: {
                required: true,
            },
            quotation_amount: {
                required: true,
                number: true,
            },
            quotation_date: {
                required: true,
                date: true,
            },
            quotation_status: {
                required: true,
            },
            quotation_by: {
                required: true,
            }
        },
        messages: {
            name: {
                required: "Name is required ",
                maxlength: "Name cannot be more than 50 characters",
            },
            age: {
                required: "Age is required ",
                digits: "Please enter a valid age",
                minlength: "Age must be at least 1 year old",
                maxlength: "Age cannot exceed 3 digits",
            },
            mobile: {
                required: "Mobile is required ",
                digits: "Please enter a valid mobile number",
                minlength: "Mobile number must be at least 10 digits long",
                maxlength: "Mobile number must be at most 15 digits long"
            },
            alternate_mobile: {
                digits: "Please enter a valid mobile number",
                minlength: "Mobile number must be at least 10 digits long",
                maxlength: "Mobile number must be at most 15 digits long"
            },
            aadhar: {
                required: "Aadhar is required ",
                digits: "Please enter a valid Aadhar number",
                minlength: "Aadhar number must be 12 digits long",
                maxlength: "Aadhar number must be 12 digits long"
            },
            pan: {
                required: "PAN is required ",
                minlength: "PAN number must be 10 characters long",
                maxlength: "PAN number must be 10 characters long"
            },
            quotation_: {
                required: "Quotation  is required ",
            },
            quotation_amount: {
                required: "Quotation Amount is required ",
                number: "Please enter a valid number",
            },
            quotation_date: {
                required: "Quotation Date is required ",
                date: "Please enter a valid date",
            },
            quotation_status: {
                required: "Quotation Status is required ",
            },
            quotation_by: {
                required: "Quotation By is required ",
            }
        },
        errorPlacement: function(error, element) {
            var errorId = element.attr("name") +
                "-error";
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

            var formData = new FormData(form);

            var storeUrl = "{{ config('apiConstants.CLIENT_URLS.CLIENT_STORE') }}";
            var updateUrl = "{{ config('apiConstants.CLIENT_URLS.CLIENT_UPDATE') }}";
            var url = clientId > 0 ? updateUrl : storeUrl;
            fnCallAjaxHttpPostEventWithoutJSON(url, formData, true, true, function(response) {
                if (response.status === 200) {
                    bootstrap.Offcanvas.getInstance(document.getElementById(
                        'commonOffcanvas')).hide();
                    $('#grid').DataTable().ajax.reload();
                    ShowMsg("bg-success", response.message);
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        }
    });
</script>
