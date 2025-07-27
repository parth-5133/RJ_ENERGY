@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="app-kanban card overflow-hidden">
            <input type="hidden" id="current_UserId" name="current_UserId" value="{{ $userId ?? '' }}" />
            <!-- Add new board -->
            <div class="d-flex justify-content-between align-items-center p-4">
                <div class="col-12 flex-sm-row kanban-project gap-2">
                    <div class="col-xl-2 col-lg-3 col-sm-4 col-12 form-floating form-floating-outline">
                        <select class="form-select" id="projectId" aria-label="Default select example">
                            <!-- Populate options dynamically -->
                            @if (!empty($projectData))
                                @foreach ($projectData as $index => $project)
                                    <option value="{{ $project['id'] }}" {{ $index === 0 ? 'selected' : '' }}>
                                        {{ $project['project_name'] }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" selected>No projects available</option>
                            @endif
                        </select>
                        <label for="projectId">Project</label>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        @if (
                            $role_code == config('roles.SUPERADMIN') ||
                                $role_code == config('roles.ADMIN') ||
                                $role_code == config('roles.CLIENT'))
                            <button id="btnAdd" type="submit" class="btn btn-primary waves-effect waves-light"
                                onClick="fnAddEdit(this, '{{ url('/projects/create') }}', 0, 'Add New Project',true,1)">
                                <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Project
                            </button>
                        @endif
                        @if ($permissions['canAdd'])
                            <form class="kanban-add-new-board">
                                <label class="kanban-add-board-btn btn btn-primary" for="kanban-add-board-input">
                                    <i class="mdi mdi-plus"></i>
                                    <span class="align-middle">Add New</span>
                                </label>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <hr>
            <div class="kanban-wrapper"></div>
            <div class="offcanvas offcanvas-end kanban-update-item-sidebar">
            </div>
        </div>
    </div>

    <!-- Add New Board Modal -->
    <div class="modal fade" id="addBoardModal" tabindex="-1" aria-labelledby="addBoardModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBoardModalLabel">Add New Board</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addBoardForm">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="kanban-add-board-input"
                                placeholder="Add Board Title" required />
                            <label for="kanban-add-board-input">Board Title</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="addBoardSubmit">Add</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jkanban/jkanban.js') }}"></script>
    <script type="text/javascript">
        "use strict";

        function KanbanData(projectId) {
            fetchKanbanData(projectId);
        }

        async function fetchKanbanData(projectId) {
            const kanbanWrapper = document.querySelector(".kanban-wrapper");
            kanbanWrapper.innerHTML = '';

            const addBoardBtn = document.querySelector(".kanban-add-board-btn");
            addBoardBtn.removeEventListener("click", handleAddBoardClick);
            addBoardBtn.addEventListener("click", handleAddBoardClick);

            const i = document.querySelector(".kanban-update-item-sidebar"),
                e = document.querySelector(".kanban-wrapper"),
                t = document.querySelector(".comment-editor"),
                a = document.querySelector(".kanban-add-new-board"),
                n = [].slice.call(document.querySelectorAll(".kanban-add-board-input")),
                d = document.querySelector(".kanban-add-board-btn"),
                r = document.querySelector("#due-date"),
                o = $(".select2"),
                l = document.querySelector("html").getAttribute("data-assets-path"),
                s = new bootstrap.Offcanvas(i);

            const apiUrl = `{{ env('APP_URL') }}/api/v1/kanban/data/${projectId}/${
                document.querySelector("#current_UserId").value
            }`;
            let kanbanData = await fetch(apiUrl);
            kanbanData = await kanbanData.json();

            function u(e) {
                return e.id ? "<div class='badge " + $(e.element).data("color") + " rounded-pill'> " + e
                    .text +
                    "</div>" : e.text
            }

            function b() {
                let html = '';

                @if ($permissions['canDelete'] || $permissions['canEdit'])
                    html += "<div class='dropdown' style='position: absolute; top: 18px; right: 40px;'>" +
                        "<i class='dropdown-toggle mdi mdi-dots-vertical cursor-pointer' id='board-dropdown' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='line-height: 0 !important; vertical-align: baseline !important;'></i>" +
                        "<div class='dropdown-menu dropdown-menu-end' aria-labelledby='board-dropdown'>";

                    @if ($permissions['canEdit'])
                        html +=
                            "<a class='dropdown-item edit-board'><i class='mdi mdi-pencil-outline'></i> <span class='align-middle'>Edit</span></a>";
                    @endif

                    @if ($permissions['canDelete'])
                        html +=
                            "<a class='dropdown-item delete-board'> <i class='mdi mdi-trash-can-outline'></i> <span class='align-middle'>Delete</span></a>";
                    @endif

                    html += "</div></div>";
                @endif

                @if ($permissions['canAdd'])
                    html +=
                        "<i class='mdi mdi-plus add-task-icon cursor-pointer mdi-18px' style='position: absolute;top: 20px;right: 18px;background: white;border-radius: 6px;'></i>";
                @endif

                return html;
            }

            kanbanData.data.forEach(board => {
                board.title = "<span>" + board.title + "</span>" + b();

                // Add comment icon to each item in the board
                if (board.item && Array.isArray(board.item)) {
                    board.item = board.item.map(item => {
                        return {
                            ...item,
                            title: "<div class='kanban-item-header d-flex justify-content-between align-items-center'>" +
                                "<span class='kanban-text' style='cursor: pointer;'>" + item.title +
                                "</span>" +
                                "<i class='mdi mdi-comment-text-outline mdi-24px comment-icon' style='cursor: pointer;'></i>" +
                                "</div>"
                        };
                    });
                }
            });

            const v = new jKanban({
                element: ".kanban-wrapper",
                gutter: "12px",
                widthBoard: "250px",
                dragItems: !0,
                boards: kanbanData.data,
                dragBoards: !0,
                addItemButton: !0,
                buttonContent: "+ Add Item",
                buttonClick: function(e, a) {
                    const n = document.createElement("form");
                    n.setAttribute("class", "new-item-form");
                    n.innerHTML = v.addForm(a, n);
                    n.addEventListener("submit", function(e) {
                        e.preventDefault();
                        var t = [].slice.call(document.querySelectorAll(
                            ".kanban-board[data-id=" + a + "] .kanban-item"));
                        v.addElement(a, {
                            title: "<div class='kanban-item-header d-flex justify-content-between align-items-center'>" +
                                "<span class='kanban-text'>" + e.target[0].value +
                                "</span>" +
                                "<i class='mdi mdi-message-text comment-icon' style='cursor: pointer;'></i>" +
                                "</div>",
                            id: a + "-" + t.length + 1
                        });
                        e = [].slice.call(document.querySelectorAll(
                            ".kanban-item .kanban-tasks-item-dropdown"));
                        e && e.forEach(function(e) {
                            e.addEventListener("click", function(e) {
                                e.stopPropagation()
                            })
                        }), [].slice.call(document.querySelectorAll(
                            ".kanban-board[data-id=" +
                            a + "] .delete-task")).forEach(function(e) {
                            e.addEventListener("click", function() {
                                var e = this.closest(".kanban-item")
                                    .getAttribute(
                                        "data-eid");
                                v.removeElement(e)
                            })
                        }), n.remove()
                    });
                    n.querySelector(".cancel-add-item").addEventListener("click", function(e) {
                        n.remove()
                    })
                },
                dropEl: async function(el, target, source, sibling) {
                    try {
                        const taskId = el?.getAttribute("data-eid");
                        const targetBoardId = target?.parentElement?.getAttribute("data-id");
                        const sourceBoardId = source?.parentElement?.getAttribute("data-id");
                        const siblingTaskId = sibling ? sibling.getAttribute("data-eid") : 0;

                        // Check if any required data is missing
                        if (!taskId || !targetBoardId || !sourceBoardId) {
                            console.error("Missing required elements or attributes.", {
                                el,
                                target,
                                source,
                                sibling
                            });
                            toastr.error("Failed to update the task due to missing data.");
                            return;
                        }
                        const movedBy = getIdFromToken();

                        // Proceed with API call
                        const response = await fetch(
                            `{{ env('APP_URL') }}/api/v1/kanban/update-task`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]')
                                        ?.getAttribute("content"),
                                },
                                body: JSON.stringify({
                                    task_id: taskId,
                                    target_board_id: targetBoardId,
                                    source_board_id: sourceBoardId,
                                    sibling_task_id: siblingTaskId,
                                    moved_by: movedBy
                                }),
                            });

                        const result = await response.json();

                        if (response.ok) {
                            toastr.success(result.message || "Task updated successfully.");
                        } else {
                            toastr.error(result.message || "Failed to update the task.");
                        }
                    } catch (error) {
                        console.error("Error updating task:", error);
                        toastr.error("An error occurred while updating the task.");
                    }
                },
                dropBoard: async function(el, target, source, sibling) {
                    try {
                        // Get the source board ID from the dragged element
                        const sourceBoardId = el?.getAttribute("data-id");
                        const targetBoardId = target?.getAttribute("data-id");

                        console.log("Source Board ID:", sourceBoardId);
                        console.log("Target Board ID:", target);

                        if (!sourceBoardId || !targetBoardId) {
                            console.error("Missing required board IDs", {
                                sourceBoardId,
                                targetBoardId
                            });
                            toastr.error("Failed to update board position due to missing data.");
                            return;
                        }

                        // Proceed with API call
                        const response = await fetch(
                            `{{ env('APP_URL') }}/api/v1/kanban/update-board-position`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]')
                                        ?.getAttribute("content"),
                                },
                                body: JSON.stringify({
                                    source_board_id: sourceBoardId,
                                    target_board_id: targetBoardId
                                }),
                            });

                        const result = await response.json();

                        if (response.ok) {
                            toastr.success(result.message || "Board position updated successfully.");
                        } else {
                            toastr.error(result.message || "Failed to update board position.");
                        }
                    } catch (error) {
                        console.error("Error updating board position:", error);
                        toastr.error("An error occurred while updating board position.");
                    }
                }
            });

            [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function(e) {
                return new bootstrap.Tooltip(e)
            });
        }

        // Separate handler functions
        function handleAddBoardClick() {
            const modal = new bootstrap.Modal(document.getElementById('addBoardModal'));
            modal.show();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const projectIdSelect = document.getElementById("projectId");
            const addBoardModal = document.getElementById('addBoardModal');
            const addBoardForm = document.getElementById('addBoardForm');
            const addBoardSubmit = document.getElementById('addBoardSubmit');

            // Initial load
            fetchKanbanData(projectIdSelect.value);

            // Project change handler
            projectIdSelect.addEventListener("change", function() {
                const selectedProjectId = this.value;
                if (selectedProjectId) {
                    fetchKanbanData(selectedProjectId);
                } else {
                    console.error("No project selected.");
                }
            });

            // Add board submit handler
            addBoardSubmit.addEventListener("click", async function() {
                const boardTitle = document.getElementById("kanban-add-board-input").value;
                const projectId = document.getElementById("projectId").value;

                if (!boardTitle || !projectId) {
                    ShowMsg("bg-danger", 'Board title are required.');
                    return;
                }

                try {
                    const response = await fetch("{{ env('APP_URL') }}/api/v1/kanban/create-board", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                ?.getAttribute(
                                    "content"),
                        },
                        body: JSON.stringify({
                            project_id: projectId,
                            title: boardTitle,
                        }),
                    });

                    const result = await response.json();

                    if (response.status == 200) {
                        const modal = bootstrap.Modal.getInstance(addBoardModal);
                        modal.hide();
                        addBoardForm.reset();
                        fetchKanbanData(projectId);
                        ShowMsg("bg-success", 'Board created successfully.');
                    } else {
                        toastr.error(result.message || "Failed to create board.");
                    }
                } catch (error) {
                    console.error("Error creating board:", error);
                    toastr.error("An error occurred while creating the board.");
                }
            });
        });

        document.addEventListener("click", async function(event) {
            if (event.target.closest(".add-task-icons")) {
                const boardElement = event.target.closest(".kanban-board");
                const boardId = boardElement?.getAttribute("data-id");

                // Create and show the add item form
                const form = document.createElement("form");
                form.setAttribute("class", "new-item-form");
                form.innerHTML = v.addForm(boardId, form);

                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    var items = [].slice.call(document.querySelectorAll(
                        ".kanban-board[data-id=" + boardId + "] .kanban-item"));
                    v.addElement(boardId, {
                        title: "<div class='kanban-item-header d-flex justify-content-between align-items-center'>" +
                            "<span class='kanban-text'>" + e.target[0].value +
                            "</span>" +
                            "<i class='mdi mdi-message-text comment-icon' style='cursor: pointer;'></i>" +
                            "</div>",
                        id: boardId + "-" + items.length + 1
                    });
                    form.remove();
                });

                form.querySelector(".cancel-add-item").addEventListener("click", function() {
                    form.remove();
                });

                boardElement.appendChild(form);
            }
            if (event.target.closest(".delete-board")) {
                const boardElement = event.target.closest(".kanban-board");
                const boardId = boardElement?.getAttribute("data-id");

                var menuName = "Board";
                var f_boardId = boardElement?.getAttribute("data-id")?.replace("column-", "");

                fnShowConfirmDeleteDialog(menuName, fnDeleteRecord, f_boardId,
                    "{{ config('apiConstants.KANBAN_URLS.KANBAN_DELETE') }}");
            }
            if (event.target.closest(".edit-board")) {
                const boardElement = event.target.closest(".kanban-board");
                const boardId = boardElement?.getAttribute("data-id");

                var f_boardId = boardElement?.getAttribute("data-id")?.replace("column-", "");

                fnAddEdit(false, "{{ url('/kanban/Edit') }}", f_boardId, "Edit Title", false)

            }
            if (event.target.closest(".kanban-item") && !event.target.closest(".comment-icon")) {
                const taskElement = event.target.closest(".kanban-item");
                const taskId = taskElement?.getAttribute("data-eid");

                fnAddEditTask(false, "{{ url('/kanban/Task/comment') }}", taskId, "", true);
            }
            if (event.target.closest(".comment-icon")) {
                event.stopPropagation();
                const taskElement = event.target.closest(".kanban-item");
                const taskId = taskElement?.getAttribute("data-eid");

                fnAddEditTask(false, "{{ url('/kanban/Task/comment') }}", taskId, "", true);
            }
            if (event.target.closest(".add-task-icon")) {
                event.stopPropagation();

                const boardElement = event.target.closest(".kanban-board");
                const boardId = boardElement?.getAttribute("data-id")?.match(/\d+/)?.[0] || null;

                fnAddEdit(this, '{{ url('/tasks/create') }}', 0, 'Add New Task', true, boardId);
            }
        });

        // Call fetchKanbanData every 30 seconds using fetch API
        setInterval(async function() {
            const projectId = $('#projectId').val();
            if (projectId) {
                try {
                    await fetchKanbanData(projectId);
                } catch (error) {
                    console.error("Failed to refresh Kanban data:", error);
                }
            }
        }, 30000);
    </script>

@endsection
