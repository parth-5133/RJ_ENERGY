<form action="javascript:void(0)" id="customerForm" name="customerForm" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="quotesId" name="quotesId" value="{{ $quotesId ?? '' }}">

    <!-- Section 1: Customer Basic Details -->
    <h5 class="fw-bold mb-3 mt-4">👤 Customer Basic Details</h5>
    <div class="row">
        <!-- Name -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="customer_name" id="customer_name" placeholder="Name" />
                <label for="customer_name">Name <span class="text-danger">*</span></label>
                <span class="text-danger" id="customer_name-error"></span>
            </div>
        </div>
        <!-- Age -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="age" id="age" placeholder="Age" />
                <label for="age">Age <span class="text-danger">*</span></label>
                <span class="text-danger" id="age-error"></span>
            </div>
        </div>
        <!-- Mobile -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="tel" class="form-control" name="mobile" id="mobile" maxlength="10"
                    placeholder="Aadhar-linked Mobile" />
                <label for="mobile">Aadhar-linked Mobile <span class="text-danger">*</span></label>
                <span class="text-danger" id="mobile-error"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Alternate Mobile -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="tel" class="form-control" name="alternate_mobile" id="alternate_mobile" maxlength="10"
                    placeholder="Alternate Mobile" />
                <label for="alternate_mobile">Alternate Mobile</label>
                <span class="text-danger" id="alternate_mobile-error"></span>
            </div>
        </div>
        <!-- Aadhar -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="aadhar" id="aadhar" placeholder="Aadhar Number" />
                <label for="aadhar">Aadhar Number <span class="text-danger">*</span></label>
                <span class="text-danger" id="aadhar-error"></span>
            </div>
        </div>
        <!-- PAN -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="pan" id="pan" placeholder="PAN Number" />
                <label for="pan">PAN Number</label>
                <span class="text-danger" id="pan-error"></span>
            </div>
        </div>
    </div>

    <!-- Section 3: Quotation -->
    <h5 class="fw-bold mb-3 mt-4">🧾 Quotation</h5>
    <div class="row">
        <!-- Quotation -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_" id="quotation_">
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <label for="quotation_">Is Quotation <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_-error"></span>
            </div>
        </div>
        <!-- Quotation Amount -->
        <div class="col-md-4 mb-4 quotation-dependent">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="quotation_amount" id="quotation_amount"
                    placeholder="Quotation Amount">
                <label for="quotation_amount">Quotation Amount <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_amount-error"></span>
            </div>
        </div>
        <!-- Quotation Date -->
        <div class="col-md-4 mb-4 quotation-dependent">
            <div class="form-floating form-floating-outline">
                <input type="date" class="form-control" name="quotation_date" id="quotation_date"
                    placeholder="Quotation Date">
                <label for="quotation_date">Quotation Date <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_date-error"></span>
            </div>
        </div>
    </div>
    <div class="row quotation-dependent">
        <!-- Entered By -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_by" id="quotation_by">
                    <option selected disabled value="">Select</option>
                    <option value="1">John Smith</option>
                    <option value="2">Jane Doe</option>
                </select>
                <label for="quotation_by">Entered By <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_by-error"></span>
            </div>
        </div>
        <!-- Quotation Status -->
        <div class="col-md-4 mb-4">
            <div class="form-floating form-floating-outline">
                <select class="form-select" name="quotation_status" id="quotation_status">
                    <option value="">Select Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Agreed">Agreed</option>
                </select>
                <label for="quotation_status">Quotation Status <span class="text-danger">*</span></label>
                <span class="text-danger" id="quotation_status-error"></span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
        <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas">
            <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
        </button>
        <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
            <span class="tf-icons mdi mdi-checkbox-marked-circle-outline"></span>&nbsp;Submit
        </button>
    </div>
</form>

<script type="text/javascript">
    var quotesId = $("#quotesId").val();

    $(document).ready(function() {
        if (quotesId > 0) {
            var Url = "{{ config('apiConstants.QUOTATION_URLS.QUOTATION_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                quotesId: quotesId
            }, true, true, function(
                response) {
                if (response.status === 200 && response.data) {
                    $("#customer_name").val(response.data.customer_name);
                    $("#age").val(response.data.age);
                    $("#mobile").val(response.data.mobile);
                    $("#alternate_mobile").val(response.data.alternate_mobile);
                    $("#aadhar").val(response.data.aadhar);
                    $("#pan").val(response.data.pan);
                    $("#quotation_").val(response.data.required);
                    $("#quotation_amount").val(response.data.amount);
                    $("#quotation_date").val(response.data.date);
                    $("#quotation_by").val(response.data.by);
                    $("#quotation_status").val(response.data.status);
                } else {
                    console.log('Failed to retrieve role data.');
                }
            });
        }
    });


    $("#customerForm").validate({
        rules: {
            customer_name: {
                required: true,
                maxlength: 50,
            },
            age: {
                required: true,
                digits: true,
                minlength: 1,
                maxlength: 3
            },
            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            alternate_mobile: {
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            aadhar: {
                required: true,
                digits: true,
                minlength: 12,
                maxlength: 12
            },
            pan: {
                required: false,
                maxlength: 10
            },
            quotation_: {
                required: true,
            },
            quotation_amount: {
                required: true,
                number: true,
            },
            quotation_date: {
                required: true,
                date: true,
            },
            quotation_status: {
                required: true,
            },
            quotation_by: {
                required: true,
            }
        },
        messages: {
            customer_name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 50 characters",
            },
            age: {
                required: "Age is required",
                digits: "Please enter a valid age",
                minlength: "Age must be at least 1 year old",
                maxlength: "Age cannot exceed 3 digits",
            },
            mobile: {
                required: "Mobile is required",
                digits: "Please enter a valid mobile number",
                minlength: "Mobile number must be at least 10 digits long",
                maxlength: "Mobile number must be at most 15 digits long"
            },
            alternate_mobile: {
                digits: "Please enter a valid mobile number",
                minlength: "Mobile number must be at least 10 digits long",
                maxlength: "Mobile number must be at most 15 digits long"
            },
            aadhar: {
                required: "Aadhar is required",
                digits: "Please enter a valid Aadhar number",
                minlength: "Aadhar number must be 12 digits long",
                maxlength: "Aadhar number must be 12 digits long"
            },
            pan: {
                maxlength: "PAN number must be 10 characters long"
            },
            quotation_: {
                required: "Quotation selection is required",
            },
            quotation_amount: {
                required: "Quotation Amount is required",
                number: "Please enter a valid number",
            },
            quotation_date: {
                required: "Quotation Date is required",
                date: "Please enter a valid date",
            },
            quotation_status: {
                required: "Quotation Status is required",
            },
            quotation_by: {
                required: "Quotation By is required",
            }
        },
        errorPlacement: function(error, element) {
            var errorId = element.attr("name") + "-error";
            $("#" + errorId).text(error.text()).show();
            element.addClass("is-invalid");
        },
        success: function(label, element) {
            var errorId = $(element).attr("name") + "-error";
            $("#" + errorId).text("").hide();
            $(element).removeClass("is-invalid");
        },
        submitHandler: function(form, event) {
            event.preventDefault();

            var formData = new FormData(form);

            var storeUrl = "{{ config('apiConstants.QUOTATION_URLS.QUOTATION_STORE') }}";
            var updateUrl = "{{ config('apiConstants.QUOTATION_URLS.QUOTATION_UPDATE') }}";
            var url = quotesId > 0 ? updateUrl : storeUrl;

            fnCallAjaxHttpPostEventWithoutJSON(url, formData, true, true, function(response) {
                if (response.status === 200) {
                    bootstrap.Offcanvas.getInstance(document.getElementById('commonOffcanvas'))
                        .hide();
                    $('#grid').DataTable().ajax.reload();
                    ShowMsg("bg-success", response.message);
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        }
    });
</script>
