<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="leaveTypeId" value="{{ $leaveTypeId ?? '' }}">
    <input type="hidden" id="old_max_days" name="old_max_days">
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="leave_type_name" id="leave_type_name" maxlength="50"
            placeholder="Leave Type Name" />
        <label for="leave_type_name">Leave Type Name <span style="color:red">*</span></label>
        <span class="text-danger" id="leave_type_name-error"></span>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="max_days" id="max_days" maxlength="50"
            placeholder="Max Days" />
        <label for="max_days">Max Days<span style="color:red">*</span></label>
        <span class="text-danger" id="max_days-error"></span>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="carry_forward_max_balance" id="carry_forward_max_balance"
            maxlength="50" placeholder="Carry Forward Max Balance" />
        <label for="carry_forward_max_balance">Carry Forward Max Balance </label>
        <span class="text-danger" id="carry_forward_max_balance-error"></span>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <input type="date" class="form-control" name="expiry_date" id="expiry_date" />
        <label for="name">Expiry Date </label>
        <span class="text-danger" id="name-error"></span>
    </div>
    <div class="form-check mb-4" style="padding-left: 2.5rem;">
        <input class="form-check-input" type="checkbox" name="is_currentYear" id="is_currentYear" />
        <label class="form-check-label" for="is_currentYear">Apply for current year employees</label>
        <span class="text-danger" id="is_currentYear-error"></span>
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
    var leaveTypeId = $("#leaveTypeId").val();

    $(document).ready(function() {
        // Initialize the form validation
        $("#commonform").validate({
            rules: {
                leave_type_name: {
                    required: true,
                    maxlength: 50
                },
                max_days: {
                    required: true,
                    number: true,
                    maxlength: 50
                },
                carry_forward_max_balance: {
                    number: true,
                    maxlength: 50
                },
                expiry_date: {
                    date: true
                },
                is_currentYear: {
                    required: false
                }
            },
            messages: {
                leave_type_name: {
                    required: "Please enter the leave type name.",
                    maxlength: "Leave type name cannot exceed 50 characters."
                },
                max_days: {
                    required: "Please enter the maximum number of days.",
                    number: "Please enter a valid number.",
                    maxlength: "Max days cannot exceed 50 characters."
                },
                carry_forward_max_balance: {
                    number: "Please enter a valid number.",
                    maxlength: "Carry forward max balance cannot exceed 50 characters."
                },
                expiry_date: {
                    date: "Please enter a valid date."
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
                var postData = {
                    leaveTypeId: $("#leaveTypeId").val(),
                    leave_type_name: $("#leave_type_name").val(),
                    max_days: $("#max_days").val(),
                    old_max_days: $("#old_max_days").val(),
                    carry_forward_max_balance: $("#carry_forward_max_balance").val(),
                    expiry_date: $("#expiry_date").val(),
                    is_currentYear: $("#is_currentYear").is(':checked'),
                };

                var url = leaveTypeId > 0 ?
                    "{{ config('apiConstants.ADMIN_LEAVE_TYPE_URLS.ADMIN_LEAVE_TYPE_UPDATE') }}" :
                    "{{ config('apiConstants.ADMIN_LEAVE_TYPE_URLS.ADMIN_LEAVE_TYPE_STORE') }}";

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

        // Pre-fill data for edit mode
        if (leaveTypeId > 0) {
            var Data = {
                leaveTypeId: $("#leaveTypeId").val(),
            };
            fnCallAjaxHttpGetEvent("{{ config('apiConstants.ADMIN_LEAVE_TYPE_URLS.ADMIN_LEAVE_TYPE_VIEW') }}",
                Data, true,
                true,
                function(response) {
                    if (response.status === 200 && response.data) {
                        $("#leave_type_name").val(response.data.leave_type_name);
                        $("#max_days").val(response.data.max_days);
                        $("#old_max_days").val(response.data.max_days);
                        $("#carry_forward_max_balance").val(response.data.carry_forward_max_balance);
                        $("#expiry_date").val(response.data.expiry_date);
                        $("#is_currentYear").prop('checked', response.data.is_currentYear);
                    }
                });
        }
    });
</script>
