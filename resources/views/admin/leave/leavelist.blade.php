@php
    use App\Enums\LeaveStatus;
    use App\Enums\LeaveSession;

    $leaveStatuses = [
        LeaveStatus::APPROVED->value => LeaveStatus::APPROVED->label(),
        LeaveStatus::REJECTED->value => LeaveStatus::REJECTED->label(),
        LeaveStatus::CANCELLED->value => LeaveStatus::CANCELLED->label(),
        LeaveStatus::PENDING->value => LeaveStatus::PENDING->label(),
    ];

    $leaveSession = [
        LeaveSession::FULL_DAY->value => LeaveSession::FULL_DAY->label(),
        LeaveSession::FIRST_HALF->value => LeaveSession::FIRST_HALF->label(),
        LeaveSession::SECOND_HALF->value => LeaveSession::SECOND_HALF->label(),
    ];

@endphp
<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="LeaveId" value="{{ $LeaveId ?? '' }}">
    <input type="hidden" id="date_difference" name="date_difference">

    <!-- Employee Information Table -->
    <table class="table mb-4">
        <tbody>
            <tr>
                <td class="fw-bold px-0 py-2">Employee ID:</td>
                <td class="px-0 py-2"><span id="employee_id" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold px-0 py-2">Employee Name:</td>
                <td class="px-0 py-2"><span id="full_name" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold px-0 py-2">Leave Type:</td>
                <td class="px-0 py-2"><span id="Leave_Type" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold px-0 py-2">From:</td>
                <td class="px-0 py-2"><span id="From" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold px-0 py-2">To:</td>
                <td class="px-0 py-2"><span id="To" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold px-0 py-2">Leave Session:</td>
                <td class="px-0 py-2"><span id="Leave_Session" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold px-0 py-2">Number of days:</td>
                <td class="px-0 py-2"><span id="Number_of_days" class="text-primary"></span></td>
            </tr>
        </tbody>
    </table>

    <!-- Leave Status -->
    <div class="form-floating form-floating-outline mb-4 mt-7">
        <select class="form-select" id="leave_status" name="leave_status">
            <option value="1">Approved</option>
            <option value="2">Rejected</option>
            <option value="3">Cancelled</option>
            <option value="4">Pending</option>
        </select>
        <label for="leave_status">Leave Status <span style="color:red">*</span></label>
        <span class="text-danger" id="leave_status-error"></span>
    </div>

    <!-- Comment -->
    <div class="form-floating form-floating-outline mb-4">
        <textarea class="form-control h-px-100" name="comment" id="comment" maxlength="255" placeholder="Comment"
            rows="3"></textarea>
        <label for="comment">Comment <span id="comment-required" style="color:red ; display: none;">*</span></label>
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
    var LeaveId = $("#LeaveId").val();
    $(document).ready(function() {


        $('#leave_status').change(function() {
            const selectedValue = $(this).val();
            if (selectedValue === '1') {
                $('#comment-required').hide();
            } else {
                $('#comment-required').show();
            }
        });

        if (LeaveId > 0) {
            var Data = {
                LeaveId: $("#LeaveId").val(),
            };

            fnCallAjaxHttpGetEvent("{{ config('apiConstants.EMPLOYEE_LEAVE_URLS.EMPLOYEE_LEAVE_VIEW') }}", Data,
                true, true,
                function(response) {
                    if (response.status === 200 && response.data) {

                        const leaveStatusMapping = @json($leaveStatuses);
                        const leaveSessionMapping = @json($leaveSession);

                        const reversedLeaveStatusMapping = Object.fromEntries(
                            Object.entries(leaveStatusMapping).map(([key, value]) => [value, parseInt(
                                key)])
                        );

                        const leaveStatusName = response.data.leave_request.status;
                        const leaveStatusValue = reversedLeaveStatusMapping[leaveStatusName];

                        // Set the value of the leave status element
                        $("#leave_status").val(leaveStatusValue);

                        const leaveSession = response.data.leave_request.leave_session_id;
                        const leaveSessionValue = leaveSessionMapping[leaveSession];

                        // Set the value of the Leave Session element
                        $("#Leave_Session").text(leaveSessionValue);

                        $("#date_difference").val(response.data.leave_request.total_days);
                        $("#comment").val(response.data.leave_request.comments);
                        $("#Leave_Type").text(response.data.leave_request.leave_type_name);

                        const startDate = new Date(response.data.leave_request.start_date);
                        const endDate = new Date(response.data.leave_request.end_date);

                        const formattedStartDate = formatDate(startDate);
                        const formattedEndDate = formatDate(endDate);

                        $("#From").text(formattedStartDate);
                        $("#To").text(formattedEndDate);

                        $("#Number_of_days").text(response.data.leave_request.total_days);
                        $("#employee_id").text(response.data.employee.employee_id);
                        $("#full_name").text(response.data.employee.full_name);
                    }
                });
        }
    });

    $("#commonform").on("submit", function(e) {
        e.preventDefault();
        var postData = {
            LeaveId: $("#LeaveId").val(),
            total_days: $("#date_difference").val(),
            comment: $("#comment").val(),
            leave_status: $("#leave_status").val(),
        };
        var url = "{{ config('apiConstants.ADMIN_LEAVE_URLS.ADMIN_LEAVE_UPDATE') }}";
        fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
            if (response.status === 200) {
                bootstrap.Offcanvas.getInstance(document.getElementById('commonOffcanvas'))
                    .hide();
                location.reload();
                ShowMsg("bg-success", response.message);
            } else {
                ShowMsg("bg-warning", 'The record could not be processed.');
            }
        });
    });

    function formatDate(date) {
        let day = date.getDate();
        let month = date.getMonth() + 1; // Months are zero-based in JavaScript
        let year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }
</script>
