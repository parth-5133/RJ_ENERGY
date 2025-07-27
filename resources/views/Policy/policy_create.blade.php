<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="policyId" value="{{ $policyId ?? '' }}">
    <div class="row gy-4">
        <!-- Policy Name -->
        <div class="col-md-4">
            <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" name="policy_name" id="policy_name" maxlength="100"
                    placeholder="Policy Name" />
                <label for="policy_name">Policy Name<span style="color:red">*</span></label>
                <span class="text-danger" id="policy_name-error"></span>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Policy Effective Date -->
            <div class="form-floating form-floating-outline mb-4">
                <input type="date" class="form-control" name="effective_date" id="effective_date"
                    placeholder="Effective Date" />
                <label for="effective_date">Policy Effective Date<span style="color:red">*</span></label>
                <span class="text-danger" id="effective_date-error"></span>
            </div>
        </div>

        <!-- Policy Expiration Date -->
        <div class="col-md-4">
            <div class="form-floating form-floating-outline mb-4">
                <input type="date" class="form-control" name="expiration_date" id="expiration_date"
                    placeholder="Expiration Date" />
                <label for="expiration_date">Policy Expiration Date</label>
                <span class="text-danger" id="expiration_date-error"></span>
            </div>
        </div>

        <!-- Policy Document -->
        <div class="col-md-4">
            <div class="mb-4">
                <label for="policy_document">Policy Document</label>
                <input type="file" class="form-control" name="policy_document" id="policy_document" />
                <span class="text-danger" id="policy_document-error"></span>
                <a href="#" id="document-old-name" name="document" target="_blank" class="form-text"></a>
            </div>
        </div>

        <div class="col-md-12">
            <!-- Policy Description -->
            <div class="form-floating form-floating-outline mb-4">
                <textarea class="form-control h-px-100" name="policy_description" id="policy_description" rows="10" cols="80"></textarea>
                <label for="policy_description">Policy Description</label>
                <span class="text-danger" id="policy_description-error"></span>
            </div>
        </div>

        <!-- Status -->
        <div class="col-md-1">
            <div class="mb-4">
                <div class="form-check mb-4" style="padding-left: 2.5rem;">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked />
                    <label class="form-check-label" for="is_active">Active</label>
                    <span class="text-danger" id="is_active-error"></span>
                </div>
            </div>
        </div>

        <!-- Display to Employees -->
        <div class="col-md-2">
            <div class="mb-4">
                <div class="form-check mb-4" style="padding-left: 2.5rem;">
                    <input class="form-check-input" type="checkbox" name="display_to_employee"
                        id="display_to_employee" />
                    <label class="form-check-label" for="display_to_employee">Display to Employee</label>
                    <span class="text-danger" id="display_to_employee-error"></span>
                </div>
            </div>
        </div>

        <!-- Display to Clients -->
        <div class="col-md-2">
            <div class="mb-4">
                <div class="form-check mb-4" style="padding-left: 2.5rem;">
                    <input class="form-check-input" type="checkbox" name="display_to_client" id="display_to_client" />
                    <label class="form-check-label" for="display_to_client">Display to Client</label>
                    <span class="text-danger" id="display_to_client-error"></span>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
            <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas">
                <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
            </button>
            <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
                <span class="tf-icons mdi mdi-checkbox-marked-circle-outline">&nbsp;</span>Submit
            </button>
        </div>
    </div>
</form>

<script type="text/javascript">
    var policyId = $("#policyId").val();

    CKEDITOR.replace('policy_description');

    if (policyId > 0) {
        var Url = "{{ config('apiConstants.POLICY_URLS.POLICY_VIEW') }}";
        fnCallAjaxHttpGetEvent(Url, {
            policyId: policyId
        }, true, true, function(response) {
            if (response.status === 200 && response.data) {
                setOldFileNames(response.data);
                $("#policy_name").val(response.data.policy_name);
                $("#effective_date").val(response.data.effective_date);
                $("#expiration_date").val(response.data.expiration_date);
                $("#is_active").prop('checked', response.data.is_active);
                $("#display_to_employee").prop('checked', response.data.display_to_employee === 1);
                $("#display_to_client").prop('checked', response.data.display_to_client === 1);

                if (CKEDITOR.instances.policy_description) {
                    CKEDITOR.instances.policy_description.on('instanceReady', function() {
                        CKEDITOR.instances.policy_description.setData(response.data.policy_description);
                    });
                }
            } else {
                console.log('Failed to retrieve policy data.');
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

    $(document).ready(function() {
        // jQuery Validation Setup
        $("#commonform").validate({
            rules: {
                policy_name: {
                    required: true,
                    maxlength: 100
                },
                effective_date: {
                    required: true
                }
            },
            messages: {
                policy_name: {
                    required: "Policy name is required",
                    maxlength: "Policy name cannot be more than 100 characters"
                },
                effective_date: {
                    required: "Policy Effective date is required"
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

                var formData = new FormData(form);
                formData.append("policy_description", CKEDITOR.instances.policy_description
                    .getData());

                formData.append('is_active', $("#is_active").is(":checked") ? 1 : 0);
                formData.append('display_to_employee', $("#display_to_employee").is(":checked") ?
                    1 : 0);
                formData.append('display_to_client', $("#display_to_client").is(":checked") ? 1 :
                    0);
                formData.append('policyId', $("#policyId").val());

                var storeUrl = "{{ config('apiConstants.POLICY_URLS.POLICY_STORE') }}";
                var updateUrl =
                    "{{ config('apiConstants.POLICY_URLS.POLICY_UPDATE') }}";
                var url = policyId > 0 ? updateUrl : storeUrl;

                fnCallAjaxHttpPostEventWithoutJSON(url, formData, true, true, function(response) {
                    if (response.status === 200) {
                        bootstrap.Offcanvas.getInstance(document
                            .getElementById(
                                'commonOffcanvas')).hide();
                        var params_id = $("#params_id").val();
                        if (params_id) {
                            location.reload();
                            ShowMsg("bg-success", response.message);
                        } else {
                            $('#grid').DataTable().ajax.reload();
                            ShowMsg("bg-success", response.message);
                        }
                    } else {
                        ShowMsg("bg-warning",
                            'The record could not be processed.');
                    }
                });
            }
        });
    });
</script>
