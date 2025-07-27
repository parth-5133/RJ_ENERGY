<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="userId" value="{{ $userId ?? '' }}">
    <div class="form-floating form-floating-outline mb-3">
        <select class="form-select" id="attendanceStatusSelect" name="attendance_status"
            aria-label="Attendance status select">
            <option value="check_in">Check-in</option>
            <option value="check_out">Check-out</option>
        </select>
        <label for="attendanceStatusSelect">Attendance Status <span style="color:red">*</span></label>
        <span class="text-danger" id="attendance_status-error"></span>
    </div>
    <div class="form-floating form-floating-outline mb-3">
        <input class="form-control" type="date" id="fromDateInput" name="attendance_date" value="{{ date('Y-m-d') }}"
            min="{{ date('Y-m-d', strtotime('-10 days')) }}" max="{{ date('Y-m-d') }}">
        <label for="fromDateInput">Attendance Date <span style="color:red">*</span></label>
        <span class="text-danger" id="attendance_date-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-3">
        <input class="form-control" type="time" id="timeInput" name="attendance_time">
        <label for="timeInput">Time <span style="color:red">*</span></label>
        <span class="text-danger" id="attendance_time-error"></span>
    </div>
    <div class="form-floating form-floating-outline mb-4">
        <textarea class="form-control h-px-100" name="note" id="note" maxlength="255" placeholder="Note" rows="3"></textarea>
        <label for="note">Note<span style="color:red">*</span></label>
        <span class="text-danger" id="note-error"></span>
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
    $(document).ready(function() {
        var Data = {
            employee_id: $("#userId").val(),
        };
        if (userId > 0) {
            fnCallAjaxHttpGetEvent(
                "{{ config('apiConstants.ADMIN_ATTENDANCE_URLS.ADMIN_ATTENDANCE_REQUEST_DATA') }}", Data,
                true, true,
                function(
                    response) {
                    if (response.status === 200 && response.data) {

                        $("#fromDateInput").val(response.data.attendance_date);
                        $("#timeInput").val(response.data.attendance_time);
                        $("#attendanceStatusSelect").val(response.data.attendance_status);
                        $("#note").text(response.data.note);

                    } else {
                        console.log('Failed to retrieve role data.');
                    }
                });
        }

        // Initialize form validation
        $("#commonform").validate({
            rules: {
                attendance_status: {
                    required: true,
                },
                attendance_date: {
                    required: true,
                },
                attendance_time: {
                    required: true,
                },
                note: {
                    required: true,
                    minlength: 15,
                    maxlength: 255,
                },
            },
            messages: {
                attendance_status: {
                    required: "Please select attendance status.",
                },
                attendance_date: {
                    required: "Please select the attendance date.",
                },
                attendance_time: {
                    required: "Please select the time.",
                },
                note: {
                    required: "Please provide a note.",
                    minlength: "Note must be at least 15 characters.",
                    maxlength: "Note cannot exceed 255 characters.",
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
                var postData = {
                    userId: $("#userId").val(),
                    attendance_date: $("#fromDateInput").val(),
                    attendance_time: $("#timeInput").val(),
                    attendance_status: $("#attendanceStatusSelect").val(),
                    note: $("#note").val(),
                };

                var url =
                    "{{ config('apiConstants.EMPLOYEE_ATTENDANCE_URLS.EMPLOYEE_ATTENDANCE_REQUEST') }}";

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
