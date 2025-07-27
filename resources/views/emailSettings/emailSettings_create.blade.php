<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="emailSettingsId" value="{{ $emailSettingsId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="mail_driver" id="mail_driver" maxlength="100"
            placeholder="Mail Driver" />
        <label for="mail_driver">Mail Driver <span style="color:red">*</span></label>
        <span class="text-danger" id="mail_driver-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="mail_host" id="mail_host" maxlength="100" placeholder="Host" />
        <label for="mail_host">Mail Host <span style="color:red">*</span></label>
        <span class="text-danger" id="mail_host-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="number" class="form-control" name="mail_port" id="mail_port" placeholder="Port" />
        <label for="mail_port">Mail Port <span style="color:red">*</span></label>
        <span class="text-danger" id="mail_port-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="mail_username" id="mail_username" maxlength="100"
            placeholder="Username" />
        <label for="mail_username">Mail Username <span style="color:red">*</span></label>
        <span class="text-danger" id="mail_username-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="email" class="form-control" name="cc_mail_username" id="cc_mail_username" maxlength="100"
            placeholder="CC Mail Username" />
        <label for="cc_mail_username">CC Mail Username <span style="color:red">*</span></label>
        <span class="text-danger" id="cc_mail_username-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="password" class="form-control" name="mail_password" id="mail_password" maxlength="100"
            placeholder="Password" />
        <label for="mail_password">Mail Password <span style="color:red">*</span></label>
        <span class="text-danger" id="mail_password-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <select class="form-select" id="mail_encryption" name="mail_encryption">
            <option value="">None</option>
            <option value="tls">TLS</option>
            <option value="ssl">SSL</option>
        </select>
        <label for="mail_encryption">Mail Encryption</label>
        <span class="text-danger" id="mail_encryption-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="email" class="form-control" name="mail_from_address" id="mail_from_address" maxlength="100"
            placeholder="From Address" />
        <label for="mail_from_address">From Address <span style="color:red">*</span></label>
        <span class="text-danger" id="mail_from_address-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="mail_from_name" id="mail_from_name" maxlength="100"
            placeholder="From Name" />
        <label for="mail_from_name">From Name <span style="color:red">*</span></label>
        <span class="text-danger" id="mail_from_name-error"></span>
    </div>

    <div class="form-check mb-4" style="padding-left: 2.5rem;">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" />
        <label class="form-check-label" for="is_active"> Active </label>
        <span class="text-danger" id="is_active-error"></span>
    </div>

    <div class="form-text mb-4" style="text-align: left;">
        <i class="mdi mdi-information-outline me-1" style="color: #dc3545;"></i>
        <strong style="color: #dc3545;">Note:</strong> <span style="color: #dc3545;">The receive and send mail address
            will be the same as Mail Username.</span>
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
    var emailSettingsId = $("#emailSettingsId").val();

    $(document).ready(function() {
        if (emailSettingsId > 0) {
            var Url = "{{ config('apiConstants.EMAIL_SETTINGS_URLS.EMAIL_SETTINGS_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                id: emailSettingsId
            }, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    $("#mail_driver").val(response.data.mail_driver);
                    $("#mail_host").val(response.data.mail_host);
                    $("#mail_port").val(response.data.mail_port);
                    $("#mail_username").val(response.data.mail_username);
                    $("#mail_password").val(response.data.mail_password);
                    $("#mail_encryption").val(response.data.mail_encryption);
                    $("#mail_from_address").val(response.data.mail_from_address);
                    $("#mail_from_name").val(response.data.mail_from_name);
                    $("#is_active").prop('checked', response.data.is_active);
                    $("#cc_mail_username").val(response.data.cc_mail_username);
                } else {
                    console.log('Failed to retrieve email settings data.');
                }
            });
        }

        // jQuery Validation Setup
        $("#commonform").validate({
            rules: {
                mail_driver: {
                    required: true
                },
                mail_host: {
                    required: true,
                    maxlength: 100
                },
                mail_port: {
                    required: true,
                    number: true
                },
                mail_username: {
                    required: true,
                    maxlength: 100
                },
                mail_password: {
                    required: true,
                    maxlength: 100
                },
                mail_from_address: {
                    required: true,
                    email: true,
                    maxlength: 100
                },
                mail_from_name: {
                    required: true,
                    maxlength: 100
                },
                cc_mail_username: {
                    required: true,
                    email: true,
                    maxlength: 100
                }
            },
            messages: {
                mail_driver: {
                    required: "Mail driver is required"
                },
                mail_host: {
                    required: "Mail host is required",
                    maxlength: "Mail host cannot be more than 100 characters"
                },
                mail_port: {
                    required: "Mail port is required",
                    number: "Mail port must be a number"
                },
                mail_username: {
                    required: "Mail username is required",
                    maxlength: "Mail username cannot be more than 100 characters"
                },
                mail_password: {
                    required: "Mail password is required",
                    maxlength: "Mail password cannot be more than 100 characters"
                },
                mail_from_address: {
                    required: "From address is required",
                    email: "Please enter a valid email address",
                    maxlength: "From address cannot be more than 100 characters"
                },
                mail_from_name: {
                    required: "From name is required",
                    maxlength: "From name cannot be more than 100 characters"
                },
                cc_mail_username: {
                    required: "CC Mail Username is required",
                    email: "Please enter a valid email address",
                    maxlength: "CC Mail Username cannot be more than 100 characters"
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

                var postData = {
                    id: $("#emailSettingsId").val(),
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                    cc_mail_username: $("#cc_mail_username").val(),
                    is_active: $("#is_active").is(':checked')
                };

                var url = emailSettingsId > 0 ?
                    "{{ config('apiConstants.EMAIL_SETTINGS_URLS.EMAIL_SETTINGS_UPDATE') }}" :
                    "{{ config('apiConstants.EMAIL_SETTINGS_URLS.EMAIL_SETTINGS_STORE') }}";

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
    });
</script>
