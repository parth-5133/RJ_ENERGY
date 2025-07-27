<form action="javascript:void(0)" id="commonForm" name="commonForm" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="id" name="id" value="{{ $Id ?? '' }}">
    <input type="hidden" id="project_id" name="project_id">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="title" id="title" maxlength="50" placeholder="Title" />
        <label for="title">Title <span style="color:red">*</span></label>
        <span class="text-danger" id="title-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="display_order" id="display_order" maxlength="50"
            placeholder="Display Order" />
        <label for="display_order">Display Order <span style="color:red">*</span></label>
        <span class="text-danger" id="display_order-error"></span>
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

<script type="text/javascript">
    var Id = $("#id").val();

    $(document).ready(function() {
        var Url = "{{ config('apiConstants.KANBAN_URLS.KANBAN_VIEW') }}";
        fnCallAjaxHttpGetEvent(Url, {
            Id: Id
        }, true, true, function(
            response) {
            if (response.status === 200 && response.data) {
                $("#title").val(response.data.column_name);
                $("#display_order").val(response.data.position);
                $("#project_id").val(response.data.project_id);
            } else {
                console.log('Failed to retrieve role data.');
            }
        });
    });

    // jQuery Validation Setup
    $("#commonForm").validate({
        rules: {
            title: {
                required: true,
                maxlength: 50,
            },
            display_order: {
                required: true,
                maxlength: 50,
            },
        },
        messages: {
            title: {
                required: "Title is required",
                maxlength: "Title cannot be more than 50 characters",
            },
            display_order: {
                required: "Display Order is required",
                maxlength: "Display Order cannot be more than 50 characters",
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
        submitHandler: function(form, e) {
            e.preventDefault();

            var postData = {
                id: $("#id").val(),
                title: $("#title").val(),
                display_order: $("#display_order").val(),
            };

            var url = "{{ config('apiConstants.KANBAN_URLS.KANBAN_UPDATE') }}";

            fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                if (response.status === 200) {
                    bootstrap.Offcanvas.getInstance(document.getElementById('commonOffcanvas'))
                        .hide();
                    var projectId = $("#project_id").val();
                    fetchKanbanData(projectId);
                    ShowMsg("bg-success", response.message);
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        }
    });
</script>
