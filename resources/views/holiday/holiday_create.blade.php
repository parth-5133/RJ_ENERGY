<form action="javascript:void(0)" id="holidayForm" name="holidayForm" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="holiday_id" value="{{ $holidayId ?? '' }}">

    <!-- Holiday Name -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="holiday_name" id="holiday_name" maxlength="50"
            placeholder="Holiday Name" />
        <label for="holiday_name">Holiday Name<span style="color:red">*</span></label>
        <span class="text-danger" id="holiday_name-error"></span>
    </div>

    <!-- Holiday Date -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="date" class="form-control" name="holiday_date" id="holiday_date" maxlength="50"
            min="{{ date('Y') . '-01-01' }}" placeholder="Holiday Date" />
        <label for="holiday_date">Holiday Date<span style="color:red">*</span></label>
        <span class="text-danger" id="holiday_date-error"></span>
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
    var holidayId = $("#holiday_id").val();

    $(document).ready(function() {

        if (holidayId > 0) {
            var Url = "{{ config('apiConstants.ADMIN_HOLIDAY_URLS.HOLIDAY_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                holidayId: holidayId
            }, true, true, function(
                response) {
                if (response.status === 200 && response.data) {
                    $("#holiday_name").val(response.data.holiday_name);
                    $("#holiday_date").val(response.data.holiday_date);
                } else {
                    console.log('Failed to retrieve role data.');
                }
            });
        }

        $("#holidayForm").validate({
            rules: {
                holiday_name: {
                    required: true,
                    maxlength: 50,
                },
                holiday_date: {
                    required: true,
                    date: true,
                },
            },
            messages: {
                holiday_name: {
                    required: "Holiday name is required",
                    maxlength: "Holiday name cannot be more than 50 characters",
                },
                holiday_date: {
                    required: "Holiday date is required",
                    date: "Please enter a valid date",
                },
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

                // Gather form data
                var postData = {
                    holiday_id: $("#holiday_id").val(),
                    holiday_name: $("#holiday_name").val(),
                    holiday_date: $("#holiday_date").val(),
                };

                // Determine the URL based on action (create/update)
                var storeHolidayUrl =
                    "{{ config('apiConstants.ADMIN_HOLIDAY_URLS.HOLIDAY_STORE') }}";
                var updateHolidayUrl =
                    "{{ config('apiConstants.ADMIN_HOLIDAY_URLS.HOLIDAY_UPDATE') }}";
                var url = holidayId > 0 ? updateHolidayUrl : storeHolidayUrl;

                // AJAX call to API
                fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                    if (response.status === 200) {
                        // Close offcanvas and reload table
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
