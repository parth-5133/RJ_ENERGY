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
                        onClick="fnAddEdit(this,'{{ url('user/create') }}', 0, 'Add User')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add User
                    </button>
                @endif
            </div>
            <div class="col-12 d-flex align-items-center flex-wrap p-4">
                <div class="form-floating form-floating-outline col-md-3 col-12 me-4 mb-3 mb-xxl-0 mb-sm-0">
                    <select class="form-select" id="roleId" aria-label="Default select example">
                        <option value="0">Select Role</option>
                    </select>
                    <label for="exampleFormControlSelect1">Role</label>
                </div>
                <a href="javascript:void(0)"
                    class="btn btn-sm btn-primary waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0" id="searchButton">
                    <i class="mdi mdi-magnify"></i>
                </a>
                <a href="javascript:void(0)"
                    class="btn btn-sm btn-primary waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0" id="reset">
                    <i class="mdi mdi-replay me-1"></i> Reset
                </a>
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Modified By</th>
                            <th>Modified Date</th>
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
            lodeRoleData();
            $("#searchButton").click(function() {
                filterGrid();
            });
            $("#reset").click(function() {
                lodeRoleData()
                const table = $('#grid').DataTable();
                table.ajax.url(
                        "{{ config('apiConstants.USER_API_URLS.USER') }}")
                    .load();
            });
        });

        function lodeRoleData() {
            fnCallAjaxHttpGetEvent("{{ config('apiConstants.API_URLS.ROLES') }}", null, true, true, function(
                response) {
                if (response.status === 200 && response.data) {
                    var $roleDropdown = $("#roleId");
                    $roleDropdown.empty();
                    $roleDropdown.append(new Option('Select Role', ''));

                    response.data.forEach(function(data) {
                        $roleDropdown.append(new Option(data.name, data.id));
                    });
                } else {
                    console.error('Failed to retrieve role list.');
                }
            });
        }

        function filterGrid() {
            const role_id = $("#roleId").val();

            if (!role_id) {
                return;
            }

            $('#grid').DataTable().ajax.url(
                `{{ config('apiConstants.USER_API_URLS.USER') }}?role_id=${role_id}`
            ).load();
        }

        function initializeDataTable() {
            $("#grid").DataTable({
                responsive: true,
                autoWidth: false,
                serverSide: false,
                processing: true,
                order: [
                    [1, "desc"]
                ],
                ajax: {
                    url: "{{ config('apiConstants.USER_API_URLS.USER') }}",
                    type: "GET",
                    headers: {
                        Authorization: "Bearer " + getCookie("access_token"),
                    },
                },
                columns: [{
                        data: "id",
                        orderable: false,
                        render: function(data, type, row) {
                            var html =
                                "<ul class='list-inline m-0'><li class='list-inline-item'>";
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canEdit'] }},
                                    "{{ url('user/create') }}",
                                    "Edit",
                                    data, "Edit User") +
                                "</li>";
                            if ({{ $permissions['canEdit'] }}) {
                                html += "<li class='list-inline-item'>" +
                                    "<button class='btn btn-sm btn-text-secondary rounded btn-icon item-edit' " +
                                    "style='background-color: #cfffd4; color:#00890e;' title='Reset Password' " +
                                    "onClick=\"fnAddEdit(this, '{{ url('change/password') }}', " + data +
                                    ", 'Reset Password')\">" +
                                    "<i class='mdi mdi-lock-reset'></i></button>" +
                                    "</li>";
                            }

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
                    {
                        data: "name",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="javascript:void(0);"onclick="fnAddEdit(this,'{{ url('user/create') }}',${row.id}, 'Edit User')"
                    class="user-name" data-id="${row.id}">
                    ${data}
                </a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: "email",
                    },
                    {
                        data: "role_name",
                    },
                    {
                        data: "updated_name",
                    },
                    {
                        data: "updated_at_formatted",
                    },
                    {
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
