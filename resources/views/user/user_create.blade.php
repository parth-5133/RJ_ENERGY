<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="userId" value="{{ $userId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="first_name" id="first_name" maxlength="50"
            placeholder="First Name" />
        <label for="first_name">First Name<span style="color:red">*</span></label>
        <span class="text-danger" id="first_name-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="middle_name" id="middle_name" maxlength="50"
            placeholder="Middle Name" />
        <label for="middle_name">Middle Name</label>
        <span class="text-danger" id="middle_name-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="last_name" id="last_name" maxlength="50"
            placeholder="Last Name" />
        <label for="last_name">Last Name<span style="color:red">*</span></label>
        <span class="text-danger" id="last_name-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="email" class="form-control" name="email" id="email" placeholder="Email" />
        <label for="email">Email<span style="color:red">*</span></label>
        <span class="text-danger" id="email-error" style="display: block !important;"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <select class="form-select" name="role_id" id="role_id">
            <option value="">Select Role</option>
            <!-- Options will be populated by JavaScript -->
        </select>
        <label for="role_id">Role <span style="color:red">*</span></label>
        <span class="text-danger" id="role_id-error"></span>
    </div>

    @if (empty($userId))
        <div class="form-floating form-floating-outline mb-4">
            <input type="password" class="form-control" name="password" id="password"
                placeholder="Enter your password" />
            <label for="password">Password <span style="color:red">*</span></label>
            <span class="text-danger" id="password-error"></span>
        </div>

        <div class="form-floating form-floating-outline mb-4">
            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                placeholder="Confirm your password" />
            <label for="password_confirmation">Confirm Password <span style="color:red">*</span></label>
            <span class="text-danger" id="password_confirmation-error"></span>
        </div>
    @endif

    <div class="form-check mb-4" style="padding-left: 2.5rem;">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" />
        <label class="form-check-label" for="is_active"> Active </label>
        <span class="text-danger" id="is_active-error"></span>
    </div>

    <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
        <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas"><span
                class="tf-icons mdi mdi-cancel me-1"></span>Cancel</button>
        <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
            <span class="tf-icons mdi mdi-checkbox-marked-circle-outline">&nbsp;</span>Submit
        </button>
    </div>
</form>

<script type="text/javascript">
    var userId = $("#userId").val(); // Get the user ID from the hidden input
    $(document).ready(function() {

        // Fetch all role items to populate the dropdown list
        fnCallAjaxHttpGetEvent("{{ config('apiConstants.API_URLS.ROLES') }}", null, true, true, function(
            response) {

            if (response.status === 200 && response.data) {
                var $roleDropdown = $("#role_id");
                $roleDropdown.empty(); // Clear existing options
                $roleDropdown.append(new Option('Select Role', '')); // Default option

                response.data.forEach(function(data) {
                    $roleDropdown.append(new Option(data.name, data.id));
                });

                // Set selected role if userId is present
                if (userId > 0) {
                    fnCallAjaxHttpGetEvent("{{ config('apiConstants.USER_API_URLS.USER_VIEW') }}", {
                            userId: userId
                        },
                        true,
                        true,
                        function(response) {
                            if (response.status === 200 && response.data) {
                                // Populate form fields with data from the response
                                $("#first_name").val(response.data.first_name);
                                $("#middle_name").val(response.data.middle_name);
                                $("#last_name").val(response.data.last_name);
                                $("#email").val(response.data.email);
                                $("#role_id").val(response.data.role_id); // Set selected role
                                $("#is_active").prop('checked', response.data.is_active);
                            } else {
                                console.error('Failed to retrieve user data.');
                            }
                        });
                }
            } else {
                console.error('Failed to retrieve role list.');
            }
        });

        // Validation Setup for #commonform
        $("#commonform").validate({
            rules: {
                first_name: {
                    required: true,
                    maxlength: 50,
                },
                middle_name: {
                    maxlength: 50,
                },
                last_name: {
                    required: true,
                    maxlength: 50,
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 100,
                },
                role_id: {
                    required: true,
                },
                password: {
                    required: true,
                    minlength: 6,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password",
                },
                is_active: {
                    required: false,
                },
            },
            messages: {
                first_name: {
                    required: "First name is required",
                    maxlength: "First name cannot be more than 50 characters",
                },
                middle_name: {
                    maxlength: "Middle name cannot be more than 50 characters",
                },
                last_name: {
                    required: "Last name is required",
                    maxlength: "Last name cannot be more than 50 characters",
                },
                email: {
                    required: "Email is required",
                    email: "Please enter a valid email address",
                    maxlength: "Email cannot be more than 100 characters",
                },
                role_id: {
                    required: "Role is required",
                },
                password: {
                    required: "Password is required",
                    minlength: "Password must be at least 6 characters",
                },
                password_confirmation: {
                    required: "Password confirmation is required",
                    equalTo: "Passwords do not match",
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
                    userId: $("#userId").val(),
                    first_name: $("#first_name").val(),
                    middle_name: $("#middle_name").val(),
                    last_name: $("#last_name").val(),
                    email: $("#email").val(),
                    role_id: $("#role_id").val(),
                    password: $("#password").val(),
                    password_confirmation: $("#password_confirmation").val(),
                    is_active: $("#is_active").is(':checked'),
                };

                var url = userId > 0 ? "{{ config('apiConstants.USER_API_URLS.USER_UPDATE') }}" :
                    "{{ config('apiConstants.USER_API_URLS.USER_STORE') }}";

                fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                    if (response.status === 200) {
                        bootstrap.Offcanvas.getInstance(document.getElementById(
                            'commonOffcanvas')).hide();
                        $('#grid').DataTable().ajax.reload();
                        ShowMsg("bg-success", response.message);
                    } else {
                        ShowMsg("bg-warning",
                            'The record could not be processed.');
                    }
                });
            }
        });
    });
</script>
