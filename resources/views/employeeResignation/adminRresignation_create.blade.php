<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="resignationId" value="{{ $resignationId ?? '' }}">
    <div class="row gy-4">
        <div class="col-md-6">
            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="employee_id" id="employee_id"></select>
                <label for="employee_id">Employee <span style="color:red">*</span></label>
                <span class="text-danger" id="employee_id-error"></span>
            </div>
        </div>
        <!-- Resignation Date -->
        <div class="col-md-6">
            <div class="form-floating form-floating-outline mb-4">
                <input type="date" class="form-control" id="resignation_date" name="resignation_date">
                <label for="resignation_date">Resignation Date <span style="color:red">*</span></label>
                <span class="text-danger" id="resignation_date-error"></span>
            </div>
        </div>
        <!-- Document Upload -->
        <div class="col-md-6">
            <div class="form-floating form-floating-outline mb-4">
                <input type="file" class="form-control" id="document" name="document" />
                <label for="document">Resignation Letter</label>
                <span class="text-danger" id="document-error"></span>
                <a href="#" id="document-old-name" name="document" target="_blank" class="form-text"></a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating form-floating-outline mb-4">
                <input type="date" class="form-control" id="last_working_date" name="last_working_date">
                <label for="last_working_date">Last Working Date <span style="color:red">*</span></label>
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
        <!-- Reason -->
        <div class="col-md-12">
            <div class="form-floating form-floating-outline mb-4">
                <textarea class="form-control h-px-100" id="reason" name="reason" rows="10" cols="80"></textarea>
                <label for="reason">Reason</label>
                <span class="text-danger" id="reason-error"></span>
            </div>
        </div>
        <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
            <button class="btn rounded btn-secondary me-2" id="cancel" type="button" data-bs-dismiss="offcanvas">
                <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
            </button>
            <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
                <span class="tf-icons mdi mdi-checkbox-marked-circle-outline">&nbsp;</span>Submit
            </button>
        </div>
    </div>
</form>
<script type="text/javascript">
    var resignationId = $("#resignationId").val();

    $(document).ready(function() {
        if ($('#reason').length) {
            CKEDITOR.replace('reason', {
                filebrowserImageUploadUrl: '/api/V1/UploadFiles',
                filebrowserImageUploadMethod: 'form',
                filebrowserBrowseUrl: ''
            });
        }
        loadEmployeeDropdown('employee_id', '{{ config('apiConstants.USER_API_URLS.USER') }}', false, true);
    });

    if (resignationId > 0) {
        var Url =
            "{{ config('apiConstants.EMPLOYEE_RESIGNATION_URLS.EMPLOYEE_RESIGNATION_VIEW') }}";
        fnCallAjaxHttpGetEvent(Url, {
            resignationId: resignationId
        }, true, true, function(
            response) {
            if (response.status === 200 && response.data) {
                setOldFileNames(response.data);
                $("#resignation_date").val(response.data.resignation_date);
                if (CKEDITOR.instances.reason) {
                    CKEDITOR.instances.reason.setData(response.data.reason);
                }
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

            // Set MDI icon + file name inside the <a> tag
            $("#document-old-name")
                .html('<i class="mdi mdi-file-document-outline text-primary me-1"></i>' +
                    fileName)
                .attr('href', fileLink);
        }
    }

    // jQuery Validation Setup
    $("#commonform").validate({
        rules: {
            employee_id: {
                required: true
            },
            resignation_date: {
                required: true,
                date: true
            },
            last_working_date: {
                required: true,
                date: true
            },
            status: {
                required: true
            },
            document: {
                extension: "pdf|doc|docx|jpg|jpeg|png"
            }
        },
        messages: {
            employee_id: {
                required: "Employee selection is required"
            },
            resignation_date: {
                required: "Resignation date is required",
                date: "Please enter a valid date"
            },
            last_working_date: {
                required: "Last working date is required",
                date: "Please enter a valid date"
            },
            status: {
                required: "Status is required"
            },
            document: {
                extension: "Only PDF, Word documents, and images are allowed"
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
            event.preventDefault();

            var resignationId = $("#resignationId").val();

            let formData = new FormData(form);

            const reason = CKEDITOR.instances.reason ?
                CKEDITOR.instances.reason.getData() :
                '';
            formData.append("reason", reason);

            var storeUrl =
                "{{ config('apiConstants.EMPLOYEE_RESIGNATION_URLS.EMPLOYEE_RESIGNATION_STORE') }}";
            var updateUrl =
                "{{ config('apiConstants.EMPLOYEE_RESIGNATION_URLS.EMPLOYEE_RESIGNATION_UPDATE') }}";
            var url = resignationId > 0 ? updateUrl : storeUrl;

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
