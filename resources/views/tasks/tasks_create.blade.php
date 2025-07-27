<!-- Tabs Navigation -->
<ul class="nav nav-tabs mb-6" id="taskTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#tabDetails" type="button"
            role="tab" aria-controls="tabDetails" aria-selected="true">Details</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="statuslog-tab" data-bs-toggle="tab" data-bs-target="#tabStatusLog" type="button"
            role="tab" aria-controls="tabStatusLog" aria-selected="false">Status Log</button>
    </li>
</ul>
<div class="tab-content p-0" id="taskTabContent">

    <div class="tab-pane fade show active" id="tabDetails" role="tabpanel" aria-labelledby="details-tab">
        <form action="javascript:void(0)" id="projectForm" name="projectForm" class="form-horizontal" method="POST"
            enctype="multipart/form-data">
            <input type="hidden" id="taskId" name="taskId" value="{{ $taskId ?? '' }}">
            <input type="hidden" id="roleCode" name="roleCode" value="{{ $role_code ?? '' }}">
            <input type="hidden" id="statusId" name="statusId" value="{{ $statusId ?? '' }}">

            <div class="row gy-4">
                @if ($role_code === config('roles.SUPERADMIN') || $role_code === config('roles.ADMIN') || $role_code === config('roles.CLIENT'))
                    <!-- Project -->
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select" id="project_id" name="project_id"></select>
                            <label for="project_id">Project <span style="color:red">*</span></label>
                            <span class="text-danger" id="project_id-error"></span>
                        </div>
                    </div>

                    <!-- Title -->
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="title" id="title" maxlength="50"
                                placeholder="Title" />
                            <label for="title">Title <span style="color:red">*</span></label>
                            <span class="text-danger" id="title-error"></span>
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="date" class="form-control" name="due_date" id="due_date"
                                placeholder="Due Date" min="{{ isset($taskId) && $taskId > 0 ? '' : date('Y-m-d') }}"
                                value="{{ date('Y-m-d') }}" />
                            <label for="due_date">Due Date <span style="color:red">*</span></label>
                            <span class="text-danger" id="due_date-error"></span>
                        </div>
                    </div>
                    @if ($taskId > 0)
                        <!-- Start Time -->
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="datetime-local" class="form-control" name="start_time" id="start_time"
                                    placeholder="Start Time" />
                                <label for="start_time">Start Time</label>
                                <span class="text-danger" id="start_time-error"></span>
                            </div>
                        </div>

                        <!-- End Time -->
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="datetime-local" class="form-control" name="end_time" id="end_time"
                                    placeholder="End Time" />
                                <label for="end_time">End Time</label>
                                <span class="text-danger" id="end_time-error"></span>
                            </div>
                        </div>
                    @endif

                    <!-- Team Members -->
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select" id="team_members" name="team_members"></select>
                            <label for="team_members">Team Members <span style="color:red">*</span></label>
                            <span class="text-danger" id="team_members-error"></span>
                        </div>
                    </div>

                    <!-- Priority -->
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select" id="priority" name="priority">
                                <option value="">Select</option>
                                <option value="1">High</option>
                                <option value="2">Medium</option>
                                <option value="3">Low</option>
                            </select>
                            <label for="priority">Priority <span style="color:red">*</span></label>
                            <span class="text-danger" id="priority-error"></span>
                        </div>
                    </div>

                    @if ($taskId > 0)
                        <!-- Status -->
                        <div class="col-lg-3 col-md-4 col-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <select class="form-select" id="status" name="status"></select>
                                <label for="status">Status <span style="color:red">*</span></label>
                                <span class="text-danger" id="status-error"></span>
                            </div>
                        </div>
                    @endif

                    <!-- Document Upload -->
                    <div class="col-lg-3 col-md-4 col-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="file" class="form-control" name="document" id="document" />
                            <label for="document">Upload File</label>
                            <span class="text-danger" id="document-error"></span>
                            <a href="#" id="document-old-name" name="document" target="_blank"
                                class="form-text"></a>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-md-12">
                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control h-px-100" name="description" id="description" rows="10" cols="80"></textarea>
                            <label for="description">Description</label>
                            <span class="text-danger" id="description-error"></span>
                        </div>
                    </div>
                @endif

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
    </div>
    <div class="tab-pane fade" id="tabStatusLog" role="tabpanel" aria-labelledby="statuslog-tab">
            <div class="timeline">
                <div id="statusLog"></div>
            </div>
    </div>
</div>

<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

<script type="text/javascript">
    var taskId = $("#taskId").val();
    var selectedStatus = null;
    $(document).ready(function() {

        if ($('#description').length) {
            CKEDITOR.replace('description', {
                filebrowserImageUploadUrl: '/api/v1/UploadFiles',
                filebrowserImageUploadMethod: 'form',
                filebrowserBrowseUrl: ''
            });
        }

        if ($('#comments').length) {
            CKEDITOR.replace('comments', {
                filebrowserImageUploadUrl: '/api/v1/UploadFiles',
                filebrowserImageUploadMethod: 'form',
                filebrowserBrowseUrl: ''
            });
        }

        $('#project_id').on('change', function() {

            var project_id = $("#project_id").val();

            fnCallAjaxHttpGetEvent(
                "{{ config('apiConstants.TASKS_URLS.TASKS_STATUS') }}", {
                    projectId: project_id
                },
                true,
                true,
                function(response) {
                    if (response.status === 200 &&
                        response.data) {
                        var $status = $("#status");
                        $status.empty();

                        response.data.forEach(function(
                            data) {
                            var option =
                                new Option(data
                                    .column_name,
                                    data.id);
                            $status.append(
                                option);
                        });
                    } else {
                        console.log(
                            "Failed to retrieve status data."
                        );
                    }
                }
            );
        });

        fnCallAjaxHttpGetEvent("{{ config('apiConstants.PROJECTS_URLS.PROJECTS') }}", null, true, true,
            function(
                response) {
                if (response.status === 200 && response.data) {

                    var $projectDropdown = $("#project_id");
                    $projectDropdown.empty();
                    $projectDropdown.append(new Option('Select Project', ''));

                    response.data.forEach(function(data) {
                        $projectDropdown.append(new Option(data.project_name, data.id));
                    });

                    fnCallAjaxHttpGetEvent("{{ config('apiConstants.USER_API_URLS.USER') }}", null,
                        true,
                        true,
                        function(
                            response) {
                            if (response.status === 200 && response.data) {

                                var $teamMembersDropdown = $("#team_members");
                                $teamMembersDropdown.empty();
                                $teamMembersDropdown.append(new Option('Select Team Member', ''));

                                var nonClients = response.data.filter(function(data) {
                                    return data.role_name !== "Client";
                                });

                                nonClients.forEach(function(data) {
                                    $teamMembersDropdown.append(new Option(data.name, data
                                        .id));
                                });

                                if (taskId > 0) {


                                    var Url = "{{ config('apiConstants.TASKS_URLS.TASKS_VIEW') }}";
                                    fnCallAjaxHttpGetEvent(
                                        Url, {
                                            taskId: taskId
                                        },
                                        true,
                                        true,
                                        function(response) {
                                            if (response.status === 200 && response.data) {
                                                selectedStatus = parseInt(response.data.status, 10);
                                                setOldFileNames(response.data);
                                                $("#title").val(response.data.title);
                                                $("#due_date").val(response.data.due_date);
                                                $("#start_time").val(response.data.start_time);
                                                $("#end_time").val(response.data.end_time);
                                                $("#project_id").val(response.data.project);
                                                $("#priority").val(response.data.priority);
                                                $("#team_members").val(response.data.user_id)
                                                    .trigger(
                                                        "change");
                                                if (CKEDITOR.instances.description) {
                                                    if (response.data.description) {
                                                        CKEDITOR.instances.description.setData(
                                                            response.data.description);
                                                    } else {
                                                        CKEDITOR.instances.description.setData(
                                                            '');
                                                    }
                                                } else {
                                                    console.warn(
                                                        'CKEditor instance for "description" is not initialized.'
                                                    );
                                                }
                                                if (response.data.project > 0) {
                                                    fnCallAjaxHttpGetEvent(
                                                        "{{ config('apiConstants.TASKS_URLS.TASKS_STATUS') }}", {
                                                            projectId: response.data.project
                                                        },
                                                        true,
                                                        true,
                                                        function(response) {
                                                            if (response.status === 200 &&
                                                                response.data) {
                                                                var $status = $("#status");
                                                                $status.empty();
                                                                response.data.forEach(
                                                                    function(
                                                                        data) {
                                                                        var option =
                                                                            new Option(
                                                                                data
                                                                                .column_name,
                                                                                data.id
                                                                            );
                                                                        if (selectedStatus ===
                                                                            data.id) {
                                                                            option
                                                                                .selected =
                                                                                true;
                                                                        }
                                                                        $status.append(
                                                                            option);
                                                                    });
                                                            } else {
                                                                console.log(
                                                                    "Failed to retrieve status data."
                                                                );
                                                            }
                                                        }
                                                    );
                                                }
                                            } else {
                                                console.log("Failed to retrieve project data.");
                                            }
                                        }
                                    );
                                }

                            } else {
                                console.error('Failed to retrieve user list.');
                            }
                        });
                }
            });

        function setOldFileNames(data) {
            if (data && data.documents) {
                data.documents.forEach(document => {
                    const fileName = document.file_display_name;
                    const filePath = document.relative_path;
                    const fileLink = "{{ url('/storage/') }}/" + filePath;

                    // Set MDI icon + file name inside the <a> tag
                    $("#document-old-name")
                        .html('<i class="mdi mdi-file-document-outline text-primary me-1"></i>' +
                            fileName)
                        .attr('href', fileLink);
                });
            }
        }

        // jQuery Validation Setup
        $("#projectForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 50,
                },
                due_date: {
                    required: true,
                    date: true,
                },
                team_members: {
                    required: true,
                },
                project_id: {
                    required: true,
                },
                priority: {
                    required: true,
                },
                document: {
                    extension: "pdf|doc|docx|xls|xlsx|png|jpg|jpeg",
                },
            },
            messages: {
                title: {
                    required: "Title is required.",
                    maxlength: "Title must not exceed 50 characters.",
                },
                due_date: {
                    required: "Due Date is required.",
                    date: "Please enter a valid date.",
                },
                team_members: {
                    required: "Team Member is required.",
                },
                project_id: {
                    required: "Project is required.",
                },
                priority: {
                    required: "Priority is required.",
                },
                document: {
                    extension: "Please upload a valid file (pdf, doc, docx, xls, xlsx, png, jpg, jpeg).",
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

                let formData = new FormData(form);

                const descriptionData = CKEDITOR.instances.description ?
                    CKEDITOR.instances.description.getData() :
                    '';
                formData.append("description", descriptionData);

                const commentsData = CKEDITOR.instances.comments ?
                    CKEDITOR.instances.comments.getData() :
                    '';
                formData.append("comments", commentsData);

                var storeTasksUrl = "{{ config('apiConstants.TASKS_URLS.TASKS_STORE') }}";
                var updateTasksUrl = "{{ config('apiConstants.TASKS_URLS.TASKS_UPDATE') }}";
                var url = taskId > 0 ? updateTasksUrl : storeTasksUrl;

                fnCallAjaxHttpPostEventWithoutJSON(url, formData, true, true, function(
                    response) {
                    if (response.status === 200) {
                        var statusId = $("#statusId").val();
                        bootstrap.Offcanvas.getInstance(document.getElementById(
                            'commonOffcanvas')).hide();
                        if (statusId === "1") {
                            location.reload();
                            ShowMsg("bg-success", response.message);
                        } else {
                            $('#grid').DataTable().ajax.reload();
                            ShowMsg("bg-success", response.message);
                            KanbanData(response.data.project_id);
                        }
                    } else {
                        ShowMsg("bg-warning", "The record could not be processed.");
                    }
                });
            }
        });
    });

    $("#statuslog-tab").on("click", function() {
        var taskId = $("#taskId").val();
        var Url = "{{ config('apiConstants.TASKS_URLS.TASKS_STATUS_LOG') }}";
        fnCallAjaxHttpGetEvent(
            Url, {
                taskId: taskId
            },
            true,
            true,
            function(response) {
                if (response.status === 200 && response.data) {
                    var statusLog = response.data;
                    var statusLogHtml = "";

                    statusLog.forEach(function(log) {
                        let duration = "-";
                        if (log.duration_seconds && log.duration_seconds > 0) {
                            const h = String(Math.floor(log.duration_seconds / 3600)).padStart(2,
                                '0');
                            const m = String(Math.floor((log.duration_seconds % 3600) / 60))
                                .padStart(2, '0');
                            duration = `${h}:${m}`;
                        }

                        const icon = '<i class="mdi mdi-arrow-right"></i>';
                        const bgColor = log.is_manual === 1 ? 'warning' : 'primary';

                        const typeBadge = log.is_manual === 1 ?
                            '<span class="badge bg-warning rounded">Manual</span>' :
                            '<span class="badge bg-success rounded">Auto</span>';

                        const movedAt = new Date(log.moved_at).toLocaleString('en-IN', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        statusLogHtml += `
    <div class="timeline-item d-flex mb-6">
        <div class="flex-shrink-0">
            <div class="timeline-icon bg-${bgColor} text-white rounded-circle p-2">
                ${icon}
            </div>
        </div>
        <div class="flex-grow-1 ms-3 mt-1">
            <h6 class="mb-1">Task moved from
                <span class="badge bg-secondary rounded">${log.from_column_name ?? '-'}</span> to
                <span class="badge bg-info rounded">${log.to_column_name ?? '-'}</span>
            </h6>
            <small class="text-muted d-block mb-1">
                <i class="mdi mdi-account-outline me-1"></i> Moved By: <strong>${log.moved_by_name ?? 'System'}</strong>
            </small>
            <small class="text-muted">Moved At: ${movedAt}</small><br>
            <small class="text-muted">Start: ${log.entered_start_time ?? '-'} • End: ${log.entered_end_time ?? '-'} • Duration: ${duration}</small><br>
            ${typeBadge}
        </div>
    </div>`;
                    });

                    $("#statusLog").html(statusLogHtml);
                } else {
                    console.log("Failed to retrieve data.");
                }
            }
        );
    });
</script>
