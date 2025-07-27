<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="companyId" value="{{ $companyId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="legal_name" id="legal_name" placeholder="Company Name" />
        <label for="legal_name">Company Name <span style="color:red">*</span></label>
        <span class="text-danger" id="legal_name-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="file" class="form-control" id="logo_url" name="logo_url" placeholder="Enter Logo URL">
        <label for="logo_url">Company Logo</label>
        <span class="text-danger" id="logo_url-error"></span>
        <a href="#" id="logo_url-old-name" name="logo_url" target="_blank" class="form-text"></a>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="website" id="website" placeholder="Website" />
        <label for="website">Website</label>
        <span class="text-danger" id="website-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" />
        <label for="phone">Phone <span style="color:red">*</span></label>
        <span class="text-danger" id="phone-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="email" class="form-control" name="email" id="email" placeholder="Email" />
        <label for="email">Email <span style="color:red">*</span></label>
        <span class="text-danger" id="email-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="gst_number" id="gst_number" placeholder="GST Number" />
        <label for="gst_number">GST Number</label>
        <span class="text-danger" id="gst_number-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="pan_number" id="pan_number" placeholder="PAN Number" />
        <label for="pan_number">PAN Number</label>
        <span class="text-danger" id="pan_number-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="address_line_1" id="address_line_1"
            placeholder="Address Line 1" />
        <label for="address_line_1">Address Line 1 <span style="color:red">*</span></label>
        <span class="text-danger" id="address_line_1-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="address_line_2" id="address_line_2"
            placeholder="Address Line 2" />
        <label for="address_line_2">Address Line 2</label>
        <span class="text-danger" id="address_line_2-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="address_line_3" id="address_line_3"
            placeholder="Address Line 3" />
        <label for="address_line_3">Address Line 3</label>
        <span class="text-danger" id="address_line_3-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="alternate_mobile_no" id="alternate_mobile_no"
            placeholder="Alternate Mobile No" />
        <label for="alternate_mobile_no">Alternate Mobile No</label>
        <span class="text-danger" id="alternate_mobile_no-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <select class="form-select" name="country" id="country">
            <option value="">Select Country</option>
            {{-- Load country options dynamically --}}
        </select>
        <label for="country">Country <span style="color:red">*</span></label>
        <span class="text-danger" id="country-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <select class="form-select" name="state" id="state">
            <option value="">Select State</option>
            {{-- Load state options dynamically --}}
        </select>
        <label for="state">State <span style="color:red">*</span></label>
        <span class="text-danger" id="state-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="city" id="city" placeholder="City" />
        <label for="city">City <span style="color:red">*</span></label>
        <span class="text-danger" id="city-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="pin_code" id="pin_code" placeholder="PIN Code" />
        <label for="pin_code">PIN Code <span style="color:red">*</span></label>
        <span class="text-danger" id="pin_code-error"></span>
    </div>

    <div class="form-check mb-4" style="padding-left: 2.5rem;">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked />
        <label class="form-check-label" for="is_active"> Active </label>
        <span class="text-danger" id="is_active-error"></span>
    </div>

    <div class="form-text mb-4" style="text-align: left;">
        <i class="mdi mdi-information-outline me-1" style="color: #dc3545;"></i>
        <strong style="color: #dc3545;">Note:</strong>
        <span style="color: #dc3545;">
            You can log in using the company's email or phone number. The phone number will be used as the password.
        </span>
    </div>

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
    var companyId = $("#companyId").val();

    $(document).ready(function() {

        let allStates = [];
        const companyId = $("#companyId").val();
        const countryDropdown = $("#country");
        const stateDropdown = $("#state");
        const profileUrl = `{{ config('apiConstants.PROFILE_URLS.PROFILE') }}`;

        fnCallAjaxHttpGetEvent(profileUrl, null, true, true, function(response) {
            if (response.status === 200 && response.data) {
                countryDropdown.empty().append('<option value="">Select Country</option>');
                response.data.nationalities.forEach(nationality => {
                    countryDropdown.append(
                        `<option value="${nationality.id}">${nationality.name}</option>`);
                });
                allStates = response.data.state;

                // Only load company after countries and states are populated
                if (companyId > 0) {
                    const companyUrl = "{{ config('apiConstants.COMPANY_URLS.COMPANY_VIEW') }}";
                    fnCallAjaxHttpGetEvent(companyUrl, {
                        companyId
                    }, true, true, function(response) {
                        if (response.status === 200 && response.data) {
                            const company = response.data;

                            // Set country first
                            countryDropdown.val(company.country);

                            // Populate states based on country, then set state after DOM is updated
                            populateStateDropdown(company.country);
                            setTimeout(() => {
                                stateDropdown.val(company.state);
                            }, 300); // Small delay to ensure state options are rendered

                            setCompanyFormData(company);
                        }
                    });
                }
            }
        });

        countryDropdown.on("change", function() {
            populateStateDropdown($(this).val());
        });

        function populateStateDropdown(countryId) {
            stateDropdown.empty().append('<option value="">Select State</option>');
            if (countryId) {
                const filteredStates = allStates.filter(state => state.country_id == countryId);
                filteredStates.forEach(state => {
                    stateDropdown.append(`<option value="${state.id}">${state.name}</option>`);
                });
            }
        }

        // Set form fields from company data
        function setCompanyFormData(data) {
            if (data.logo_url) {
                $("#logo_url-old-name").text(data.logo_display_name).attr('href', data.logo_url);
            }
            $("#legal_name").val(data.legal_name);
            $("#phone").val(data.phone);
            $("#alternate_mobile_no").val(data.alternate_mobile_no);
            $("#email").val(data.email);
            $("#website").val(data.website);
            $("#gst_number").val(data.gst_number);
            $("#pan_number").val(data.pan_number);
            $("#address_line_1").val(data.address_line_1);
            $("#address_line_2").val(data.address_line_2);
            $("#address_line_3").val(data.address_line_3);
            $("#city").val(data.city);
            $("#pin_code").val(data.pin_code);
            $("#is_active").prop("checked", data.is_active);
        }

        // jQuery Validation Setup
        $("#commonform").validate({
            rules: {
                legal_name: {
                    required: true
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                email: {
                    required: true,
                    email: true
                },
                email: {
                    required: true,
                    email: true
                },
                website: {
                    required: false,
                    url: true
                },
                address_line_1: {
                    required: true
                },
                country: {
                    required: true
                },
                state: {
                    required: true
                },
                city: {
                    required: true
                },
                pin_code: {
                    required: true,
                    digits: true
                },
                logo_url: {
                    extension: "jpg|jpeg|png|gif"
                },
                gst_number: {
                    required: false,
                    minlength: 15,
                    maxlength: 15
                },
                pan_number: {
                    required: false,
                    minlength: 10,
                    maxlength: 10
                },
                alternate_mobile_no: {
                    required: false,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                }
            },
            messages: {
                legal_name: {
                    required: "Company Name is required"
                },
                phone: {
                    required: "Phone is required",
                    digits: "Please enter a valid phone number",
                    minlength: "Phone number must be at least 10 digits long",
                    maxlength: "Phone number must be at most 15 digits long"
                },
                email: {
                    required: "Email is required"
                },
                website: {
                    required: "Website is required",
                    url: "Please enter a valid URL"
                },
                address_line_1: {
                    required: "Address Line 1 is required"
                },
                country: {
                    required: "Country is required"
                },
                state: {
                    required: "State is required"
                },
                city: {
                    required: "City is required"
                },
                pin_code: {
                    required: "PIN Code is required",
                    digits: "Please enter a valid PIN Code"
                },
                logo_url: {
                    extension: "Please upload a valid image file (jpg, jpeg, png, gif)"
                },
                gst_number: {
                    required: "GST Number is required",
                    minlength: "GST Number must be 15 characters long",
                    maxlength: "GST Number must be 15 characters long"
                },
                pan_number: {
                    required: "PAN Number is required",
                    minlength: "PAN Number must be 10 characters long",
                    maxlength: "PAN Number must be 10 characters long"
                },
                alternate_mobile_no: {
                    required: "Alternate Mobile No is required",
                    digits: "Please enter a valid mobile number",
                    minlength: "Mobile number must be at least 10 digits long",
                    maxlength: "Mobile number must be at most 15 digits long"
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
                formData.append('companyId', $("#companyId").val());
                formData.append('is_active', $("#is_active").is(':checked') ? 1 : 0);
                var storeUrl =
                    "{{ config('apiConstants.COMPANY_URLS.COMPANY_STORE') }}";
                var updateUrl =
                    "{{ config('apiConstants.COMPANY_URLS.COMPANY_UPDATE') }}";
                var url = companyId > 0 ? updateUrl : storeUrl;
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
    });
</script>
