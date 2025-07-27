<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="FinancialYearId" value="{{ $FinancialYearId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="date" class="form-control" name="from_date" id="from_date" />
        <label for="from_date">From Date<span style="color:red">*</span></label>
        <span class="text-danger" id="from_date-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="date" class="form-control" name="to_date" id="to_date" />
        <label for="to_date">To Date<span style="color:red">*</span></label>
        <span class="text-danger" id="to_date-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="display_year" id="display_year" placeholder="Display Year" />
        <label for="display_year">Display Year<span style="color:red">*</span></label>
        <span class="text-danger" id="display_year-error"></span>
    </div>

    <div class="form-check mb-4" style="padding-left: 2.5rem;">
        <input class="form-check-input" type="checkbox" name="is_currentYear" id="is_currentYear" />
        <label class="form-check-label" for="is_currentYear">Is Current Year</label>
        <span class="text-danger" id="is_currentYear-error"></span>
    </div>

    <div class="form-check mb-4" style="padding-left: 2.5rem;">
        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" />
        <label class="form-check-label" for="is_active"> Active </label>
        <span class="text-danger" id="is_active-error"></span>
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
    var FinancialYearId = $("#FinancialYearId").val();

    $(document).ready(function() {
        if (FinancialYearId > 0) {
            var Url = "{{ config('apiConstants.FINANCIAL_YEAR_URLS.FINANCIAL_YEAR_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                FinancialYearId: FinancialYearId
            }, true, true, function(
                response) {
                if (response.status === 200 && response.data) {
                    $("#from_date").val(response.data.from_date);
                    $("#to_date").val(response.data.to_date);
                    $("#display_year").val(response.data.display_year);
                    $("#is_currentYear").prop('checked', response.data.is_currentYear);
                    $("#is_active").prop('checked', response.data.is_active);
                } else {
                    console.log('Failed to retrieve role data.');
                }
            });
        }

        // jQuery Validation Setup
        $("#commonform").validate({
            rules: {
                from_date: {
                    required: true
                },
                to_date: {
                    required: true
                },
                display_year: {
                    required: true
                }
            },
            messages: {
                from_date: {
                    required: "From date is required"
                },
                to_date: {
                    required: "To date is required"
                },
                display_year: {
                    required: "Display year is required"
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
                    FinancialYearId: $("#FinancialYearId").val(),
                    from_date: $("#from_date").val(),
                    to_date: $("#to_date").val(),
                    display_year: $("#display_year").val(),
                    is_currentYear: $("#is_currentYear").is(':checked'),
                    is_active: $("#is_active").is(':checked'),
                };

                var storeUrl =
                    "{{ config('apiConstants.FINANCIAL_YEAR_URLS.FINANCIAL_YEAR_STORE') }}";
                var updateUrl =
                    "{{ config('apiConstants.FINANCIAL_YEAR_URLS.FINANCIAL_YEAR_UPDATE') }}";
                var url = FinancialYearId > 0 ? updateUrl : storeUrl;
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
