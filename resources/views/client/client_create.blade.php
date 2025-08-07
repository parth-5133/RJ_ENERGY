<form action="javascript:void(0)" id="customerForm" name="customerForm" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="clientId" name="clientId" value="{{ $clientId ?? '' }}">

    <h5 class="fw-bold mb-3 mt-4">👤 Customer Basic Details</h5>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Name" />
                <label for="customer_name">Name <span class="text-danger">*</span></label>
                <span class="text-danger" id="customer_name-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="age" id="age" placeholder="Age" />
                <label for="age">Age <span class="text-danger">*</span></label>
                <span class="text-danger" id="age-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="gender" id="gender">
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <label for="gender">Gender <span class="text-danger">*</span></label>
                <span class="text-danger" id="gender-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="marital_status" id="marital_status">
                    <option value="">Select Marital Status</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                </select>
                <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                <span class="text-danger" id="marital_status-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="tel" class="form-control" name="mobile" id="mobile" maxlength="10"
                    placeholder="Aadhar-linked Mobile" />
                <label for="mobile">Aadhar-linked Mobile <span class="text-danger">*</span></label>
                <span class="text-danger" id="mobile-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="tel" class="form-control" name="alternate_mobile" id="alternate_mobile" maxlength="10"
                    placeholder="Alternate Mobile" />
                <label for="alternate_mobile">Alternate Mobile</label>
                <span class="text-danger" id="alternate_mobile-error"></span>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3 mt-4">🧾 Quotation</h5>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_" id="quotation_">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
                <label for="quotation_">Is Quotation <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4 quotation-dependent">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="quotation_amount" id="quotation_amount"
                    placeholder="Quotation Amount">
                <label for="quotation_amount">Quotation Amount <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_amount-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4 quotation-dependent">
            <div class="form-floating form-floating-outline">
                <input type="date" class="form-control" name="quotation_date" id="quotation_date"
                    placeholder="Quotation Date">
                <label for="quotation_date">Quotation Date <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_date-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_by" id="quotation_by">
                </select>
                <label for="quotation_by">Entered By <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_by-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_status" id="quotation_status">
                    <option value="Agreed">Agreed</option>
                    <option value="Pending">Pending</option>
                </select>
                <label for="quotation_status">Quotation Status <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_status-error"></span>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3 mt-4">☀️ Solar Details</h5>
    <div class="row">
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
                <span class="text-danger" id="roof_type-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="roof_area" id="roof_area"
                    placeholder="Roof Area" />
                <label for="roof_area">Roof Area (sq. ft) <span class="text-danger">*</span></label>
                <span class="text-danger" id="roof_area-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="usage_pattern" id="usage_pattern">
                    <option value="">Select Usage Pattern</option>
                    <option value="Domestic">Domestic</option>
                    <option value="Commercial">Commercial</option>
                </select>
                <label for="usage_pattern">Usage Pattern <span class="text-danger">*</span></label>
                <span class="text-danger" id="usage_pattern-error"></span>
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
                <span class="text-danger" id="solar_capacity-error"></span>
            </div>
        </div>
        <!-- Solar Company -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="solar_company" id="solar_company"
                    placeholder="Solar Company Name" />
                <label for="solar_company">Solar Company <span class="text-danger">*</span></label>
                <span class="text-danger" id="solar_company-error"></span>
            </div>
        </div>
        <!-- Inverter Company -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="inverter_company" id="inverter_company"
                    placeholder="Inverter Company Name" />
                <label for="inverter_company">Inverter Company <span class="text-danger">*</span></label>
                <span class="text-danger" id="inverter_company-error"></span>
            </div>
        </div>
    </div>
    <div class="row">

        <!-- Application Reference No -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="application_ref_no" id="application_ref_no"
                    placeholder="Application Reference No." />
                <label for="application_ref_no">Application Reference No. <span class="text-danger">*</span></label>
            </div>
        </div>
        <!-- Jan-Samarth ID -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="jan_samarth_id" id="jan_samarth_id"
                    placeholder="Jan-Samarth ID" />
                <label for="jan_samarth_id">Jan-Samarth ID <span class="text-danger">*</span></label>
                <span class="text-danger" id="jan_samarth_id-error"></span>
            </div>
        </div>
        <!-- Acknowledge No. -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="acknowledge_no" id="acknowledge_no"
                    placeholder="Acknowledgement No." />
                <label for="acknowledge_no">Acknowledge No. <span class="text-danger">*</span></label>
                <span class="text-danger" id="acknowledge_no-error"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Cancelled Cheque -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="file" class="form-control" name="cancel_cheque" id="cancel_cheque" />
                <label for="cancel_cheque">Cancelled Cheque (Upload)</label>
            </div>
        </div>
        <!-- Light Bill -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="file" class="form-control" name="light_bill" id="light_bill" />
                <label for="light_bill">Light Bill (Upload)</label>
            </div>
        </div>
        <!-- Light Bill No -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="light_bill_no" id="light_bill_no"
                    placeholder="Consumer No." />
                <label for="light_bill_no">Light Bill No <span class="text-danger">*</span></label>
                <span class="text-danger" id="light_bill_no-error"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Loan -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="loan_" id="loan_">
                    <option value="">Loan ?</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="loan_">Loan ? <span class="text-danger">*</span></label>
                <span class="text-danger" id="loan_-error"></span>
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
                <span class="text-danger" id="payment_mode-error"></span>
            </div>
        </div>
        <!-- Solar Total Amount -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="solar_total_amount" id="solar_total_amount"
                    placeholder="Total Amount" />
                <label for="solar_total_amount">Solar Total Amount (₹) <span class="text-danger">*</span></label>
                <span class="text-danger" id="solar_total_amount-error"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Total Received Amount -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="total_received_amount" id="total_received_amount"
                    placeholder="Total Received Amount" />
                <label for="total_received_amount">Total Received Amount (₹) <span
                        class="text-danger">*</span></label>
                <span class="text-danger" id="total_received_amount-error"></span>
            </div>
        </div>
        <!-- Date of Full Payment -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="date" class="form-control" name="date_full_payment" id="date_full_payment" />
                <label for="date_full_payment">Date of Full Payment </label>
                <span class="text-danger" id="date_full_payment-error"></span>
            </div>
        </div>
        <!-- Registration Date -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="date" class="form-control" name="registration_date" id="registration_date" />
                <label for="registration_date">Registration Date <span class="text-danger">*</span></label>
                <span class="text-danger" id="registration_date-error"></span>
            </div>
        </div>
        <!-- Channel Partner -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="channel_partner" id="channel_partner">
                    <option value="">Select Channel Partner</option>
                    <!-- Dynamic options -->
                </select>
                <label for="channel_partner">Channel Partner <span class="text-danger">*</span></label>
                <span class="text-danger" id="channel_partner-error"></span>
            </div>
        </div>

    </div>
    <div class="row">
        <!-- Customer Address -->
        <div class="col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
                <textarea class="form-control" name="customer_address" id="customer_address" placeholder="Enter Address"
                    style="height: 100px;"></textarea>
                <label for="customer_address">Permanent Address <span class="text-danger">*</span></label>
                <span class="text-danger" id="customer_address-error"></span>
            </div>
        </div>
        <!-- Residential Address -->
        <div class="col-md-6 mb-4">
            <div class="form-floating form-floating-outline">
                <textarea class="form-control" name="customer_residential_address" id="customer_residential_address"
                    placeholder="Enter Address" style="height: 100px;"></textarea>
                <label for="customer_residential_address">Residential Address <span
                        class="text-danger">*</span></label>
                <span class="text-danger" id="customer_residential_address-error"></span>
            </div>
        </div>
    </div>
    <!-- Section: 💰 Subsidy Info -->
    <h5 class="fw-bold mb-3 mt-4">💰 Subsidy Info</h5>
    <div class="row">
        <!-- Subsidy Amount -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="subsidy_amount" id="subsidy_amount"
                    placeholder="Subsidy Amount" />
                <label for="subsidy_amount">Subsidy Amount (₹)</label>
            </div>
        </div>
        <!-- Subsidy Status -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="subsidy_status" id="subsidy_status">
                    <option value="">Select Subsidy Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Received">Received</option>
                </select>
                <label for="subsidy_status">Subsidy Status</label>
            </div>
        </div>
    </div>
    <!-- Consumer Bank Details Section -->
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
    <!-- Loan Bank Details Section -->
    <div id="loanBankDetailsSection" class="mb-4">
        <h6 class="fw-bold mb-3">🏦 Loan Applicants Bank Details</h6>
        <div class="row">
            <!-- Bank Name -->
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <select class="form-select" name="bank_name_loan" id="bank_name_loan">
                        <option value="">Select Bank</option>
                        <!-- Dynamic options -->
                    </select>
                    <label for="bank_name_loan">Bank Name <span style="color:red">*</span></label>
                    <span class="text-danger" id="bank_name_loan-error"></span>
                </div>
            </div>
            <!-- Branch -->
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="bank_branch_loan" id="bank_branch_loan"
                        placeholder="Branch">
                    <label for="bank_branch_loan">Branch <span class="text-danger">*</span></label>
                    <span class="text-danger" id="bank_branch_loan-error"></span>
                </div>
            </div>
            <!-- Account Number -->
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
            <!-- IFSC Code -->
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
                        id="branch_manager_phone_loan" placeholder="Branch Manager Phone">
                    <label for="branch_manager_phone_loan">Branch Manager Phone <span
                            style="color:red">*</span></label>
                    <span class="text-danger" id="branch_manager_phone_loan-error"></span>
                </div>
            </div>
            <!-- Loan Manager Phone -->
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="text" class="form-control" name="loan_manager_phone_loan"
                        id="loan_manager_phone_loan" placeholder="Loan Manager Phone">
                    <label for="loan_manager_phone_loan">Loan Manager Phone <span style="color:red">*</span></label>
                    <span class="text-danger" id="loan_manager_phone_loan-error"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Loan Status -->
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <select class="form-select" name="loan_status" id="loan_status">
                        <option value="">Select Loan Status</option>
                        <option value="Sanctioned">Sanctioned</option>
                        <option value="Disbursed">Disbursed</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Pending">Approved</option>
                    </select>
                    <label for="loan_status">Loan Status <span class="text-danger">*</span></label>
                    <span class="text-danger" id="loan_status-error"></span>
                </div>
            </div>
            <!-- Loan Sanction Date -->
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="date" class="form-control" name="loan_sanction_date" id="loan_sanction_date" />
                    <label for="loan_sanction_date">Loan Sanction Date</label>
                    <span class="text-danger" id="loan_sanction_date-error"></span>
                </div>
            </div>
            <!-- Loan Disbursal Date -->
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <input type="date" class="form-control" name="loan_disbursal_date"
                        id="loan_disbursal_date" />
                    <label for="loan_disbursal_date">Loan Disbursal Date</label>
                    <span class="text-danger" id="loan_disbursal_date-error"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Managed By -->
            <div class="col-md-4 mb-4">
                <div class="form-floating form-floating-outline">
                    <select class="form-select" name="managed_by" id="managed_by">
                        <option value="">Select Accountant</option>
                        <!-- Example dynamic options -->
                        <option value="Accountant1">Accountant 1</option>
                        <option value="Accountant2">Accountant 2</option>
                        <option value="Accountant3">Accountant 3</option>
                    </select>
                    <label for="managed_by">Managed By <span class="text-danger">*</span></label>
                    <span class="text-danger" id="managed_by-error"></span>
                </div>
            </div>
        </div>
    </div>
    <!-- INSTALLATION Details Section -->
    <h5 class="fw-bold mb-3 mt-4">🔧 Installation Details</h5>
    <div class="row">
        <!-- Installers -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="installers" id="installers">
                    <option value="">Select Installers</option>
                </select>
                <label for="installers">Installers</label>
                <span class="text-danger" id="installers-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="date" class="form-control" name="installation_date" id="installation_date">
                <label for="installation_date">Installation Date</label>
                <span class="text-danger" id="installation_date-error"></span>
            </div>
        </div>
    </div>
    <!-- Section: 📌 Application Status -->
    <h5 class="fw-bold mb-3 mt-4">📌 Application Status</h5>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_completed" id="is_completed">
                <label class="form-check-label" for="is_completed">Completed Fitting</label>
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

        $('#loanBankDetailsSection').hide();

        $('#payment_mode').change(function() {
            const selected = $(this).val();

            if (selected === 'loan') {
                $('#loanBankDetailsSection').slideDown();
            } else {
                $('#loanBankDetailsSection').slideUp();
            }
        });

        // Declare a variable to hold bank data
        var bankDataMap = {};
        var bankDataMap2 = {};

        // Load banks via AJAX
        fnCallAjaxHttpGetEvent("{{ config('apiConstants.MANAGE_BANK_URLS.MANAGE_BANK') }}", null, true, true,
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
        fnCallAjaxHttpGetEvent("{{ config('apiConstants.INSTALLERS_URLS.INSTALLERS') }}", null, true, true,
            function(response) {
                if (response.status === 200 && response.data) {
                    // Populate existing installers dropdown
                    var $Dropdown = $("#installers");
                    $Dropdown.empty();
                    $Dropdown.append(new Option('Select Installers', ''));

                    // Populate new installer_id dropdown for installation details
                    var $InstallerIdDropdown = $("#installer_id");
                    $InstallerIdDropdown.empty();
                    $InstallerIdDropdown.append(new Option('Select Installer', ''));

                    response.data.forEach(function(data) {
                        $Dropdown.append(new Option(data.name, data.id));
                        $InstallerIdDropdown.append(new Option(data.name, data.id));
                    });
                }
            });

        fnCallAjaxHttpGetEvent("{{ config('apiConstants.QUOTATION_URLS.QUOTATION_ALL_ACCOUNTANT') }}", null,
            true, true,
            function(response) {
                if (response.status === 200 && response.data) {
                    var options = '<option selected disabled value="">Select</option>';
                    $.each(response.data, function(index, accountant) {
                        options += '<option value="' + accountant.id + '">' + accountant.full_name +
                            '</option>';
                    });
                    $("#quotation_by").html(options);
                    $("#managed_by").html(options);
                } else {
                    console.log('Failed to retrieve accountant data.');
                }
            });

        if (clientId > 0) {
            var Url = "{{ config('apiConstants.QUOTATION_URLS.QUOTATION_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                quotesId: clientId,
                is_customer: 1
            }, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    $("#customer_name").val(response.data.customer_name);
                    $("#age").val(response.data.age);
                    $("#mobile").val(response.data.mobile);
                    $("#alternate_mobile").val(response.data.alternate_mobile);
                    $("#quotation_").val(response.data.required);
                    $("#solar_capacity").val(response.data.capacity);
                    $("#roof_area").val(response.data.roof_area);
                    $("#quotation_amount").val(response.data.amount);
                    $("#quotation_date").val(response.data.date);
                    $("#quotation_by").val(response.data.by);
                    $("#quotation_status").val(response.data.status);
                } else {
                    console.log('Failed to retrieve role data.');
                }
            });
        }

        if (clientId > 0) {
            var Url = "{{ config('apiConstants.CLIENT_URLS.CLIENT_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                customerId: clientId
            }, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    // Solar detail data
                    if (response.data.solar_detail) {
                        $("#roof_type").val(response.data.solar_detail.roof_type);
                        $("#roof_area").val(response.data.solar_detail.roof_area);
                        $("#usage_pattern").val(response.data.solar_detail.usage_pattern);
                        $("#solar_capacity").val(response.data.solar_detail.capacity);
                        $("#solar_company").val(response.data.solar_detail.solar_company);
                        $("#inverter_company").val(response.data.solar_detail.inverter_company);
                        $("#installers").val(response.data.solar_detail.installers);
                        $("#jan_samarth_id").val(response.data.solar_detail.jan_samarth_id);
                        $("#acknowledge_no").val(response.data.solar_detail.acknowledge_no);
                        $("#loan_").val(response.data.solar_detail.loan_required);
                        $("#payment_mode").val(response.data.solar_detail.payment_mode);
                        $("#light_bill_no").val(response.data.solar_detail.consumer_no);
                        $("#application_ref_no").val(response.data.solar_detail.application_ref_no);
                        $("#channel_partner").val(response.data.solar_detail.channel_partner_id);
                        $("#registration_date").val(response.data.solar_detail.registration_date);
                        $("#solar_total_amount").val(response.data.solar_detail.solar_total_amount);
                        $("#customer_address").val(response.data.solar_detail.customer_address);
                        $("#customer_residential_address").val(response.data.solar_detail
                            .customer_residential_address);
                        $("#is_completed").prop("checked", response.data.solar_detail.is_completed);
                    }

                    // Subsidy data
                    if (response.data.subsidy) {
                        $("#subsidy_amount").val(response.data.subsidy.subsidy_amount);
                        $("#subsidy_status").val(response.data.subsidy.subsidy_status);
                    }

                    // Customer bank detail data
                    if (response.data.customer_bank_detail) {
                        $("#bank_name").val(response.data.customer_bank_detail.bank_name);
                        $("#bank_branch").val(response.data.customer_bank_detail.bank_branch);
                        $("#account_number").val(response.data.customer_bank_detail.account_number);
                        $("#ifsc_code").val(response.data.customer_bank_detail.ifsc_code);
                    }

                    // Loan bank detail data
                    if (response.data.loan_bank_detail) {
                        $("#bank_name_loan").val(response.data.loan_bank_detail.bank_name);
                        $("#bank_branch_loan").val(response.data.loan_bank_detail.bank_branch);
                        $("#account_number_loan").val(response.data.loan_bank_detail.account_number);
                        $("#ifsc_code_loan").val(response.data.loan_bank_detail.ifsc_code);
                        $("#branch_manager_phone_loan").val(response.data.loan_bank_detail
                            .branch_manager_phone);
                        $("#loan_manager_phone_loan").val(response.data.loan_bank_detail
                            .loan_manager_phone);
                        $("#loan_status").val(response.data.loan_bank_detail.loan_status);
                    }
                } else {
                    console.log('Failed to retrieve client data.');
                }
            });
        }
    });

    // jQuery Validation Setup
    $("#customerForm").validate({
        rules: {
            customer_name: {
                required: true
            },
            age: {
                required: true,
                digits: true,
                minlength: 1,
                maxlength: 3
            },
            gender: {
                required: true
            },
            marital_status: {
                required: true
            },
            alternate_mobile: {
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            roof_type: {
                required: true
            },
            roof_area: {
                required: true,
                number: true
            },
            usage_pattern: {
                required: true
            },
            solar_capacity: {
                required: true
            },
            solar_company: {
                required: true
            },
            inverter_company: {
                required: true
            },
            customer_address: {
                required: true
            },
            customer_residential_address: {
                required: true
            },
            light_bill_no: {
                required: true
            },
            channel_partner: {
                required: true
            },
            registration_date: {
                required: true,
                dateISO: true
            },
            solar_total_amount: {
                required: true,
                number: true
            },
            installers: {
                required: false
            },
            jan_samarth_id: {
                required: true
            },
            loan_: {
                required: true
            },
            payment_mode: {
                required: true
            },
            bank_name: {
                required: true
            },
            bank_branch: {
                required: true
            },
            account_number: {
                required: true,
                digits: true
            },
            ifsc_code: {
                required: true,
                minlength: 11,
                maxlength: 11
            },
            total_received_amount: {
                required: true,
                number: true
            },
            acknowledge_no: {
                required: true
            },
            acknowledge_no: {
                required: true
            },
            application_ref_no: {
                required: true
            },
            managed_by: {
                required: true
            },
        },
        messages: {
            customer_name: {
                required: "Name is required"
            },
            age: {
                required: "Age is required",
                digits: "Please enter a valid age",
                minlength: "Age must be at least 1 year old",
                maxlength: "Age cannot exceed 3 digits"
            },
            gender: {
                required: "Gender is required."
            },
            marital_status: {
                required: "Marital status is required."
            },
            alternate_mobile: {
                digits: "Please enter a valid mobile number",
                minlength: "Mobile number must be at least 10 digits long",
                maxlength: "Mobile number must be at most 15 digits long"
            },
            mobile: {
                required: "Mobile is required",
                digits: "Please enter a valid mobile number",
                minlength: "Mobile number must be at least 10 digits long",
                maxlength: "Mobile number must be at most 15 digits long"
            },
            roof_type: {
                required: "Please select a roof type."
            },
            roof_area: {
                required: "Roof area is required.",
                number: "Please enter a valid number."
            },
            usage_pattern: {
                required: "Please select a usage pattern."
            },
            solar_capacity: {
                required: "Solar capacity is required."
            },
            solar_company: {
                required: "Solar company name is required."
            },
            inverter_company: {
                required: "Inverter company name is required."
            },
            customer_address: {
                required: "Customer address is required."
            },
            light_bill_no: {
                required: "Light bill number is required."
            },
            channel_partner: {
                required: "Channel partner selection is required."
            },
            registration_date: {
                required: "Registration date is required.",
                dateISO: "Please enter a valid date."
            },
            solar_total_amount: {
                required: "Total amount is required.",
                number: "Please enter a valid number."
            },
            bank_name: {
                required: "Bank name is required."
            },
            bank_branch: {
                required: "Bank branch is required."
            },
            account_number: {
                required: "Account number is required.",
                digits: "Please enter only digits."
            },
            ifsc_code: {
                required: "IFSC code is required.",
                minlength: "IFSC code must be 11 characters long.",
                maxlength: "IFSC code must be 11 characters long."
            },
            customer_residential_address: {
                required: "Residential address is required."
            },
            jan_samarth_id: {
                required: "Jan-Samarth ID is required."
            },
            loan_: {
                required: "Please specify if loan is applicable."
            },
            payment_mode: {
                required: "Payment mode is required."
            },
            installers: {
                required: "Installers selection is required."
            },
            total_received_amount: {
                required: "Total received amount is required.",
                number: "Please enter a valid number."
            },
            acknowledge_no: {
                required: "Acknowledgement number is required."
            },
            application_ref_no: {
                required: "Application reference number is required."
            },
            managed_by: {
                required: "Managed by is required."
            }
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

            var formData = new FormData(form);
            formData.append('is_completed', $("#is_completed").is(":checked") ? 1 : 0);

            var storeUrl = "{{ config('apiConstants.CLIENT_URLS.CLIENT_STORE') }}";
            var updateUrl = "{{ config('apiConstants.CLIENT_URLS.CLIENT_UPDATE') }}";
            var url = clientId > 0 ? updateUrl : storeUrl;
            fnCallAjaxHttpPostEventWithoutJSON(url, formData, true, true, function(response) {
                if (response.status === 200) {
                    bootstrap.Offcanvas.getInstance(document.getElementById('commonOffcanvas'))
                        .hide();
                    $('#grid').DataTable().ajax.reload();
                    ShowMsg("bg-success", response.message);
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        }
    });
</script>
