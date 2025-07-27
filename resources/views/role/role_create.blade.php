<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="roleId" value="{{ $roleId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="name" id="name" maxlength="50" placeholder="Role" />
        <label for="name">Role Name<span style="color:red">*</span></label>
        <span class="text-danger" id="name-error"></span>
    </div>

    @if (empty($roleId))
        <div class="form-floating form-floating-outline mb-4">
            <input type="text" class="form-control" name="code" id="code" maxlength="50"
                placeholder="Code" />
            <label for="code">Code<span style="color:red">*</span></label>
            <span class="text-danger" id="code-error"></span>
        </div>
    @endif

    <div class="form-floating form-floating-outline mb-4">
        <input type="number" max="100" min="0" class="form-control" name="access_level" id="access_level"
            placeholder="Access Level" />
        <label for="access_level">Access Level<span style="color:red">*</span></label>
        <span class="text-danger" id="access_level-error"></span>
    </div>

    <div class="form-check mb-4" style="padding-left: 2.5rem;">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" />
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
    var roleId = $("#roleId").val();

    $(document).ready(function() {
        if (roleId > 0) {
            var Url = "{{ config('apiConstants.API_URLS.ROLES_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {roleId:roleId}, true, true, function(
                response) {
                if (response.status === 200 && response.data) {
                    $("#name").val(response.data.name);
                    $("#code").val(response.data.code);
                    $("#access_level").val(response.data.access_level);
                    $("#is_active").prop('checked', response.data.is_active);
                } else {
                    console.log('Failed to retrieve role data.');
                }
            });
        }

        // jQuery Validation Setup
        $("#commonform").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 50,
                },
                code: {
                    required: true,
                    maxlength: 50,
                },
                access_level: {
                    required: true,
                    range: [0, 100],
                },
            },
            messages: {
                name: {
                    required: "Role name is required",
                    maxlength: "Role name cannot be more than 50 characters",
                },
                code: {
                    required: "Code is required",
                    maxlength: "Code cannot be more than 50 characters",
                },
                access_level: {
                    required: "Access level is required",
                    range: "Access level must be between 0 and 100",
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
                    roleId: $("#roleId").val(),
                    name: $("#name").val(),
                    code: $("#code").val(),
                    access_level: $("#access_level").val(),
                    is_active: $("#is_active").is(':checked'),
                };
                var storeRoleUrl = "{{ config('apiConstants.API_URLS.ROLES_STORE') }}";
                var updateRoleUrl = "{{ config('apiConstants.API_URLS.ROLES_UPDATE') }}";
                var url = roleId > 0 ? updateRoleUrl : storeRoleUrl;
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
