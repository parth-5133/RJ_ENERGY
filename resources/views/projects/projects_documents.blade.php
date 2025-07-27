<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="projectId" name="projectId" value="{{ $projectId ?? '' }}">

    <!-- Document Upload -->
    <div class="form-floating form-floating-outline mb-4">
        <input type="file" class="form-control" name="document" id="document" />
        <label for="document">Upload File <span style="color:red">*</span></label>
        <span class="text-danger" id="document-error"></span>
        <a href="#" id="document-old-name" name="document" target="_blank" class="form-text"></a>
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
    var projectId = $("#projectId").val();

    $(document).ready(function() {

        $("#commonform").on("submit", function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var storeProjectUrl = "{{ config('apiConstants.PROJECTS_URLS.PROJECTS_UPLOAD_DOCUMENT') }}";

            fnCallAjaxHttpPostEventWithoutJSON(storeProjectUrl, formData, true, true, function(
                response) {
                if (response.status === 200) {
                    bootstrap.Offcanvas.getInstance(document
                        .getElementById(
                            'commonOffcanvas')).hide();
                    location.reload();
                    ShowMsg("bg-success", response.message);
                } else {
                    ShowMsg("bg-warning",
                        'The record could not be processed.');
                }
            });

        });
    });
</script>
