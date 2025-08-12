@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>{{ $menuName }}</b></h5>
                </div>
                @if ($role_code == config('roles.ADMIN') || $role_code == config('roles.SUPERADMIN') || $role_code == config('roles.CLIENT'))
                    @if ($permissions['canAdd'])
                        <button id="btnAdd" type="submit" class="btn btn-primary waves-effect waves-light"
                            onClick="fnAddEdit(this, '{{ url('employee-salary/create') }}', 0, 'Add Employee Salary',true)">
                            <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Employee Salary
                        </button>
                    @endif
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Employee ID</th>
                            <th>Employee</th>
                            <th>Basic Salary</th>
                            <th>Total Allowances</th>
                            <th>Total Deductions</th>
                            <th>Total Salary</th>
                            <th>Salary Month</th>
                            <th>Salary Year</th>
                            <th>Modified By</th>
                            <th>Modified Date</th>
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
                    url: "{{ config('apiConstants.EMPLOYEE_SALARY_URLS.EMPLOYEE_SALARY') }}",
                    type: "GET",
                    headers: {
                        Authorization: "Bearer " + getCookie("access_token"),
                    },
                },
                columns: [{
                        data: "id",
                        orderable: false,
                        render: function(data, type, row) {
                            var html = "<ul class='list-inline m-0'>";
                            @if ($role_code == config('roles.ADMIN') || $role_code == config('roles.SUPERADMIN'))
                                // Copy Button
                                html += "<li class='list-inline-item'>" +
                                    "<button class='btn btn-sm btn-text-success rounded btn-icon item-edit' " +
                                    "style='background-color: #cfffd4 !important; color:#00890e !important;' title='Copy Salary' " +
                                    "onClick=\"fnAddEdit(this, '{{ url('employee-salary/create') }}', " +
                                    data +
                                    ", 'Add Employee Salary',true, 1)\">" +
                                    "<i class='mdi mdi-content-copy'></i></button>" +
                                    "</li>";
                            @endif
                            @if (
                                $role_code == config('roles.ADMIN') ||
                                    $role_code == config('roles.SUPERADMIN') ||
                                    $role_code == config('roles.EMPLOYEE'))
                                // Download Button
                                html += "<li class='list-inline-item'>" +
                                    "<button class='btn btn-sm btn-text-info rounded btn-icon item-edit' " +
                                    "style='background-color: #c7e9ff !important; color:#009dff !important;' title='Download Salary Slip' " +
                                    "onClick=\"downloadSalarySlip(" + data + ")\">" +
                                    "<i class='mdi mdi-file-download-outline'></i></button>" +
                                    "</li>";
                            @endif
                            // Edit Button
                            @if ($role_code == config('roles.ADMIN') || $role_code == config('roles.SUPERADMIN'))
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canEdit'] }},
                                        "{{ url('employee-salary/create') }}", "Edit",
                                        data, "Edit Employee Salary", true) +
                                    "</li>";
                            @endif
                            // Delete Button
                            @if ($role_code == config('roles.ADMIN') || $role_code == config('roles.SUPERADMIN'))
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canDelete'] }},
                                        "fnShowConfirmDeleteDialog('" + row.employee_name +
                                        "',fnDeleteRecord," +
                                        data + ",'" +
                                        '{{ config('apiConstants.EMPLOYEE_SALARY_URLS.EMPLOYEE_SALARY_DELETE') }}' +
                                        "','#grid')", "Delete") +
                                    "</li>";
                            @endif
                            html += "</ul>";
                            return html;
                        },
                    },
                    {
                        data: "employee_id"
                    },
                    {
                        data: "employee_name",
                        @if ($role_code == config('roles.ADMIN') || $role_code == config('roles.SUPERADMIN'))
                            render: function(data, type, row) {
                                if ({{ $permissions['canEdit'] }}) {
                                    return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('employee-salary/create') }}',${row.id}, 'Edit Employee Salary',true)"
                    class="user-name" data-id="${row.id}">${data}</a>`;
                                }
                                return data;
                            }
                        @else
                            render: function(data, type, row) {
                                return data;
                            }
                        @endif
                    },
                    {
                        data: "basic_salary"
                    },
                    {
                        data: "total_allowances"
                    },
                    {
                        data: "total_deductions"
                    },
                    {
                        data: "total_salary"
                    },
                    {
                        data: "month_name"
                    },
                    {
                        data: "salary_year"
                    },
                    {
                        data: "updated_name"
                    },
                    {
                        data: "updated_at_formatted"
                    }
                ]
            });
        }

        function downloadSalarySlip(id) {
            let url = `{{ config('apiConstants.EMPLOYEE_SALARY_URLS.EMPLOYEE_SALARY_DOWNLOAD') }}`;

            fnCallAjaxHttpGetEvent(url, {
                id: id
            }, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    window.open(response.data, '_blank');
                }
            });
        }
    </script>
@endsection
