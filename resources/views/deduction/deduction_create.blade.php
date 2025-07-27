<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="deductionId" value="{{ $deductionId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="deduction " id="deduction" maxlength="50"
            placeholder="Deduction" />
        <label for="deduction">Deduction <span style="color:red">*</span></label>
        <span class="text-danger" id="deduction-error"></span>
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

<script>
    var deductionId = $("#deductionId").val();

    if (deductionId > 0) {
        var Url = "{{ config('apiConstants.DEDUCTION_URLS.DEDUCTION_VIEW') }}";
        fnCallAjaxHttpGetEvent(Url, {
            deductionId: deductionId
        }, true, true, function(
            response) {
            if (response.status === 200 && response.data) {
                $("#deduction").val(response.data.deduction_name);
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
        },
        messages: {
            name: {
                required: "Allowance name is required",
                maxlength: "Allowance name cannot be more than 50 characters",
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
                deductionId: $("#deductionId").val(),
                deduction_name: $("#deduction").val(),
                is_active: $("#is_active").is(':checked'),
            };
            var storeRoleUrl = "{{ config('apiConstants.DEDUCTION_URLS.DEDUCTION_STORE') }}";
            var updateRoleUrl = "{{ config('apiConstants.DEDUCTION_URLS.DEDUCTION_UPDATE') }}";
            var url = deductionId > 0 ? updateRoleUrl : storeRoleUrl;
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
