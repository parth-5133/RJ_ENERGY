<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="EMployeeRoleId" value="{{ $EMployeeRoleId ?? '' }}">
    <input type="hidden" id="isCopy" value="{{ $isCopy ?? '' }}">
    <div class="row">
        <!-- Month Selection -->
        <div class="col-md-4">
            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" id="salary_month" name="salary_month">
                    <option value="">-- Select Month --</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <label for="salary_month">Month <span style="color:red">*</span></label>
                <span class="text-danger" id="salary_month-error"></span>
            </div>
        </div>

        <!-- Year Selection -->
        <div class="col-md-4">
            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" id="salary_year" name="salary_year">
                    <option value="">-- Select Year --</option>
                </select>
                <label for="salary_year">Year <span style="color:red">*</span></label>
                <span class="text-danger" id="salary_year-error"></span>
            </div>
        </div>

        <!-- Employee -->
        <div class="col-md-4">
            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="employee" id="employee"></select>
                <label for="employee">Employee <span style="color:red">*</span></label>
                <span class="text-danger" id="employee-error"></span>
            </div>
        </div>

        <!-- Basic Salary -->
        <div class="col-md-4">
            <div class="form-floating form-floating-outline mb-4">
                <input type="number" min="0" class="form-control" name="basic_salary" id="basic_salary"
                    placeholder="Basic Salary" />
                <label for="basic_salary">Basic Salary <span style="color:red">*</span></label>
                <span class="text-danger" id="basic_salary-error"></span>
            </div>
        </div>

        <!-- Allowances Section -->
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div class="card border rounded p-1" style="background-color: #f9f9f9;">
                <h5 class="mb-0">Allowances</h5>
            </div>
        </div>

        @foreach ($allowanceList as $allowance)
            <div class="col-md-3">
                <label for="allowance_{{ $allowance['id'] }}">{{ $allowance['allowances_name'] }}</label>
                <div class="form-floating form-floating-outline mb-4">
                    <input type="number" step="0.01" class="form-control allowance-input"
                        name="allowances_{{ $allowance['id'] }}" id="allowance_{{ $allowance['id'] }}"
                        placeholder="{{ $allowance['allowances_name'] }}" value="0.00">
                    <span class="text-danger" id="allowance_{{ $allowance['id'] }}-error"></span>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row gy-4 mt-3">
        <!-- Deductions Section -->
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <div class="card border rounded p-1" style="background-color: #f9f9f9;">
                <h5 class="mb-0">Deductions</h5>
            </div>
        </div>

        @foreach ($deductionList as $deduction)
            <div class="col-md-3">
                <label for="deduction_{{ $deduction['id'] }}">{{ $deduction['deduction_name'] }}</label>
                <div class="form-floating form-floating-outline mb-4">
                    <input type="number" step="0.01" class="form-control deduction-input"
                        name="deductions_{{ $deduction['id'] }}" id="deduction_{{ $deduction['id'] }}"
                        placeholder="{{ $deduction['deduction_name'] }}" value="0.00">
                    <span class="text-danger" id="deduction_{{ $deduction['id'] }}-error"></span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- ✅ Payslip-style Summary Section -->
    <div class="row gy-4 mt-4">
        <div class="col-md-12">
            <div class="card border rounded p-3" style="background-color: #f9f9f9;">
                <div class="row text-center">
                    <div class="col-md-4">
                        <label class="fw-bold">Total Allowances</label>
                        <input type="text" id="total_allowances" name="total_allowances"
                            class="form-control text-center fw-bold" value="0.00" readonly
                            style="background-color: #f0f0f0;">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Total Deductions</label>
                        <input type="text" id="total_deductions" name="total_deductions"
                            class="form-control text-center fw-bold" value="0.00" readonly
                            style="background-color: #f0f0f0;">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Total Salary</label>
                        <input type="text" id="total_salary" name="total_salary"
                            class="form-control text-center fw-bold text-success" value="0.00" readonly
                            style="background-color: #e9f7ef;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
        <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas">
            <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
        </button>
        <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
            <span class="tf-icons mdi mdi-checkbox-marked-circle-outline">&nbsp;</span>Submit
        </button>
    </div>
</form>

<script type="text/javascript">
    var EMployeeRoleId = $("#EMployeeRoleId").val();
    var isCopy = $("#isCopy").val();

    $(document).ready(function() {

        loadEmployeeDropdown('employee', '{{ config('apiConstants.USER_API_URLS.USER') }}', false, true);

        // Populate year dropdown with current year and previous years
        populateYearDropdown('salary_year', -5, 1);

        // Set default values for month and year
        const currentDate = new Date();
        $("#salary_month").val(String(currentDate.getMonth() + 1).padStart(2, '0'));
        $("#salary_year").val(currentDate.getFullYear());

        // Trigger salary recalculation on value change
        $("#basic_salary, .allowance-input, .deduction-input").on("input", calculateTotalSalary);
    });


    function calculateTotalSalary() {
        let basicSalary = parseFloat($("#basic_salary").val()) || 0;
        let totalAllowances = 0;
        let totalDeductions = 0;

        // Calculate total allowances
        $("input[name^='allowances']").each(function() {
            totalAllowances += parseFloat($(this).val()) || 0;
        });

        // Calculate total deductions
        $("input[name^='deductions']").each(function() {
            totalDeductions += parseFloat($(this).val()) || 0;
        });

        // Update total allowances and deductions fields
        $("#total_allowances").val(totalAllowances.toFixed(2));
        $("#total_deductions").val(totalDeductions.toFixed(2));

        // Calculate total salary
        let totalSalary = basicSalary + totalAllowances - totalDeductions;
        totalSalary = totalSalary < 0 ? 0 : totalSalary;

        $("#total_salary").val(totalSalary.toFixed(2));
    }

    $("#commonform").validate({
        rules: {
            employee: {
                required: true,
            },
            salary_month: {
                required: true,
            },
            salary_year: {
                required: true,
            },
            basic_salary: {
                required: true,
                min: 0,
            },
        },
        messages: {
            employee: {
                required: "Please select an employee.",
            },
            salary_month: {
                required: "Please select a month.",
            },
            salary_year: {
                required: "Please select a year.",
            },
            basic_salary: {
                required: "Basic Salary is required.",
                min: "Basic Salary must be at least 0.",
            },
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

            var postData = {
                department: 1,
                employee: $("#employee").val(),
                salary_month: $("#salary_month").val(),
                salary_year: $("#salary_year").val(),
                basic_salary: $("#basic_salary").val(),
                allowances: $("input[name^='allowances']").serializeArray(),
                deductions: $("input[name^='deductions']").serializeArray(),
                total_allowances: $("#total_allowances").val(),
                total_deductions: $("#total_deductions").val(),
                total_salary: $("#total_salary").val(),
                EMployeeRoleId: EMployeeRoleId,
            };

            var storeUrl = "{{ config('apiConstants.EMPLOYEE_SALARY_URLS.EMPLOYEE_SALARY_STORE') }}";
            var updateUrl = "{{ config('apiConstants.EMPLOYEE_SALARY_URLS.EMPLOYEE_SALARY_UPDATE') }}";
            var url = EMployeeRoleId > 0 ? isCopy == 1 ? storeUrl : updateUrl : storeUrl;

            fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
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
