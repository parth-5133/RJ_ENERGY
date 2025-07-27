<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="resignationId" value="{{ $resignationId ?? '' }}">
    <div class="row mb-4 g-4">
        <div class="col-sm-6 col-lg-4">
            <div
                class="d-flex flex-column flex-lg-row align-items-center justify-content-between p-4 text-center border h-100 rounded card card-border-shadow-danger shadow-none">
                <h5 class="text-black font-bold m-0">Employee</h5>
                <div class="item-badges">
                    <div class="badge rounded bg-label-primary" id="employee_name">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div
                class="d-flex flex-column flex-lg-row align-items-center justify-content-between p-4 text-center border h-100 rounded card card-border-shadow-primary shadow-none">
                <h5 class="text-black font-bold m-0">Resignation Date</h5>
                <div class="text-primary" id="resignation_date"></div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div
                class="d-flex flex-column flex-lg-row align-items-center justify-content-between p-4 text-center border h-100 rounded card card-border-shadow-success shadow-none z-0">
                <h5 class="text-black font-bold m-0">File</h5>
                <div class="uploaded-file" id="uploadedFile"></div>
            </div>
        </div>

    </div>
    <!-- Reason Section -->
    <div class="card border shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-2">
            </div>
            <p class="text-muted" id="reason"></p>
        </div>
    </div>
    <div class="row gy-4">
        <div class="col-md-6">
            <div class="form-floating form-floating-outline mb-4">
                <input type="date" class="form-control" id="last_working_date" name="last_working_date">
                <label for="last_working_date">Last Working Date</label>
                <span class="text-danger" id="last_working_date-error"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="status" id="status">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <label for="status">Status <span style="color:red">*</span></label>
                <span class="text-danger" id="status-error"></span>
            </div>
        </div>
    </div>
    <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
        <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas">
            <span class="tf-icons mdi mdi-cancel me-1"></span> Cancel
        </button>
        <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
            <span class="tf-icons mdi mdi-checkbox-marked-circle-outline">&nbsp;</span> Submit
        </button>
    </div>
</form>
<script src="{{ asset('app_js/employee-dropdown.js') }}"></script>
<script type="text/javascript">
    var resignationId = $("#resignationId").val();

    if (resignationId > 0) {
        var Url =
            "{{ config('apiConstants.EMPLOYEE_RESIGNATION_URLS.EMPLOYEE_RESIGNATION_VIEW') }}";
        fnCallAjaxHttpGetEvent(Url, {
            resignationId: resignationId
        }, true, true, function(
            response) {
            if (response.status === 200 && response.data) {
                setOldFileNames(response.data);
                $("#employee_name").text(response.data.employee_name);
                $("#resignation_date").text(moment(response.data.resignation_date).format('DD/MM/YYYY'));
                $("#reason").html(response.data.reason);
                $("#status").val(response.data.status);
                $("#last_working_date").val(response.data.last_working_date);
            } else {
                console.log('Failed to retrieve role data.');
            }
        });
    }

    function setOldFileNames(data) {
        if (data && data.document) {
            const fileName = data.document.file_display_name;
            const filePath = data.document.relative_path;
            const fileLink = "{{ url('/storage/') }}/" + filePath;

            // Display file in the uploadedFile div
            $("#uploadedFile").html('<a href="' + fileLink + '" target="_blank">' + fileName + '</a>');
        }
    }

    // jQuery Validation Setup
    $("#commonform").validate({
        rules: {
            last_working_date: {
                required: function() {
                    return $("#status").val() === "Approved";
                },
                date: true
            },
            status: {
                required: true
            }
        },
        messages: {
            last_working_date: {
                required: "Last working date is required",
                date: "Please enter a valid date"
            },
            status: {
                required: "Status is required"
            }
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
            var formData = new FormData(form);
            var url =
                "{{ config('apiConstants.EMPLOYEE_RESIGNATION_URLS.EMPLOYEE_RESIGNATION_UPDATE') }}";

            formData.append("resignationId", resignationId);

            fnCallAjaxHttpPostEventWithoutJSON(url, formData, true, true, function(
                response) {
                if (response.status === 200) {
                    var statusId = $("#statusId").val();
                    bootstrap.Offcanvas.getInstance(document.getElementById(
                        'commonOffcanvas')).hide();
                    if (statusId === "1") {
                        location.reload();
                        ShowMsg("bg-success", response.message);
                    } else {
                        $('#grid').DataTable().ajax.reload();
                        ShowMsg("bg-success", response.message);
                    }
                } else {
                    ShowMsg("bg-warning", "The record could not be processed.");
                }
            });
        }
    });
</script>
