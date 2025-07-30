<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="manageBankId" name="manageBankId" value="{{ $manageBankId ?? '' }}">

    <!-- Bank Name -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name" />
        <label for="bank_name">Bank Name <span style="color:red">*</span></label>
        <span class="text-danger" id="bank_name-error"></span>
    </div>

    <!-- Branch Name -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="branch_name" id="branch_name" placeholder="Branch Name" />
        <label for="branch_name">Branch Name <span style="color:red">*</span></label>
        <span class="text-danger" id="branch_name-error"></span>
    </div>

    <!-- Branch Manager Phone -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="branch_manager_phone" id="branch_manager_phone"
            placeholder="Branch Manager Phone" />
        <label for="branch_manager_phone">Branch Manager Phone <span style="color:red">*</span></label>
        <span class="text-danger" id="branch_manager_phone-error"></span>
    </div>

    <!-- Loan Manager Phone -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="loan_manager_phone" id="loan_manager_phone"
            placeholder="Loan Manager Phone" />
        <label for="loan_manager_phone">Loan Manager Phone <span style="color:red">*</span></label>
        <span class="text-danger" id="loan_manager_phone-error"></span>
    </div>

    <!-- IFSC Code -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="ifsc_code" id="ifsc_code" placeholder="IFSC Code" />
        <label for="ifsc_code">IFSC Code <span style="color:red">*</span></label>
        <span class="text-danger" id="ifsc_code-error"></span>
    </div>

    <!-- Address -->
    <div class="form-floating form-floating-outline mb-4">
        <textarea class="form-control" name="address" id="address" placeholder="Branch Address" style="height: 100px"></textarea>
        <label for="address">Branch Address</label>
        <span class="text-danger" id="address-error"></span>
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
    var manageBankId = $("#manageBankId").val();

    $(document).ready(function() {
        if (manageBankId > 0) {
            var Url = "{{ config('apiConstants.MANAGE_BANK_URLS.MANAGE_BANK_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                manageBankId: manageBankId
            }, true, true, function(
                response) {
                if (response.status === 200 && response.data) {
                    $("#bank_name").val(response.data.bank_name);
                    $("#branch_name").val(response.data.branch_name);
                    $("#branch_manager_phone").val(response.data.branch_manager_phone);
                    $("#loan_manager_phone").val(response.data.loan_manager_phone);
                    $("#ifsc_code").val(response.data.ifsc_code);
                    $("#address").val(response.data.address);
                } else {
                    console.log('Failed to retrieve role data.');
                }
            });
        }

        // jQuery Validation Setup
        $("#commonform").validate({
            rules: {
                bank_name: {
                    required: true,
                    maxlength: 50,
                },
                branch_name: {
                    required: true,
                    maxlength: 50,
                },
                branch_manager_phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                loan_manager_phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                ifsc_code: {
                    required: true,
                    maxlength: 11,
                },
                address: {
                    required: true,
                    maxlength: 255,
                }
            },
            messages: {
                bank_name: {
                    required: "Bank name is required",
                    maxlength: "Bank name cannot be more than 50 characters",
                },
                branch_name: {
                    required: "Branch name is required",
                    maxlength: "Branch name cannot be more than 50 characters",
                },
                branch_manager_phone: {
                    required: "Branch manager phone is required",
                    digits: "Please enter a valid phone number",
                    minlength: "Phone number must be at least 10 digits long",
                    maxlength: "Phone number must be at most 15 digits long"
                },
                loan_manager_phone: {
                    required: "Loan manager phone is required",
                    digits: "Please enter a valid phone number",
                    minlength: "Phone number must be at least 10 digits long",
                    maxlength: "Phone number must be at most 15 digits long"
                },
                ifsc_code: {
                    required: "IFSC code is required",
                    maxlength: "IFSC code cannot be more than 11 characters",
                },
                address: {
                    required: "Address is required",
                    maxlength: "Address cannot be more than 255 characters",
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

                var postData = {
                    manageBankId: $("#manageBankId").val(),
                    bank_name: $("#bank_name").val(),
                    branch_name: $("#branch_name").val(),
                    branch_manager_phone: $("#branch_manager_phone").val(),
                    loan_manager_phone: $("#loan_manager_phone").val(),
                    ifsc_code: $("#ifsc_code").val(),
                    address: $("#address").val(),
                };
                var storeRoleUrl =
                    "{{ config('apiConstants.MANAGE_BANK_URLS.MANAGE_BANK_STORE') }}";
                var updateRoleUrl =
                    "{{ config('apiConstants.MANAGE_BANK_URLS.MANAGE_BANK_UPDATE') }}";
                var url = manageBankId > 0 ? updateRoleUrl : storeRoleUrl;
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
