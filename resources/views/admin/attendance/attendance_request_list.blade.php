@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>Attendance Request</b></h5>
                </div>
            </div>
            <div class="col-12 d-flex align-items-center flex-wrap p-4">
                <div class="form-floating form-floating-outline col-lg-3 col-md-5 col-sm-6 col-12 me-2 mb-3">
                    <select class="form-select" id="employeeId" aria-label="Default select example">
                    </select>
                    <label for="exampleFormControlSelect1">Employee</label>
                </div>

                <div class="form-floating form-floating-outline col-lg-3 col-md-5 col-sm-6 col-12 me-2 mb-3">
                    <select class="form-select" id="leaveStatusSelect" aria-label="Default select example">
                        <option value="0" selected>All</option>
                        <option value="1">Approved</option>
                        <option value="2">Rejected</option>
                        <option value="3">Pending</option>
                    </select>
                    <label for="exampleFormControlSelect1">Status</label>
                </div>

                <a href="javascript:void(0)" class="btn btn-sm btn-primary waves-effect waves-light mb-3 me-2"
                    id="searchButton">
                    <i class="mdi mdi-magnify"></i>
                </a>

                <a href="javascript:void(0)" class="btn btn-sm btn-primary waves-effect waves-light mb-3" id="reset">
                    <i class="mdi mdi-replay me-1"></i> Reset
                </a>
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Employee ID</th>
                            <th>Employee Name</th>
                            <th>Attendance Status</th>
                            <th>Attendance Date</th>
                            <th>Attendance Time</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Modified By</th>
                            <th>Modified Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script script type="text/javascript">
        $(document).ready(function() {

            $('#reset').last().on('click', function() {
                $('#employeeId').val('');
                $('#leaveStatusSelect').val('0');
                filterGrid(leaveStatus = null);
                loadEmployeeDropdown('employeeId', '{{ config('apiConstants.USER_API_URLS.USER') }}', false,
                    true);
            });

            initializeDataTable();
            loadEmployeeDropdown('employeeId', '{{ config('apiConstants.USER_API_URLS.USER') }}', false, true);

            $("#searchButton").click(function() {
                filterGrid();
            });
        });

        function filterGrid(leaveStatus = null) {
            const employeeId = $("#employeeId").val();

            if (leaveStatus !== null) {
                $("#leaveStatusSelect").val(leaveStatus);
            }

            const Status = leaveStatus !== null ? leaveStatus : $("#leaveStatusSelect").val();

            if (!employeeId && !Status) {
                return;
            }

            $('#grid').DataTable().ajax.url(
                `{{ config('apiConstants.ADMIN_ATTENDANCE_URLS.ADMIN_ATTENDANCE_REQUEST') }}?employee_id=${employeeId}&leave_status=${Status}`
            ).load();
        }

        function initializeDataTable() {
            return $("#grid").DataTable({
                responsive: true,
                autoWidth: false,
                serverSide: false,
                processing: true,
                order: [
                    [1, "asc"]
                ],
                ajax: {
                    url: `{{ config('apiConstants.ADMIN_ATTENDANCE_URLS.ADMIN_ATTENDANCE_REQUEST') }}`,
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

                            // Edit Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton(true,
                                    "{{ url('admin/attendance/request/edit') }}", "Edit",
                                    data, "Edit Attendance") +
                                "</li>";

                            // Delete Button
                            if (row.status !== "Approved") {
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton(true,
                                        "fnShowConfirmDeleteDialog('" + row.full_name +
                                        "',fnDeleteRecord," +
                                        data + ",'" +
                                        '{{ config('apiConstants.ADMIN_ATTENDANCE_URLS.ADMIN_ATTENDANCE_DELETE') }}' +
                                        "','#grid')", "Delete") +
                                    "</li>";
                            }

                            html += "</ul>";
                            return html;
                        },
                    },
                    {
                        data: "employeeId",
                    },
                    {
                        data: "full_name",
                        render: function(data, type, row) {
                            return `<a href="javascript:void(0);"onclick="fnAddEdit(this,'{{ url('admin/attendance/request/edit') }}',${row.id}, 'Edit Attendance')"
                    class="user-name" data-id="${row.id}">
                    ${data}
                </a>`;
                        }
                    },
                    {
                        data: "attendance_status",
                    },
                    {
                        data: "formatted_attendance_date",
                    },
                    {
                        data: "formatted_attendance_time",
                    },
                    {
                        data: "status",
                        render: function(data) {
                            if (data === "Pending") {
                                return `<span class="badge rounded bg-label-danger">Pending</span>`;
                            }
                            if (data === "Rejected") {
                                return `<span class="badge rounded bg-label-success">Rejected</span>`;
                            }
                            if (data === "Approved") {
                                return `<span class="badge rounded bg-label-success">Approved</span>`;
                            }
                        }
                    },
                    {
                        data: "comment",
                    },
                    {
                        data: "updated_name",
                    },
                    {
                        data: "updated_at_formatted",
                    }
                ]
            });
        }
    </script>
@endsection
