<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST" enctype="multipart/form-data">
    <input type="hidden" id="shiftId" value="{{ $shiftId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="shift_name" id="shift_name" maxlength="50" placeholder="Shift Name" />
        <label for="shift_name">Shift Name<span style="color:red">*</span></label>
        <span class="text-danger" id="shift_name-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="time" class="form-control" name="from_time" id="from_time" maxlength="50" placeholder="From Time" />
        <label for="from_time">From Time<span style="color:red">*</span></label>
        <span class="text-danger" id="from_time-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="time" class="form-control" name="to_time" id="to_time" maxlength="50" placeholder="To Time" />
        <label for="to_time">To Time<span style="color:red">*</span></label>
        <span class="text-danger" id="to_time-error"></span>
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
    var shiftId = $("#shiftId").val();
    $(document).ready(function() {
        if (shiftId > 0) {
            var Url = "{{ config('apiConstants.SHIFT_URLS.SHIFT_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                shiftId: shiftId
            }, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    $("#shift_name").val(response.data.shift_name);
                    $("#from_time").val(response.data.from_time);
                    $("#to_time").val(response.data.to_time);
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
            shift_name: {
                required: true,
                maxlength: 50,
            },
            from_time: {
                required: true,
            },
            to_time: {
                required: true,
            },
        },
        messages: {
            shift_name: {
                required: "Shift name is required",
                maxlength: "Shift name cannot be more than 50 characters",
            },
            from_time: {
                required: "From time is required",
            },
            to_time: {
                required: "To time is required",
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
                shiftId: $("#shiftId").val(),
                shift_name: $("#shift_name").val(),
                from_time: $("#from_time").val(),
                to_time: $("#to_time").val(),
                is_active: $("#is_active").is(':checked'),
            };
            var storeRoleUrl = "{{ config('apiConstants.SHIFT_URLS.SHIFT_STORE') }}";
            var updateRoleUrl = "{{ config('apiConstants.SHIFT_URLS.SHIFT_UPDATE') }}";
            var url = shiftId > 0 ? updateRoleUrl : storeRoleUrl;
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
