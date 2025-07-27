@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>{{ $menuName }}</b></h5>
                </div>
                @if ($permissions['canAdd'])
                    <button id="btnAdd" type="submit" class="btn btn-primary waves-effect waves-light"
                        onClick="fnAddEdit(this, '{{ url('/tasks/create') }}', 0, 'Add New Task',true)">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Task
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            @if ($permissions['canEdit'] || $permissions['canDelete'])
                                <th>Action</th>
                            @endif
                            <th>Title</th>
                            <th>Due date</th>
                            <th>Project</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Total Time Spent</th>
                            @if ($permissions['canDelete'] || $permissions['canEdit'])
                                <th>Modified By</th>
                                <th>Modified Date</th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            initializeDataTable();
        });

        function initializeDataTable() {
            $("#grid").DataTable({
                responsive: true,
                autoWidth: false,
                serverSide: false,
                processing: true,
                'language': {
                    "loadingRecords": "&nbsp;",
                    "processing": "<img src='{{ asset('assets/img/illustrations/loader.gif') }}' alt='loader' />"
                },
                order: [
                    [1, "desc"]
                ],
                ajax: {
                    url: "{{ config('apiConstants.TASKS_URLS.TASKS') }}",
                    type: "GET",
                    headers: {
                        Authorization: "Bearer " + getCookie("access_token"),
                    },
                },
                columns: [
                    @if ($permissions['canDelete'] || $permissions['canEdit'])
                        {
                            data: "id",
                            orderable: false,
                            render: function(data, type, row) {
                                var html = "<ul class='list-inline m-0'>";

                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canEdit'] }},
                                        "{{ url('/tasks/create') }}", "Edit",
                                        data, "Edit Task", true) +
                                    "</li>";

                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canDelete'] }},
                                        "fnShowConfirmDeleteDialog('" + row.title + "',fnDeleteRecord," +
                                        data + ",'" +
                                        '{{ config('apiConstants.TASKS_URLS.TASKS_DELETE') }}' +
                                        "','#grid')", "Delete") +
                                    "</li>";

                                html += "</ul>";
                                return html;
                            },
                        }, {
                            data: "title",
                            render: function(data, type, row) {
                                if ({{ $permissions['canEdit'] }}) {
                                    return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('/tasks/create') }}',${row.id}, 'Edit Tasks',true)"
                            class="user-name" data-id="${row.id}">
                            ${data}
                        </a>`;
                                }
                                return data;
                            }
                        }, {
                            data: "due_date_formatted",
                        }, {
                            data: "project_name",
                        }, {
                            data: "status_name",
                        }, {
                            data: "priority",
                            render: function(data, type, row) {
                                switch (data) {
                                    case 1:
                                        return "<span>High</span>";
                                    case 2:
                                        return "<span>Medium</span>";
                                    case 3:
                                        return "<span>Low</span>";
                                }
                            }
                        }, {
                            data: "total_worked_time",
                        }, {
                            data: "updated_name",
                        }, {
                            data: "updated_at_formatted",
                        }
                    @else
                        {
                            data: "title",
                        }, {
                            data: "due_date_formatted",
                        }, {
                            data: "project_name",
                        }, {
                            data: "status",
                            render: function(data, type, row) {
                                switch (data) {
                                    case 1:
                                        return "<span>In Progress</span>";
                                    case 2:
                                        return "<span>Completed</span>";
                                    case 3:
                                        return "<span>Pending</span>";
                                    case 4:
                                        return "<span>On Hold</span>";
                                }
                            }
                        }, {
                            data: "priority",
                            render: function(data, type, row) {
                                switch (data) {
                                    case 1:
                                        return "<span>High</span>";
                                    case 2:
                                        return "<span>Medium</span>";
                                    case 3:
                                        return "<span>Low</span>";
                                }
                            }
                        }, {
                            data: "total_worked_time",
                        }
                    @endif
                ]
            });
        }
    </script>
@endsection
