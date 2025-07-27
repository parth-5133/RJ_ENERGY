<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="userId" value="{{ $userId ?? '' }}">
    <table class="table mb-4">
        <tbody>
            <tr>
                <td class="fw-bold">Employee ID:</td>
                <td><span id="employee_id" class="text-primary"></span></td>
            </tr>
            <tr>
                <td class="fw-bold">Employee Name:</td>
                <td><span id="full_name" class="text-primary"></span></td>
            </tr>
        </tbody>
    </table>

    <div id="dynamicFieldsContainer"></div>

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
        if (userId > 0) {
            var Url = "{{ config('apiConstants.ADMIN_LEAVE_REPORT_URLS.LEAVE_REPORT') }}";
            fnCallAjaxHttpGetEvent(Url, {
                userId: userId
            }, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    // Set user information
                    $("#employee_id").text(response.data.user.employee_id);
                    $("#full_name").text(response.data.user.full_name);

                    // Generate dynamic leave fields
                    var leaveBalances = response.data.leaveBalances;
                    var dynamicFieldsHtml = '';

                    leaveBalances.forEach(function(leave) {
                        dynamicFieldsHtml += `
                        <div class="row mb-3 justify-content-between align-items-center">
                            <label class="col-sm-6 col-form-label" for="leave-${leave.id}">
                                ${leave.leave_type_name}
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="${leave.id}" name="leave_balances[${leave.leave_type_id}]" value="${leave.balance}" />
                            </div>
                        </div>`;
                    });

                    // Inject fields into the container
                    $("#dynamicFieldsContainer").html(dynamicFieldsHtml);
                }
            });
        }

        $("#commonform").on("submit", function(e) {
            e.preventDefault();

            var postData = {
                userId: userId,
                leaveBalances: {}
            };

            // Collect values of dynamically generated input fields
            $("#dynamicFieldsContainer input").each(function() {
                var leaveTypeId = $(this).attr("name").match(/\d+/)[
                    0]; // Extract the leave_type_id from the input name
                var balance = $(this).val(); // Get the value of the input field

                postData.leaveBalances[leaveTypeId] = balance;
            });

            var url =
                "{{ config('apiConstants.ADMIN_LEAVE_REPORT_URLS.LEAVE_REPORT_UPDATE') }}";

            fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                if (response.status === 200) {
                    bootstrap.Offcanvas.getInstance(document.getElementById('commonOffcanvas'))
                        .hide();
                    $('#grid').DataTable().ajax.reload();
                    ShowMsg("bg-success", response.message);
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        });

    });
</script>
