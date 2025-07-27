<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="userId" value="{{ $userId ?? '' }}">
    <div class="form-floating form-floating-outline mb-4">
        <input type="password" class="form-control" name="password" id="password" maxlength="50"
            placeholder="New Password" />
        <label for="password">New Password<span style="color:red">*</span></label>
        <span class="text-danger" id="password-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
            maxlength="50" placeholder="Confirm Password" />
        <label for="password_confirmation">Confirm Password<span style="color:red">*</span></label>
        <span class="text-danger" id="password_confirmation-error"></span>
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
    var userId = $("#userId").val();
    // jQuery Validation Setup
    $("#commonform").validate({
        rules: {
            password: {
                required: true,
                minlength: 6,
                maxlength: 50,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password",
            },
        },
        messages: {
            password: {
                required: "Password is required.",
                minlength: "Password must be at least 8 characters long.",
                maxlength: "Password cannot exceed 50 characters.",
            },
            password_confirmation: {
                required: "Confirm Password is required.",
                equalTo: "Passwords do not match.",
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

            // Prepare data for AJAX request
            var postData = {
                userId: $("#userId").val(),
                password: $("#password").val(),
                password_confirmation: $("#password_confirmation").val(),
            };

            var url = "{{ config('apiConstants.AUTH_URL.CHANGE_PASSWORD') }}";

            // AJAX call
            fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                if (response.status === 200) {
                    bootstrap.Offcanvas.getInstance(document.getElementById('commonOffcanvas'))
                        .hide();
                    ShowMsg("bg-success", response.message);
                    if (!postData.userId || postData.userId == 0) {
                        // Redirect to login page if userId is null
                        setTimeout(function() {
                            setCookie("access_token", null, -1);
                            setCookie("user_data", null, -1);
                            
                            window.location.href = '/';
                        }, 2000);
                    }
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        }
    });
</script>
