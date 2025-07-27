@php
    use App\Enums\LeaveStatus;
    $leaveStatuses = [
        LeaveStatus::APPROVED->value => LeaveStatus::APPROVED->label(),
        LeaveStatus::REJECTED->value => LeaveStatus::REJECTED->label(),
        LeaveStatus::PENDING->value => LeaveStatus::PENDING->label(),
    ];
@endphp
<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="employee_id" name="employee_id" value="{{ $employee_id ?? '' }}">

    <table class="table mb-4">
        <tbody>
            <tr>
                <td class="fw-bold px-0 py-2">Employee ID:</td>
                <td class="px-0 py-2"><span id="Employee_Id" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold px-0 py-2">Employee Name:</td>
                <td class="px-0 py-2"><span id="full_name" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold px-0 py-2">Attendance Status:</td>
                <td class="px-0 py-2"><span id="attendanceStatusSelect" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold px-0 py-2">Note:</td>
                <td class="px-0 py-2"><span id="note" class="text-primary"></span></td>
            </tr>
        </tbody>
    </table>

    <!-- Attendance Date -->
    <div class="form-floating form-floating-outline mb-3 mt-7">
        <input class="form-control" type="date" id="fromDateInput" name="attendance_date">
        <label for="fromDateInput">Attendance Date <span style="color:red">*</span></label>
        <span class="text-danger" id="attendance_date-error"></span>
    </div>
    <!-- Leave Time -->
    <div class="form-floating form-floating-outline mb-3">
        <input class="form-control" type="time" id="timeInput" name="attendance_time">
        <label for="timeInput">Time <span style="color:red">*</span></label>
        <span class="text-danger" id="attendance_time-error"></span>
    </div>
    <!-- Leave Status -->
    <div class="form-floating form-floating-outline mb-3">
        <select class="form-select" id="request_status" name="request_status">
            <option>Select Status</option>
            <option value="1">Approved</option>
            <option value="2">Rejected</option>
            <option value="4">Pending</option>
        </select>
        <label for="request_status">Attendance Status <span style="color:red">*</span></label>
        <span class="text-danger" id="request_status-error"></span>
    </div>
    <!-- Comment -->
    <div class="form-floating form-floating-outline mb-3">
        <textarea class="form-control h-px-100" name="comment" id="comment" maxlength="255" placeholder="Comment"
            rows="3"></textarea>
        <label for="comment">Comment <span id="comment-required" style="color:red ;">*</span></label>
        <span class="text-danger" id="comment-error"></span>
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
    var employee_id = $("#employee_id").val();
    $(document).ready(function() {

        $('#request_status').change(function() {
            const selectedValue = $(this).val();
            if (selectedValue === '1') {
                $('#comment-required').hide();
            } else {
                $('#comment-required').show();
            }
        });

        var Data = {
            employee_id: $("#employee_id").val(),
        };

        fnCallAjaxHttpGetEvent(
            "{{ config('apiConstants.ADMIN_ATTENDANCE_URLS.ADMIN_ATTENDANCE_REQUEST_DATA') }}", Data,
            true, true,
            function(
                response) {
                if (response.status === 200 && response.data) {
                    const leaveStatusMapping = @json($leaveStatuses);

                    const reversedLeaveStatusMapping = Object.fromEntries(
                        Object.entries(leaveStatusMapping).map(([key, value]) => [value, parseInt(
                            key)])
                    );
                    const leaveStatusName = response.data.status;
                    const leaveStatusValue = reversedLeaveStatusMapping[leaveStatusName];
                    // Set the value of the leave status element
                    $("#request_status").val(leaveStatusValue);

                    $("#Employee_Id").text(response.data.Employee_Id);
                    $("#full_name").text(response.data.employee_name);
                    $("#fromDateInput").val(response.data.attendance_date);
                    $("#timeInput").val(response.data.attendance_time);
                    if (response.data.attendance_status === 'check_out') {
                        $("#attendanceStatusSelect").text('Check out');
                    } else if (response.data.attendance_status === 'check_in') {
                        $("#attendanceStatusSelect").text('Check in');
                    }
                    $("#note").text(response.data.note);
                    $("#comment").text(response.data.comment);


                } else {
                    console.log('Failed to retrieve role data.');
                }
            });
    });

    $("#commonform").on("submit", function(e) {
        e.preventDefault();

        var postData = {
            id: $("#employee_id").val(),
            attendance_date: $("#fromDateInput").val(),
            attendance_time: $("#timeInput").val(),
            attendance_status: $("#attendanceStatusSelect").text(),
            request_status: $("#request_status").val(),
            comment: $("#comment").val() // Get the comment value
        };

        var url = "{{ config('apiConstants.ADMIN_ATTENDANCE_URLS.ADMIN_ATTENDANCE_REQUEST_UPDATE') }}";
        fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
            if (response.status === 200) {
                bootstrap.Offcanvas.getInstance(document.getElementById('commonOffcanvas')).hide();
                $('#grid').DataTable().ajax.reload();
                ShowMsg("bg-success", response.message);
            } else {
                ShowMsg("bg-warning", 'The record could not be processed.');
            }
        });
    });
</script>
