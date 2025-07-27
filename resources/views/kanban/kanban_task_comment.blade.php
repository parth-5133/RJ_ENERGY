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
        <form action="javascript:void(0)" id="commonForm" name="commonForm" class="form-horizontal" method="POST"
            enctype="multipart/form-data">
            <!-- Hidden Inputs -->
            <input type="hidden" id="id" name="id" value="{{ $taskId ?? '' }}">
            <input type="hidden" id="taskTitle" name="taskTitle"
                value="{{ ($task_id ?? '') . ' - ' . ($taskTitle ?? '') }}">
            <input type="hidden" id="project_id" name="project_id">

            <!-- Top Summary Cards -->
            <div class="row mb-4 g-4">
                <!-- Priority -->
                <div class="col-sm-6 col-lg-3">
                    <div
                        class="d-flex flex-row align-items-center justify-content-between px-4 py-md-2 py-4 gap-2 h-100 text-center border card card-border-shadow-danger shadow-none">
                        <h5 class="text-black font-bold m-0">Priority</h5>
                        <div class="item-badges">
                            <div class="badge bg-label-danger" id="priority">High</div>
                        </div>
                    </div>
                </div>

                <!-- Due Date -->
                <div class="col-sm-6 col-lg-3">
                    <div
                        class="d-flex flex-row align-items-center justify-content-between px-4 py-md-2 py-4 gap-2 h-100 text-center border card card-border-shadow-primary shadow-none">
                        <h5 class="text-black font-bold m-0">Due date</h5>
                        <div class="text-primary" id="due_date"></div>
                    </div>
                </div>

                <!-- Status -->
                <div class="col-sm-6 col-lg-3">
                    <div
                        class="d-flex flex-row align-items-center justify-content-between px-4 py-md-2 py-4 gap-2 h-100 text-center border card card-border-shadow-warning shadow-none">
                        <h5 class="text-black font-bold m-0">Status</h5>
                        <div
                            class="form-floating form-floating-outline d-flex align-items-center justify-content-end w-100">
                            <select class="form-select custom-select border-0 shadow-none" id="status"
                                name="status">
                                <!-- Options populated dynamically -->
                            </select>
                        </div>
                    </div>
                </div>

                <!-- File -->
                <div class="col-sm-6 col-lg-3">
                    <div
                        class="d-flex flex-row align-items-center justify-content-between px-4 py-md-2 py-4 gap-2 h-100 text-center border card card-border-shadow-success shadow-none z-0">
                        <h5 class="text-black font-bold m-0">File</h5>
                        <div class="uploaded-file" id="uploadedFile"></div>
                    </div>
                </div>

                <!-- Start Time -->
                <div class="col-sm-6 col-lg-6">
                    <div
                        class="d-flex flex-column flex-lg-row align-items-center justify-content-lg-between px-4 py-lg-2 pt-4 gap-2 h-100 text-center border card card-border-shadow-info shadow-none">
                        <h5 class="text-black font-bold m-0 text-nowrap">Start Time</h5>
                        <div class="form-floating form-floating-outline w-lg-auto w-100">
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time">
                        </div>
                    </div>
                </div>

                <!-- End Time -->
                <div class="col-sm-6 col-lg-6">
                    <div
                        class="d-flex flex-column flex-lg-row align-items-center justify-content-lg-between px-4 py-lg-2 pt-4 gap-2 h-100 text-center border card card-border-shadow-secondary shadow-none">
                        <h5 class="text-black font-bold m-0 text-nowrap">End Time</h5>
                        <div class="form-floating form-floating-outline w-lg-auto w-100">
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Details Section -->
            <div class="card border shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-2">
                        <div id="user_initials" class="user-initials text-center me-2"></div>
                        <div>
                            <span id="user_name" class="fw-bold"></span><br>
                            <small id="created_at"></small>
                        </div>
                    </div>
                    <p class="text-muted">{!! $taskDescription ? $taskDescription : 'Description not available.' !!}</p>
                </div>
            </div>

            <!-- Chat Section -->
            <div class="card mt-4 border shadow-sm" id="chatSection">
                <div class="card-body p-0 d-flex flex-column" style="height: 400px;">
                    <div id="chatHistory" class="chat-history-body flex-grow-1 p-3"
                        style="overflow-y: auto; padding-right: 10px;">
                        <ul class="list-unstyled chat-history"></ul>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="mt-4">
                <div class="form-floating form-floating-outline mb-4">
                    <textarea class="form-control h-px-100" name="chatInput" id="chatInput" rows="10" cols="80"></textarea>
                    <label for="chatInput">Comment</label>
                    <span class="text-danger" id="chatInput-error"></span>
                </div>
            </div>

            <!-- Form Buttons -->
            <div class="offcanvas-footer justify-content-md-end position-absolute bottom-0 end-0 w-100">
                <button class="btn rounded btn-secondary me-2" type="button" data-bs-dismiss="offcanvas">
                    <span class="tf-icons mdi mdi-cancel me-1"></span> Cancel
                </button>
                <button type="submit" class="btn rounded btn-primary waves-effect waves-light">
                    <span class="tf-icons mdi mdi-checkbox-marked-circle-outline">&nbsp;</span> Submit
                </button>
            </div>
        </form>
    </div>

    <div class="tab-pane fade" id="tabStatusLog" role="tabpanel" aria-labelledby="statuslog-tab">
        <div class="p-3">
            <div class="timeline">
                <div id="statusLog"></div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    if ($('#chatInput').length) {
        CKEDITOR.replace('chatInput', {
            filebrowserImageUploadUrl: '/api/v1/UploadFiles',
            filebrowserImageUploadMethod: 'form',
            filebrowserBrowseUrl: ''
        });
    }

    var taskId = $("#id").val();
    var selectedStatus = null;

    $(document).ready(function() {
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

                        $("#title").text(response.data.title);
                        $("#due_date").text(response.data.due_date);
                        $("#start_time").val(response.data.start_time);
                        $("#end_time").val(response.data.end_time);
                        $("#project_id").val(response.data.project);
                        $("#due_date").text(moment(response.data.due_date).format("DD/MM/YYYY"));

                        let createdBy = response.data.created_by || "Unknown";
                        let initials = createdBy
                            .split(" ")
                            .map((name) => name.charAt(0).toUpperCase())
                            .join("")
                            .substring(0, 2); // Get first two initials

                        $("#user_initials").text(initials);
                        $("#user_name").text(createdBy);
                        $("#created_at").text(moment(response.data.created_at).format("DD/MM/YYYY, HH:mm"));

                        let priorityText = "";
                        let priorityClass = "";
                        switch (response.data.priority) {
                            case 1:
                                priorityText = "High";
                                priorityClass = "bg-label-danger";
                                break;
                            case 2:
                                priorityText = "Medium";
                                priorityClass = "bg-label-info";
                                break;
                            case 3:
                                priorityText = "Low";
                                priorityClass = "bg-label-primary";
                                break;
                            default:
                                priorityText = "Unknown";
                                priorityClass = "bg-label-secondary";
                        }
                        $("#priority").text(priorityText)
                            .removeClass(
                                "bg-label-danger bg-label-warning bg-label-success bg-label-secondary")
                            .addClass(priorityClass);

                        let documentsContainer = $(
                            "#uploadedFile");
                        documentsContainer.find("a")
                            .remove();

                        if (response.data.documents.length > 0) {
                            response.data.documents.forEach(function(doc) {
                                let documentLink = `
            <div class="d-flex align-items-center justify-content-center">
                <a href="{{ Storage::url('${doc.relative_path}') }}" class="text-primary text-truncate" target="_blank" style="max-width: 100%; display: inline-block;" title="${doc.file_display_name}">
                    ${doc.file_display_name}
                </a>
                <a href="{{ Storage::url('${doc.relative_path}') }}" download class="ms-2 text-secondary">
                    <i class="mdi mdi-download"></i>
                </a>
            </div>`;
                                documentsContainer.append(documentLink);
                            });
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
                        console.log("Failed to retrieve data.");
                    }
                }
            );
            var Url =
                "{{ config('apiConstants.TASKS_URLS.TASKS_GET_COMMENT') }}";
            fnCallAjaxHttpGetEvent(
                Url, {
                    taskId: taskId
                },
                true,
                true,
                function(response) {
                    if (response.status === 200 && response.data) {

                        if (response.data.comments.length <= 0) {
                            $("#chatSection").hide();
                        }
                        var comments = response.data.comments;
                        var currentUserId = response.data.currentUserId;

                        var chatHistory = $("#chatHistory ul");
                        chatHistory.empty();

                        comments.forEach(function(comment) {
                            var nameParts = comment.commented_by_name.split(" ");
                            var initials = nameParts.length > 1 ?
                                nameParts[0][0].toUpperCase() + nameParts[1][0].toUpperCase() :
                                nameParts[0][0]
                                .toUpperCase();

                            var chatHtml = `
        <li class="chat-message mt-4">
            <!-- Name, Initials, and Date above the message box -->
            <div class="d-flex align-items-center mb-3">
                <div class="user-initials text-center me-3">${initials}</div>
                <div>
                    <span class="fw-bold">${comment.commented_by_name.toLowerCase()}</span><br>
                    <small>${formatDateTime(comment.created_at)}</small>
                </div>
            </div>
            <!-- Message Box -->
            <div class="chat-message-wrapper p-3">
                <p class="mb-0">${comment.comment_text}</p>
            </div>
        </li>
    `;
                            chatHistory.append(chatHtml);
                        });

                    } else {
                        console.log("Failed to retrieve data.");
                    }
                }
            );
        }
    });

    function formatDateTime(dateTime) {
        var date = new Date(dateTime);
        var day = date.getDate().toString().padStart(2, '0'); // Get day (01-31)
        var month = (date.getMonth() + 1).toString().padStart(2, '0'); // Get month (01-12)
        var year = date.getFullYear(); // Get year (YYYY)
        var hours = date.getHours().toString().padStart(2, '0'); // Get hours (00-23)
        var minutes = date.getMinutes().toString().padStart(2, '0'); // Get minutes (00-59)
        var seconds = date.getSeconds().toString().padStart(2, '0'); // Get seconds (00-59)

        return `${day}/${month}/${year}, ${hours}:${minutes}`; // Format as DD/MM/YYYY, HH:mm:ss
    }

    $("#commonForm").submit(function(event) {
        event.preventDefault();

        var chatInputData = CKEDITOR.instances['chatInput'].getData();

        var postData = {
            chatInput: chatInputData,
            status: $("#status").val(),
            taskId: $("#id").val(),
            start_time: $("#start_time").val(),
            end_time: $("#end_time").val()
        };

        var storeTasksCommentUrl = "{{ config('apiConstants.TASKS_URLS.TASKS_COMMENT') }}";

        fnCallAjaxHttpPostEvent(storeTasksCommentUrl, postData, true, true, function(response) {
            if (response.status === 200) {
                bootstrap.Offcanvas.getInstance(document.getElementById('commonOffcanvas')).hide();

                var projectId = $("#project_id").val();
                fetchKanbanData(projectId);

                ShowMsg("bg-success", response.message);

            } else {
                ShowMsg("bg-warning", "The record could not be processed.");
            }
        });
    });


    $("#statuslog-tab").on("click", function() {
        var taskId = $("#id").val();
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
                            '<span class="badge bg-warning text-dark">Manual</span>' :
                            '<span class="badge bg-success">Auto</span>';

                        const movedAt = new Date(log.moved_at).toLocaleString('en-IN', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        statusLogHtml += `
    <div class="timeline-item d-flex mb-4">
        <div class="flex-shrink-0">
            <div class="timeline-icon bg-${bgColor} text-white rounded-circle p-2">
                ${icon}
            </div>
        </div>
        <div class="flex-grow-1 ms-3">
            <h6 class="mb-1">Task moved from
                <span class="badge bg-secondary">${log.from_column_name ?? '-'}</span> to
                <span class="badge bg-info text-dark">${log.to_column_name ?? '-'}</span>
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
