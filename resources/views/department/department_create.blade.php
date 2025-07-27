<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="departmentId" value="{{ $departmentId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="name" id="name" maxlength="50"
            placeholder="Department Name" />
        <label for="name">Department Name<span style="color:red">*</span></label>
        <span class="text-danger" id="name-error"></span>
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
    var departmentId = $("#departmentId").val();

    $(document).ready(function() {
        if (departmentId > 0) {
            var Url = "{{ config('apiConstants.DEPARTMENT_URLS.DEPARTMENT_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                departmentId: departmentId
            }, true, true, function(
                response) {
                if (response.status === 200 && response.data) {
                    $("#name").val(response.data.name);
                    $("#is_active").prop('checked', response.data.is_active);
                } else {
                    console.log('Failed to retrieve role data.');
                }
            });
        }
    });

    // jQuery Validation Setup
    $("#commonform").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
            },
        },
        messages: {
            name: {
                required: "Department name is required",
                maxlength: "Department name cannot be more than 50 characters",
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
                departmentId: $("#departmentId").val(),
                name: $("#name").val(),
                is_active: $("#is_active").is(':checked'),
            };
            var storeRoleUrl = "{{ config('apiConstants.DEPARTMENT_URLS.DEPARTMENT_STORE') }}";
            var updateRoleUrl = "{{ config('apiConstants.DEPARTMENT_URLS.DEPARTMENT_UPDATE') }}";
            var url = departmentId > 0 ? updateRoleUrl : storeRoleUrl;
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
