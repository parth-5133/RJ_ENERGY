<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="channelPartnersId" name="channelPartnersId" value="{{ $channelPartnersId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="legal_name" id="legal_name"
            placeholder="Channel Partners Name" />
        <label for="legal_name">Channel Partners Name <span style="color:red">*</span></label>
        <span class="text-danger" id="legal_name-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="file" class="form-control" id="logo_url" name="logo_url" placeholder="Enter Logo URL">
        <label for="logo_url">Channel Partners Logo</label>
        <span class="text-danger" id="logo_url-error"></span>
        <a href="#" id="logo_url-old-name" name="logo_url" target="_blank" class="form-text"></a>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="number" step="0.01" class="form-control" name="commission" id="commission"
            placeholder="Commission" />
        <label for="commission">Commission (%) <span style="color:red">*</span></label>
        <span class="text-danger" id="commission-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" />
        <label for="phone">Phone <span style="color:red">*</span></label>
        <span class="text-danger" id="phone-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="email" class="form-control" name="email" id="email" placeholder="Email" />
        <label for="email">Email</label>
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
    var channelPartnersId = $("#channelPartnersId").val();

    $(document).ready(function() {

        const channelPartnersId = $("#channelPartnersId").val();

        if (channelPartnersId > 0) {
            const companyUrl = "{{ config('apiConstants.CHANNEL_PARTNERS_URLS.CHANNEL_PARTNERS_VIEW') }}";
            fnCallAjaxHttpGetEvent(companyUrl, {
                channelPartnersId
            }, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    const company = response.data;
                    setCompanyFormData(company);
                }
            });
        }
    });
    // Set form fields from company data
    function setCompanyFormData(data) {
        if (data.logo_url) {
            $("#logo_url-old-name").text(data.logo_display_name).attr('href', data.logo_url);
        }
        $("#legal_name").val(data.legal_name);
        $("#phone").val(data.phone);
        $("#email").val(data.email);
        $("#commission").val(data.commission);
        $("#gst_number").val(data.gst_number);
        $("#pan_number").val(data.pan_number);
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
                required: false,
                email: true
            },
            website: {
                required: false,
                url: true
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
            }
        },
        messages: {
            legal_name: {
                required: "Channel Partners Name is required"
            },
            phone: {
                required: "Phone is required",
                digits: "Please enter a valid phone number",
                minlength: "Phone number must be at least 10 digits long",
                maxlength: "Phone number must be at most 15 digits long"
            },
            website: {
                required: "Website is required",
                url: "Please enter a valid URL"
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
            var storeUrl =
                "{{ config('apiConstants.CHANNEL_PARTNERS_URLS.CHANNEL_PARTNERS_STORE') }}";
            var updateUrl =
                "{{ config('apiConstants.CHANNEL_PARTNERS_URLS.CHANNEL_PARTNERS_UPDATE') }}";
            var url = channelPartnersId > 0 ? updateUrl : storeUrl;
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
