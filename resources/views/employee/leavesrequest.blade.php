<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="LeaveId" value="{{ $LeaveId ?? '' }}">
    <!-- Leave Type ID -->
    <div class="form-floating form-floating-outline mb-4">
        <select class="form-control" id="leave_type_id" name="leave_type_id">
        </select>
        <label for="leave_type_id">Leave Type <span style="color:red">*</span></label>
        <span class="text-danger" id="leave_type_id-error"></span>
    </div>
    <!-- Start Date -->
    <div class="form-floating form-floating-outline mb-4">
        <input class="form-control" type="date" id="start_date" name="start_date" value="{{ date('Y-m-d') }}"
            min="{{ date('Y-m-d', strtotime('-10 days')) }}"  max="{{ date('Y') . '-12-31' }}" />
        <label for="start_date">Start Date <span style="color:red">*</span></label>
        <span class="text-danger" id="start_date-error"></span>
    </div>
    <!-- End Date -->
    <div class="form-floating form-floating-outline mb-4">
        <input class="form-control" type="date" id="end_date" name="end_date" value="{{ date('Y-m-d') }}"
            min="{{ date('Y-m-d', strtotime('-10 days')) }}"  max="{{ date('Y') . '-12-31' }}" />
        <label for="end_date">End Date <span style="color:red">*</span></label>
        <span class="text-danger" id="end_date-error"></span>
    </div>
    <!-- Leave Session -->
    <div class="form-floating form-floating-outline mb-4">
        <select class="form-control" id="leave_session_id" name="leave_session_id">
            <option value="1">Full Day</option>
            <option value="2">First Half</option>
            <option value="3">Second Half</option>
        </select>
        <label for="leave_session_id">Leave Session <span style="color:red">*</span></label>
        <span class="text-danger" id="leave_session_id-error"></span>
    </div>
    <!-- Reason -->
    <div class="form-floating form-floating-outline mb-4">
        <textarea class="form-control h-px-100" name="reason" id="reason" maxlength="255" placeholder="Reason"
            rows="3"></textarea>
        <label for="reason">Leave Reason<span style="color:red">*</span></label>
        <span class="text-danger" id="reason-error"></span>
    </div>
    <!-- Total Leave -->
    <label for="date_difference">Total Leave :</label>
    <span id="date_difference" name="date_difference" readonly>1</span>
    <!-- Form Actions (Submit/Cancel) -->
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
        $('#start_date').on('change', calculateDateDifference);
        $('#end_date').on('change', calculateDateDifference);

        function calculateDateDifference() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                let differenceInDays = 0;

                // Loop through each date between start and end
                let currentDate = new Date(start);
                while (currentDate <= end) {
                    const dayOfWeek = currentDate.getDay(); // 0 = Sunday, 6 = Saturday

                    // Only count weekdays (Monday to Friday)
                    if (dayOfWeek !== 0 && dayOfWeek !== 6) {
                        differenceInDays++;
                    }

                    // Move to the next day
                    currentDate.setDate(currentDate.getDate() + 1);
                }
                // Display the calculated difference in days
                $('#date_difference').text(differenceInDays > 0 ? differenceInDays : 0);
            } else {
                $('#date_difference').text('');
            }
        }

        $('#leave_session_id').on('change', function() {
            const selectedValue = $(this).val();
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();

            if ((selectedValue === '2' || selectedValue === '3') && startDate === endDate) {
                $('#date_difference').text('0.5');
            } else {
                calculateDateDifference();
            }
        });

        fnCallAjaxHttpGetEvent("{{ config('apiConstants.EMPLOYEE_LEAVE_URLS.EMPLOYEE_LEAVE_TYPE') }}", null,
            true, true,
            function(response) {
                if (response.status === 200 && response.data) {
                    var $leaveDropdown = $("#leave_type_id");
                    $leaveDropdown.empty();
                    $leaveDropdown.append(new Option('Select leave type', ''));

                    response.data.forEach(function(data) {
                        $leaveDropdown.append(new Option(data.leave_type_name, data.id));
                    });
                }

                if (LeaveId > 0) {
                    var Data = {
                        LeaveId: $("#LeaveId").val(),
                    };
                    fnCallAjaxHttpGetEvent(
                        "{{ config('apiConstants.EMPLOYEE_LEAVE_URLS.EMPLOYEE_LEAVE_VIEW') }}", Data,
                        true, true,
                        function(response) {
                            if (response.status === 200 && response.data) {

                                $("#leave_type_id").val(response.data.leave_request
                                    .leave_type_id);
                                $("#start_date").val(response.data.leave_request
                                    .start_date);
                                $("#end_date").val(response.data.leave_request
                                    .end_date);
                                $("#leave_session_id").val(response.data.leave_request
                                    .leave_session_id);
                                $("#date_difference").text(response.data.leave_request
                                    .total_days);
                                $("#reason").val(response.data.leave_request
                                    .reason);
                            }
                        });
                }
            });

        $.validator.addMethod("endDateAfterStartDate", function(value, element) {
            var startDate = $("#start_date").val();
            if (!startDate || !value) {
                return true;
            }
            return new Date(value) >= new Date(startDate);
        });

        $("#commonform").validate({
            rules: {
                leave_type_id: {
                    required: true,
                },
                start_date: {
                    required: true,
                    date: true,
                },
                end_date: {
                    required: true,
                    date: true,
                    endDateAfterStartDate: true,
                },
                leave_session_id: {
                    required: true,
                },
                reason: {
                    required: true,
                    minlength: 15,
                    maxlength: 255,
                },
            },
            messages: {
                leave_type_id: {
                    required: "Leave type is required",
                },
                start_date: {
                    required: "Start date is required",
                    date: "Please enter a valid start date",
                },
                end_date: {
                    required: "End date is required",
                    date: "Please enter a valid end date",
                    endDateAfterStartDate: "End date must be after or equal to the start date",
                },
                leave_session_id: {
                    required: "Leave session is required",
                },
                reason: {
                    required: "Leave reason is required",
                    maxlength: "Leave reason cannot exceed 255 characters",
                    minlength: "Leave reason must be at least 15 characters.",
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

                var postData = {
                    LeaveId: $("#LeaveId").val(),
                    leave_type_id: $("#leave_type_id").val(),
                    start_date: $("#start_date").val(),
                    end_date: $("#end_date").val(),
                    leave_session_id: $("#leave_session_id").val(),
                    total_days: $("#date_difference").text(),
                    reason: $("#reason").val(),
                };

                var url = LeaveId > 0 ?
                    "{{ config('apiConstants.EMPLOYEE_LEAVE_URLS.EMPLOYEE_LEAVE_UPDATE') }}" :
                    "{{ config('apiConstants.EMPLOYEE_LEAVE_URLS.EMPLOYEE_LEAVE_STORE') }}";

                fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                    if (response.status === 200) {
                        bootstrap.Offcanvas.getInstance(document.getElementById(
                            'commonOffcanvas')).hide();
                        $('#grid').DataTable().ajax.reload();
                        ShowMsg("bg-success", response.message);
                    } else {
                        ShowMsg("bg-danger", 'The record could not be processed.');
                    }
                });
            }
        });
    });
</script>
