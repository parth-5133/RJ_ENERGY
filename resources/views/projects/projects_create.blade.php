<form action="javascript:void(0)" id="commonform" name="commonform" class="form-horizontal" method="POST"
    enctype="multipart/form-data">
    <input type="hidden" id="project_Id" name="project_Id" value="{{ $projectId ?? '' }}">
    <input type="hidden" id="params_id" name="params_id" value="{{ $params_id ?? '' }}">
    <div class="row gy-4">
        <!-- Project Name -->
        <div class="col-md-3">
            <div class="form-floating form-floating-outline mb-4">
                <input type="text" class="form-control" name="project_name" id="project_name" maxlength="50"
                    placeholder="Project Name" />
                <label for="project_name">Project Name <span style="color:red">*</span></label>
                <span class="text-danger" id="project_name-error"></span>
            </div>
        </div>

        <div class="col-md-3">
            <!-- Start Date -->
            <div class="form-floating form-floating-outline mb-4">
                <input type="date" class="form-control" name="start_date" id="start_date" max="{{ date('Y-m-d') }}"
                    placeholder="Start Date" />
                <label for="start_date">Start Date <span style="color:red">*</span></label>
                <span class="text-danger" id="start_date-error"></span>
            </div>
        </div>

        <div class="col-md-3">
            <!-- End Date -->
            <div class="form-floating form-floating-outline mb-4">
                <input type="date" class="form-control" name="end_date" id="end_date" placeholder="End Date" />
                <label for="end_date">End Date</label>
                <span class="text-danger" id="end_date-error"></span>
            </div>
        </div>

        <div class="col-md-3">
            <!-- Priority -->
            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" name="priority" id="priority">
                    <option selected value="1">High</option>
                    <option value="2">Medium</option>
                    <option value="3">Low</option>
                </select>
                <label for="priority">Priority <span style="color:red">*</span></label>
                <span class="text-danger" id="priority-error"></span>
            </div>
        </div>

        <div class="col-md-3">
            <!-- Client -->
            <div class="form-floating form-floating-outline mb-4">
                <select class="form-select" id="client" name="client">
                </select>
                <label for="client">Client <span style="color:red">*</span></label>
                <span class="text-danger" id="client-error"></span>
            </div>
        </div>

        <div class="col-md-3">
            <!-- Team Members -->
            <div class="form-floating form-floating-outline mb-4">
                <div class="select2-primary">
                    <select class="select2 form-select" multiple name="team_members[]" id="team_members">
                    </select>
                </div>
                <label for="team_members">Team Members </label>
                <span class="text-danger" id="team_members-error"></span>
            </div>
        </div>

        <div class="col-md-3">
            <!-- Team Leaders -->
            <div class="form-floating form-floating-outline mb-4">
                <div class="select2-primary">
                    <select class="select2 form-select" multiple name="team_leaders[]" id="team_leaders">
                    </select>
                </div>
                <label for="team_leaders">Project Manager/Team Lead </label>
                <span class="text-danger" id="team_leaders-error"></span>
            </div>
        </div>

        <div class="col-md-3">
            <!-- Document Upload -->
            <div class="form-floating form-floating-outline mb-4">
                <input type="file" class="form-control" name="document" id="document" />
                <label for="document">Upload File</label>
                <span class="text-danger" id="document-error"></span>
                <a href="#" id="document-old-name" name="document" target="_blank" class="form-text"></a>
            </div>
        </div>

        <div class="col-md-12">
            <!-- Description -->
            <div class="form-floating form-floating-outline mb-4">
                <textarea class="form-control h-px-100" name="description" id="description" rows="10" cols="80"></textarea>
                <label for="description">Description</label>
                <span class="text-danger" id="description-error"></span>
            </div>
        </div>

        <div class="col-md-3">
            <!-- Active Checkbox -->
            <div class="form-check mb-4" style="padding-left: 2.5rem;">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked />
                <label class="form-check-label" for="is_active">Active</label>
                <span class="text-danger" id="is_active-error"></span>
            </div>
        </div>

        <!-- Submit Buttons -->
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

<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

<script type="text/javascript">
    var projectId = $("#project_Id").val();

    $(document).ready(function() {

        CKEDITOR.replace('description');

        fnCallAjaxHttpGetEvent("{{ config('apiConstants.CLIENT_URLS.CLIENT') }}", null, true, true, function(
            response) {
            if (response.status === 200 && response.data) {

                var $clientDropdown = $("#client");
                $clientDropdown.empty();
                $clientDropdown.append(new Option('Select Client', ''));

                var clients = response.data

                clients.forEach(function(data) {
                    $clientDropdown.append(new Option(data.name, data.id));
                });
            }
        });

        fnCallAjaxHttpGetEvent("{{ config('apiConstants.PROJECTS_URLS.PROJECTS_GET_TEAM_MEMBERS') }}", null,
            true, true,
            function(
                response) {
                if (response.status === 200 && response.data) {

                    var $teamMembersDropdown = $("#team_members");
                    $teamMembersDropdown.empty();
                    $teamMembersDropdown.append(new Option('Select Team Member', ''));

                    var $teamLeadersDropdown = $("#team_leaders");
                    $teamLeadersDropdown.empty();
                    $teamLeadersDropdown.append(new Option('Select Team Leader', ''));

                    var nonClients = response.data

                    nonClients.forEach(function(data) {
                        $teamMembersDropdown.append(new Option(data.name, data.id));
                        $teamLeadersDropdown.append(new Option(data.name, data.id));
                    });

                    if (projectId > 0) {
                        var Url = "{{ config('apiConstants.PROJECTS_URLS.PROJECTS_VIEW') }}";
                        fnCallAjaxHttpGetEvent(Url, {
                            projectId: projectId
                        }, true, true, function(response) {
                            if (response.status === 200 && response.data) {
                                setOldFileNames(response.data);

                                $("#project_name").val(response.data.project_name);
                                $("#project_id").val(response.data.project_id);
                                $("#start_date").val(response.data.start_date);
                                $("#end_date").val(response.data.end_date);
                                $("#priority").val(response.data.priority);
                                $("#client").val(response.data.client);
                                if (response.data.description) {
                                    CKEDITOR.instances.description.setData(response.data
                                        .description);
                                }
                                $("#is_active").prop('checked', response.data.is_active);

                                var teamMembers = [];
                                var teamLeaders = [];

                                if (response.data.projectTeamMappings && response.data
                                    .projectTeamMappings.length > 0) {
                                    response.data.projectTeamMappings.forEach(function(mapping) {
                                        if (mapping.team_type === 1) {
                                            teamMembers.push(mapping.user_id);
                                        } else if (mapping.team_type === 2) {
                                            teamLeaders.push(mapping.user_id);
                                        }
                                    });
                                }

                                if (teamMembers.length > 0) {
                                    $("#team_members").val(teamMembers).trigger('change');
                                }

                                if (teamLeaders.length > 0) {
                                    $("#team_leaders").val(teamLeaders).trigger('change');
                                }

                            } else {
                                console.log('Failed to retrieve project data.');
                            }
                        });
                    }
                } else {
                    console.error('Failed to retrieve user list.');
                }
            });

        function setOldFileNames(data) {
            if (data && data.documents) {
                data.documents.forEach(document => {
                    const fileName = document.file_display_name;
                    const filePath = document.relative_path;
                    const fileLink = "{{ url('/storage/') }}/" + filePath;

                    // Set icon and file name in the <a> tag
                    $("#document-old-name")
                        .html('<i class="mdi mdi-file-document-outline text-primary me-1"></i> ' +
                            fileName)
                        .attr('href', fileLink);
                });
            }
        }

        $("#commonform").validate({
            rules: {
                project_name: {
                    required: true,
                    maxlength: 50,
                },
                project_code: {
                    required: true,
                    maxlength: 50,
                },
                start_date: {
                    required: true,
                    date: true,
                },
                end_date: {
                    required: false,
                    date: true,
                    endDateAfterStartDate: true,
                },
                priority: {
                    required: true,
                },
                client: {
                    required: true,
                    min: 1,
                },
                document: {
                    required: false,
                    extension: "pdf|doc|docx|xls|xlsx|png|jpg|jpeg",
                }
            },
            messages: {
                project_name: {
                    required: "Project Name is required.",
                    maxlength: "Project Name must not exceed 50 characters.",
                },
                project_code: {
                    required: "Project Code is required.",
                    maxlength: "Project Code must not exceed 50 characters.",
                },
                start_date: {
                    required: "Start Date is required.",
                    date: "Please enter a valid date.",
                },
                end_date: {
                    required: "End Date is required.",
                    date: "Please enter a valid date.",
                    endDateAfterStartDate: "End date must be after or equal to the start date",
                },
                priority: {
                    required: "Priority is required.",
                },
                client: {
                    required: "Client is required.",
                    min: "Please select a valid client.",
                },
                document: {
                    extension: "Please upload a valid file (pdf, doc, docx, xls, xlsx, png, jpg, jpeg).",
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

                var formData = new FormData(form);
                formData.append("description", CKEDITOR.instances.description.getData());

                formData.append('is_active', $("#is_active").is(":checked") ? 1 : 0);

                var storeProjectUrl =
                    "{{ config('apiConstants.PROJECTS_URLS.PROJECTS_STORE') }}";
                var updateProjectUrl =
                    "{{ config('apiConstants.PROJECTS_URLS.PROJECTS_UPDATE') }}";
                var url = projectId > 0 ? updateProjectUrl : storeProjectUrl;

                fnCallAjaxHttpPostEventWithoutJSON(url, formData, true, true, function(
                    response) {
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

        $.validator.addMethod("endDateAfterStartDate", function(value, element) {
            var startDate = $("#start_date").val();
            if (!startDate || !value) {
                return true;
            }
            return new Date(value) >= new Date(startDate);
        });
    });
</script>
