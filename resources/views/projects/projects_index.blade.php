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
                        onClick="fnAddEdit(this, '{{ url('/projects/create') }}', 0, 'Add New Project',true)">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Project
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
                            <th>Project ID</th>
                            <th>Project Name</th>
                            <th>Deadline</th>
                            <th>Priority</th>
                            @if ($permissions['canDelete'] || $permissions['canEdit'])
                                <th>Modified By</th>
                                <th>Modified Date</th>
                            @endif
                            <th>Status</th>
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
                    url: "{{ config('apiConstants.PROJECTS_URLS.PROJECTS') }}",
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

                                // Edit Button (This is your existing edit button logic)
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canEdit'] }},
                                        "{{ url('/projects/create') }}", "Edit",
                                        data, "Edit Project", true) +
                                    "</li>";

                                // Delete Button
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canDelete'] }},
                                        "fnShowConfirmDeleteDialog('" + row.project_name +
                                        "',fnDeleteRecord," +
                                        data + ",'" +
                                        '{{ config('apiConstants.PROJECTS_URLS.PROJECTS_DELETE') }}' +
                                        "','#grid')", "Delete") +
                                    "</li>";
                                html += "</ul>";
                                return html;
                            },
                        }, {
                            data: "project_id",
                            render: function(data, type, row) {
                                if ({{ $permissions['canEdit'] }}) {
                                    return `<a href="{{ url('/projects/details') }}/${row.id}"
                           class="text-primary">${data}</a>`;
                                }
                                return data;
                            }
                        }, {
                            data: "project_name",
                            render: function(data, type, row) {
                                if ({{ $permissions['canEdit'] }}) {
                                    return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('/projects/create') }}',${row.id}, 'Edit Project',true)"
                            class="user-name" data-id="${row.id}">
                            ${data}
                        </a>`;
                                }
                                return data;
                            }
                        }, {
                            data: "end_date_formatted",
                        }, {
                            data: "priority",
                            render: function(data, type, row) {
                                switch (data) {
                                    case 1:
                                        return "High";
                                    case 2:
                                        return "Medium";
                                    case 3:
                                        return "Low";
                                    default:
                                        return "Select";
                                }
                            }
                        }, {
                            data: "updated_name",
                        }, {
                            data: "updated_at_formatted",
                        }, {
                            data: "is_active",
                            render: function(data) {
                                return data === 1 ?
                                    `<span class="badge rounded bg-label-success">Active</span>` :
                                    `<span class="badge rounded bg-label-danger">Inactive</span>`;
                            }
                        }
                    @else
                        {
                            data: "project_id",
                        }, {
                            data: "project_name",
                        }, {
                            data: "end_date_formatted",
                        }, {
                            data: "priority",
                            render: function(data, type, row) {
                                switch (data) {
                                    case 1:
                                        return "High";
                                    case 2:
                                        return "Medium";
                                    case 3:
                                        return "Low";
                                    default:
                                        return "Select";
                                }
                            }
                        }, {
                            data: "is_active",
                            render: function(data) {
                                return data === 1 ?
                                    `<span class="badge rounded bg-label-success">Active</span>` :
                                    `<span class="badge rounded bg-label-danger">Inactive</span>`;
                            }
                        }
                    @endif
                ]
            });
        }
    </script>
@endsection
