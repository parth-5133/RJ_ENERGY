<!-- Tabs Navigation -->

<ul class="nav nav-tabs mb-4" id="taskTabs" role="tablist" style="display: none;">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#tabDetails" type="button"
            role="tab" aria-controls="tabDetails" aria-selected="true">
            Step 1: Proposal
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="statuslog-tab" data-bs-toggle="tab" data-bs-target="#tabStatusLog" type="button"
            role="tab" aria-controls="tabStatusLog" aria-selected="false">
            Step 2: Documents
        </button>
    </li>
</ul>

<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <div class="tab-content" id="taskTabsContent">
        <!-- Step 1: Proposal -->
        <div class="tab-pane fade show active" id="tabDetails" role="tabpanel" aria-labelledby="details-tab">
            <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" name="solar_capacity" id="solar_capacity"
                    placeholder="Solar Capacity" required>
                <label for="solar_capacity">Solar Capacity <span class="text-danger">*</span></label>
                <span class="text-danger" id="solar_capacity-error"></span>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="roof_type" id="roof_type" required>
                    <option value="">Select Roof Type</option>
                    <option value="RCC">RCC</option>
                    <option value="Tin">Tin</option>
                    <option value="Asbestos">Asbestos</option>
                    <option value="Other">Other</option>
                </select>
                <label for="roof_type">Roof Type <span class="text-danger">*</span></label>
                <span class="text-danger" id="roof_type-error"></span>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <input type="number" class="form-control" name="roof_area" id="roof_area" placeholder="Roof Area"
                    required>
                <label for="roof_area">Roof Area (sq. ft) <span class="text-danger">*</span></label>
                <span class="text-danger" id="roof_area-error"></span>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="usage_pattern" id="usage_pattern" required>
                    <option value="">Select Usage Pattern</option>
                    <option value="Domestic">Domestic</option>
                    <option value="Commercial">Commercial</option>
                </select>
                <label for="usage_pattern">Usage Pattern <span class="text-danger">*</span></label>
                <span class="text-danger" id="usage_pattern-error"></span>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="net_metering" id="net_metering" required>
                    <option value="">Select Option</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="net_metering">Net Metering Used? <span class="text-danger">*</span></label>
                <span class="text-danger" id="net_metering-error"></span>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="subsidy_claimed" id="subsidy_claimed" required>
                    <option value="">Select Option</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="subsidy_claimed">Is Subsidy to be claimed? <span class="text-danger">*</span></label>
                <span class="text-danger" id="subsidy_claimed-error"></span>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="purchase_mode" id="purchase_mode" required>
                    <option value="">Select Payment Mode</option>
                    <option value="Cash">Cash</option>
                    <option value="Credit">Credit</option>
                </select>
                <label for="purchase_mode">Purchase Mode <span class="text-danger">*</span></label>
                <span class="text-danger" id="purchase_mode-error"></span>
            </div>

            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="loan_required" id="loan_required" required>
                    <option value="">Loan Required?</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="loan_required">Loan Required? <span class="text-danger">*</span></label>
                <span class="text-danger" id="loan_required-error"></span>
            </div>

            <!-- Bank Details Section -->
            <div id="bankDetailsSection" class="mb-4" style="display: none;">
                <h6 class="fw-bold mb-3">🏦 Financial Details (Loan Applicants)</h6>
                <div class="table-responsive border rounded">
                    <table class="table table-bordered align-middle mb-0">
                        <tbody>
                            <tr>
                                <th style="width: 30%;">Bank Name</th>
                                <td>
                                    <input type="text" class="form-control" name="bank_name" id="bank_name"
                                        placeholder="Enter Bank Name">
                                    <span class="text-danger" id="bank_name-error"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>Branch</th>
                                <td>
                                    <input type="text" class="form-control" name="bank_branch" id="bank_branch"
                                        placeholder="Enter Branch">
                                    <span class="text-danger" id="bank_branch-error"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>Account Number</th>
                                <td>
                                    <input type="text" class="form-control" name="account_number"
                                        id="account_number" placeholder="Enter Account Number">
                                    <span class="text-danger" id="account_number-error"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>IFSC Code</th>
                                <td>
                                    <input type="text" class="form-control" name="ifsc_code" id="ifsc_code"
                                        placeholder="Enter IFSC Code">
                                    <span class="text-danger" id="ifsc_code-error"></span>
                                </td>
                            </tr>
                            <tr>
                                <th>Mode</th>
                                <td>
                                    <select class="form-select" name="loan_mode" id="loan_mode">
                                        <option value="">Select Mode</option>
                                        <option value="Loan">Loan</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                    <span class="text-danger" id="loan_mode-error"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- NEXT Button -->
            <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
                <button type="button" class="btn btn-primary" id="goToStep2">
                    Next <i class="mdi mdi-arrow-right ms-1"></i>
                </button>
            </div>
        </div>
        <!-- Step 2: Documents -->
        <div class="tab-pane fade" id="tabStatusLog" role="tabpanel" aria-labelledby="statuslog-tab">
            <div class="mb-4">
                <h6 class="fw-bold mb-3">📎 Upload Required Documents</h6>

                <div class="mb-3">
                    <label for="aadhaar_card" class="form-label">Aadhaar Card <span
                            class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="aadhaar_card" name="aadhaar_card" required>
                    <span class="text-danger" id="aadhaar_card-error"></span>
                </div>

                <div class="mb-3">
                    <label for="pan_card" class="form-label">PAN Card <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="pan_card" name="pan_card" required>
                    <span class="text-danger" id="pan_card-error"></span>
                </div>

                <div class="mb-3">
                    <label for="electricity_bill" class="form-label">Recent Electricity Bill <span
                            class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="electricity_bill" name="electricity_bill"
                        required>
                    <span class="text-danger" id="electricity_bill-error"></span>
                </div>

                <div class="mb-3">
                    <label for="bank_proof" class="form-label">Bank Passbook / Cancelled Cheque <span
                            class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="bank_proof" name="bank_proof" required>
                    <span class="text-danger" id="bank_proof-error"></span>
                </div>

                <div class="mb-3">
                    <label for="passport_photo" class="form-label">Passport-size Photo <span
                            class="text-muted">(Optional)</span></label>
                    <input class="form-control" type="file" id="passport_photo" name="passport_photo">
                    <span class="text-danger" id="passport_photo-error"></span>
                </div>

                <div class="mb-3">
                    <label for="ownership_proof" class="form-label">Property Ownership Proof <span
                            class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="ownership_proof" name="ownership_proof" required>
                    <span class="text-danger" id="ownership_proof-error"></span>
                </div>

                <div class="mb-3">
                    <label for="site_photo" class="form-label">Roof Layout / Site Photograph <span
                            class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="site_photo" name="site_photo" required>
                    <span class="text-danger" id="site_photo-error"></span>
                </div>

                <div class="mb-3">
                    <label for="self_declaration" class="form-label">Self-Declaration Form <span
                            class="text-muted">(Optional)</span></label>
                    <input class="form-control" type="file" id="self_declaration" name="self_declaration">
                    <span class="text-danger" id="self_declaration-error"></span>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
                <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas">
                    <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
                </button>
                <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
                    <span class="tf-icons mdi mdi-checkbox-marked-circle-outline">&nbsp;</span>Submit
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Script for Step Navigation -->
<script>
    $(document).ready(function() {
        // Show/hide bank details
        $('#loan_required').on('change', function() {
            const show = $(this).val() === 'Yes';
            $('#bankDetailsSection').toggle(show);

            const fields = ['bank_name', 'bank_branch', 'account_number', 'ifsc_code', 'loan_mode'];
            fields.forEach(name => {
                const el = $('[name="' + name + '"]');
                if (show) {
                    el.rules('add', {
                        required: true,
                        messages: {
                            required: 'This field is required.'
                        }
                    });
                } else {
                    el.rules('remove', 'required');
                    $('#' + name + '-error').text('').hide();
                    el.removeClass('is-invalid');
                }
            });
        });

        // Form Validation Setup
        $("#commonform").validate({
            errorPlacement: function(error, element) {
                const id = element.attr('name') + '-error';
                $('#' + id).html(error.text()).show();
                element.addClass('is-invalid');
            },
            success: function(label, element) {
                const id = $(element).attr('name') + '-error';
                $('#' + id).html('').hide();
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                event.preventDefault();

                var formData = new FormData(form);

                var Url = "{{ config('apiConstants.PROPOSAL_URLS.PROPOSAL') }}";

                fnCallAjaxHttpPostEventWithoutJSON(Url, formData, true, true, function(
                    response) {
                    if (response.status === 200) {
                        bootstrap.Offcanvas.getInstance(document.getElementById(
                            'commonOffcanvas')).hide();
                        ShowMsg("bg-success",
                            'The record has been processed successfully.');
                    } else {
                        ShowMsg("bg-warning",
                            'The record could not be processed.');
                    }
                });
            }
        });

        // Go to Step 2 after validating Step 1
        $('#goToStep2').on('click', function() {
            const fields = $('#tabDetails').find('input, select').filter('[name]');
            let isValid = true;

            fields.each(function() {
                if (!$(this).valid()) isValid = false;
            });

            if (isValid) {
                const nextTab = new bootstrap.Tab($('#statuslog-tab'));
                nextTab.show();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
