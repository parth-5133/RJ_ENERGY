<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="clientId" name="clientId" value="{{ $clientId ?? '' }}">

    <!-- Aadhar -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="file" class="form-control" name="aadhar" id="aadhar" />
        <label for="aadhar"> Aadhar </label>
        <span class="text-danger" id="aadhar-error"></span>
        <a href="#" id="aadhar-old-name" target="_blank" class="form-text"></a>
    </div>

    <!-- PAN -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="file" class="form-control" name="pan" id="pan" />
        <label for="pan"> PAN </label>
        <span class="text-danger" id="pan-error"></span>
        <a href="#" id="pan-old-name" target="_blank" class="form-text"></a>
    </div>

    <!-- Light Bill -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="file" class="form-control" name="light_bill" id="light_bill" />
        <label for="light_bill"> Light Bill </label>
        <span class="text-danger" id="light_bill-error"></span>
        <a href="#" id="light-bill-old-name" target="_blank" class="form-text"></a>
    </div>

    <!-- Bank Details -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="file" class="form-control" name="bank_details" id="bank_details" />
        <label for="bank_details"> Bank Details (Cancelled Cheque / Passbook) </label>
        <span class="text-danger" id="bank-details-error"></span>
        <a href="#" id="bank-details-old-name" target="_blank" class="form-text"></a>
    </div>

    <!-- Bank Statement -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="file" class="form-control" name="bank_statement" id="bank_statement" />
        <label for="bank_statement"> Bank Statement (Last 6 Months) </label>
        <span class="text-danger" id="bank-statement-error"></span>
        <a href="#" id="bank-statement-old-name" target="_blank" class="form-text"></a>
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
    var clientId = $("#clientId").val();

    $(document).ready(function() {

        $("#commonform").on("submit", function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var storeProjectUrl = "{{ config('apiConstants.CLIENT_URLS.CLIENT_UPLOAD_DOCUMENT') }}";

            fnCallAjaxHttpPostEventWithoutJSON(storeProjectUrl, formData, true, true, function(
                response) {
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
    });
</script>
