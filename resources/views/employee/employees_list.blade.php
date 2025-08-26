@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-nowrap gap-1">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>{{ $menuName }}</b></h5>
                </div>
                @if ($permissions['canAdd'])
                    <button id="btnAdd" type="submit" class="btn btn-primary waves-effect waves-light"
                        onClick="fnAddEdit(this,'{{ url('user/create') }}', 0, 'Add Employee')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Employee
                    </button>
                @endif
            </div>
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            @if ($permissions['canDelete'] || $permissions['canEdit'])
                                <th>Action</th>
                            @endif
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Email</th>
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
                order: [
                    [1, "asc"]
                ],
                ajax: {
                    url: "{{ config('apiConstants.EMPLOYEE_URLS.EMPLOYEE') }}",
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
                                var html =
                                    "<ul class='list-inline m-0'><li class='list-inline-item'>";
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canEdit'] }},
                                        "{{ url('user/create') }}",
                                        "Edit",
                                        data, "Edit Employee") +
                                    "</li>";
                                html += "<li class='list-inline-item'>" + GetEditDeleteButton(
                                    {{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.name +
                                    "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.USER_API_URLS.USER_DELETE') }}' +
                                    "','#grid')",
                                    "Delete") + "</li></ul>";
                                return html;
                            },
                        },
                    @endif {
                        data: "employee_id",
                    }, {
                        data: "name",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="{{ url('/profile') }}?id=${row.uuid}"class="user-name" data-id="${row.uuid}">
                    ${data}
                </a>`;
                            }
                            return data;
                        }
                    }, {
                        data: "email",
                    }, {
                        data: "is_active",
                        render: function(data) {
                            if (data === 1) {
                                return `<span class="badge rounded bg-label-success">Active</span>`;
                            } else {
                                return `<span class="badge rounded bg-label-danger">Inactive</span>`;
                            }
                        }
                    }
                ]
            });
        }
    </script>
@endsection
