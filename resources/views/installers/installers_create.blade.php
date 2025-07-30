<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="installersId" name="installersId" value="{{ $installersId ?? '' }}">

    <div class="form-floating form-floating-outline mb-4">
        <input type="text" class="form-control" name="name" id="name" maxlength="50"
            placeholder="Installer Name" />
        <label for="name">Installer Name <span style="color:red">*</span></label>
        <span class="text-danger" id="name-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="number" class="form-control" name="phone" id="phone" maxlength="15"
            placeholder="Installer Phone" />
        <label for="phone">Installer Phone <span style="color:red">*</span></label>
        <span class="text-danger" id="phone-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <input type="email" class="form-control" name="email" id="email" placeholder="Installer Email" />
        <label for="email">Installer Email <span style="color:red">*</span></label>
        <span class="text-danger" id="email-error"></span>
    </div>

    <div class="form-floating form-floating-outline mb-4">
        <textarea class="form-control" name="address" id="address" placeholder="Installer Address" style="height: 100px"></textarea>
        <label for="address">Installer Address</label>
        <span class="text-danger" id="address-error"></span>
    </div>

    <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
        <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas"><span
                class="tf-icons mdi mdi-cancel me-1"></span>Cancel</button>
        <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
            <span class="tf-icons mdi mdi-checkbox-marked-circle-outline">&nbsp;</span>Submit
        </button>
    </div>
</form>
<script type="text/javascript">
    var installersId = $("#installersId").val();

    $(document).ready(function() {
        if (installersId > 0) {
            var Url = "{{ config('apiConstants.INSTALLERS_URLS.INSTALLERS_VIEW') }}";
            fnCallAjaxHttpGetEvent(Url, {
                installersId: installersId
            }, true, true, function(
                response) {
                if (response.status === 200 && response.data) {
                    $("#name").val(response.data.name);
                    $("#phone").val(response.data.phone);
                    $("#email").val(response.data.email);
                    $("#address").val(response.data.address);
                } else {
                    console.log('Failed to retrieve installer data.');
                }
            });
        }

        // jQuery Validation Setup
        $("#commonform").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 50,
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 15
                },
                email: {
                    required: true,
                    email: true,
                },
                address: {
                    maxlength: 255,
                }
            },
            messages: {
                name: {
                    required: "Installer name is required",
                    maxlength: "Installer name cannot be more than 50 characters",
                },
                phone: {
                    required: "Phone is required",
                    digits: "Please enter a valid phone number",
                    minlength: "Phone number must be at least 10 digits long",
                    maxlength: "Phone number must be at most 15 digits long"
                },
                email: {
                    required: "Email is required",
                    email: "Please enter a valid email address",
                },
                address: {
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
                    installersId: $("#installersId").val(),
                    name: $("#name").val(),
                    phone: $("#phone").val(),
                    email: $("#email").val(),
                    address: $("#address").val(),
                };
                var storeUrl =
                    "{{ config('apiConstants.INSTALLERS_URLS.INSTALLERS_STORE') }}";
                var updateUrl =
                    "{{ config('apiConstants.INSTALLERS_URLS.INSTALLERS_UPDATE') }}";
                var url = installersId > 0 ? updateUrl : storeUrl;
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
