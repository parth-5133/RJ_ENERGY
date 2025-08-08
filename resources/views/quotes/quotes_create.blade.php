<form action="javascript:void(0)" id="customerForm" name="customerForm" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="quotesId" name="quotesId" value="{{ $quotesId ?? '' }}">

    <!-- Section 1: Customer Basic Details -->
    <h5 class="fw-bold mb-3 mt-4">👤 Customer Basic Details</h5>
    <div class="row">
        <!-- Name -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Name" />
                <label for="customer_name">Name <span class="text-danger">*</span></label>
                <span class="text-danger" id="customer_name-error"></span>
            </div>
        </div>
        <!-- Age -->
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
        <!-- Mobile -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="tel" class="form-control" name="mobile" id="mobile" maxlength="10"
                    placeholder="Aadhar-linked Mobile" />
                <label for="mobile">Aadhar-linked Mobile <span class="text-danger">*</span></label>
                <span class="text-danger" id="mobile-error"></span>
            </div>
        </div>
        <!-- Alternate Mobile -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="tel" class="form-control" name="alternate_mobile" id="alternate_mobile" maxlength="10"
                    placeholder="Alternate Mobile" />
                <label for="alternate_mobile">Alternate Mobile</label>
                <span class="text-danger" id="alternate_mobile-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" id="PerAdd_state" name="PerAdd_state">
                    <option value="">Select State</option>
                </select>
                <label for="PerAdd_state">State <span style="color:red">*</span></label>
                <span class="text-danger" id="PerAdd_state-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="district" name="district" placeholder="District" />
                <label for="district">District <span style="color:red">*</span></label>
                <span class="text-danger" id="district-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="PerAdd_city" name="PerAdd_city"
                    placeholder="City" />
                <label for="city">City <span style="color:red">*</span></label>
                <span class="text-danger" id="PerAdd_city-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input class="form-control" type="text" id="PerAdd_pin_code" name="PerAdd_pin_code"
                    placeholder="Pin Code" />
                <label for="pin_code">Pin Code <span style="color:red">*</span></label>
                <span class="text-danger" id="PerAdd_pin_code-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <textarea class="form-control" name="customer_address" id="customer_address" placeholder="Enter Address"
                    style="height: 10px;"></textarea>
                <label for="customer_address">Permanent Address <span class="text-danger">*</span></label>
                <span class="text-danger" id="customer_address-error"></span>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <textarea class="form-control" name="customer_residential_address" id="customer_residential_address"
                    placeholder="Enter Address" style="height: 10px;"></textarea>
                <label for="customer_residential_address">Residential Address <span
                        class="text-danger">*</span></label>
                <span class="text-danger" id="customer_residential_address-error"></span>
            </div>
        </div>
    </div>
    <!-- Section 3: Quotation -->
    <h5 class="fw-bold mb-3 mt-4">🧾 Quotation</h5>
    <div class="row">
        <!-- Quotation -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_" id="quotation_">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="quotation_">Is Quotation <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_-error"></span>
            </div>
        </div>
        <!-- Proposed Solar Capacity -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="solar_capacity" id="solar_capacity"
                    placeholder="Solar Capacity" />
                <label for="solar_capacity">Solar Capacity (e.g., 3kW) <span class="text-danger">*</span></label>
                <span class="text-danger" id="solar_capacity-error"></span>
            </div>
        </div>
        <!-- Rooftop Size -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="rooftop_size" id="rooftop_size"
                    placeholder="Rooftop Size" />
                <label for="rooftop_size">Rooftop Size (in sq. ft) <span class="text-danger">*</span></label>
                <span class="text-danger" id="rooftop_size-error"></span>
            </div>
        </div>
        <!-- Quotation Amount -->
        <div class="col-md-4 mb-4 quotation-dependent">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="quotation_amount" id="quotation_amount"
                    placeholder="Quotation Amount">
                <label for="quotation_amount">Quotation Amount <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_amount-error"></span>
            </div>
        </div>
        <!-- Quotation Date -->
        <div class="col-md-4 mb-4 quotation-dependent">
            <div class="form-floating form-floating-outline">
                <input type="date" class="form-control" name="quotation_date" id="quotation_date"
                    placeholder="Quotation Date">
                <label for="quotation_date">Quotation Date <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_date-error"></span>
            </div>
        </div>
        <!-- Entered By -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_by" id="quotation_by">
                </select>
                <label for="quotation_by">Entered By <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_by-error"></span>
            </div>
        </div>
    </div>
    <div class="row quotation-dependent">
        <!-- Quotation Status -->
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
    var quotesId = $("#quotesId").val();

    $(document).ready(function() {

        let url =
            `{{ config('apiConstants.PROFILE_URLS.PROFILE') }}?id={{ request()->get('id') }}&Params='Address'`;

        fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
            if (response.status === 200 && response.data) {
                allStates = response.data.state;
                const fixedCountryId = 1;
                populateStateDropdown(fixedCountryId, "#PerAdd_state");
            }
        });

        function populateStateDropdown(countryId, stateDropdownSelector) {
            const stateDropdown = $(stateDropdownSelector);
            stateDropdown.empty();
            stateDropdown.append('<option value="">Select State</option>');
            if (countryId) {
                const filteredStates = allStates.filter(state => state.country_id == countryId);
                filteredStates.forEach(function(state) {
                    stateDropdown.append(`<option value="${state.name}">${state.name}</option>`);
                });
            }
        }

        fnCallAjaxHttpGetEvent("{{ config('apiConstants.QUOTATION_URLS.QUOTATION_ALL_ACCOUNTANT') }}", null,
            true, true,
            function(
                response) {
                if (response.status === 200 && response.data) {
                    var options = '<option selected disabled value="">Select</option>';
                    $.each(response.data, function(index, accountant) {
                        options += '<option value="' + accountant.id + '">' + accountant.full_name +
                            '</option>';
                    });
                    $("#quotation_by").html(options);
                } else {
                    console.log('Failed to retrieve accountant data.');

                }
            });

        if (quotesId > 0) {
            var Url = "{{ config('apiConstants.QUOTATION_URLS.QUOTATION_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                quotesId: quotesId
            }, true, true, function(
                response) {
                if (response.status === 200 && response.data) {
                    debugger
                    $("#customer_name").val(response.data.customer_name);
                    $("#age").val(response.data.age);
                    $("#gender").val(response.data.gender);
                    $("#marital_status").val(response.data.marital_status);
                    $("#mobile").val(response.data.mobile);
                    $("#alternate_mobile").val(response.data.alternate_mobile);
                    $("#PerAdd_state").val(response.data.PerAdd_state);
                    $("#district").val(response.data.district);
                    $("#PerAdd_city").val(response.data.PerAdd_city);
                    $("#PerAdd_pin_code").val(response.data.PerAdd_pin_code);
                    $("#customer_address").val(response.data.customer_address);
                    $("#customer_residential_address").val(response.data.customer_residential_address);
                    $("#quotation_").val(response.data.required);
                    $("#solar_capacity").val(response.data.capacity);
                    $("#rooftop_size").val(response.data.roof_area);
                    $("#quotation_amount").val(response.data.amount);
                    $("#quotation_date").val(response.data.date);
                    $("#quotation_by").val(response.data.by);
                    $("#quotation_status").val(response.data.status);
                } else {
                    console.log('Failed to retrieve role data.');
                }
            });
        }
    });

    $("#customerForm").validate({
        rules: {
            customer_name: {
                required: true,
                maxlength: 50,
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
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            alternate_mobile: {
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
                required: false,
                maxlength: 10
            },
            quotation_: {
                required: true,
            },
            quotation_by: {
                required: true,
            }
        },
        messages: {
            customer_name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 50 characters",
            },
            age: {
                required: "Age is required",
                digits: "Please enter a valid age",
                minlength: "Age must be at least 1 year old",
                maxlength: "Age cannot exceed 3 digits",
            },
            gender: {
                required: "Gender is required."
            },
            marital_status: {
                required: "Marital status is required."
            },
            mobile: {
                required: "Mobile is required",
                digits: "Please enter a valid mobile number",
                minlength: "Mobile number must be at least 10 digits long",
                maxlength: "Mobile number must be at most 15 digits long"
            },
            alternate_mobile: {
                digits: "Please enter a valid mobile number",
                minlength: "Mobile number must be at least 10 digits long",
                maxlength: "Mobile number must be at most 15 digits long"
            },
            quotation_: {
                required: "Quotation selection is required",
            },
            quotation_by: {
                required: "Quotation By is required",
            },
            quotation_amount: {
                required: "Quotation amount is required",
                number: "Please enter a valid number"
            }
        },
        errorPlacement: function(error, element) {
            var errorId = element.attr("name") + "-error";
            $("#" + errorId).text(error.text()).show();
            element.addClass("is-invalid");
        },
        success: function(label, element) {
            var errorId = $(element).attr("name") + "-error";
            $("#" + errorId).text("").hide();
            $(element).removeClass("is-invalid");
        },
        submitHandler: function(form, event) {
            event.preventDefault();

            var formData = new FormData(form);

            var storeUrl = "{{ config('apiConstants.QUOTATION_URLS.QUOTATION_STORE') }}";
            var updateUrl = "{{ config('apiConstants.QUOTATION_URLS.QUOTATION_UPDATE') }}";
            var url = quotesId > 0 ? updateUrl : storeUrl;

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
