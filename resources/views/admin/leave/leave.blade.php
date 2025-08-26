@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>{{ $menuName }}</b></h5>
                </div>
            </div>
            <div class="col-12 d-flex align-items-center flex-wrap p-4">
                <div class="form-floating form-floating-outline col-lg-3 col-md-5 col-sm-6 col-12 me-2 mb-3">
                    <select class="form-select" id="employeeId" aria-label="Default select example">
                        <option value="">Select Employee Name</option>
                    </select>
                    <label for="exampleFormControlSelect1">Employee Name</label>
                </div>

                <div class="form-floating form-floating-outline col-lg-3 col-md-5 col-sm-6 col-12 me-2 mb-3">
                    <select class="form-select" id="leaveStatusSelect" aria-label="Default select example">
                        <option value="0" selected>All</option>
                        <option value="1">Approved</option>
                        <option value="2">Rejected</option>
                        <option value="3">Cancelled</option>
                        <option value="4">Pending</option>
                    </select>
                    <label for="exampleFormControlSelect1">Status</label>
                </div>
                <div class="form-floating form-floating-outline col-lg-2 col-md-5 col-sm-6 col-12 me-2 mb-3">
                    <input class="form-control" type="date" id="fromDateInput" pattern="\d{4}-\d{2}-\d{2}"
                        title="Enter a date in the format YYYY-MM-DD">
                    <label for="fromDateInput">From Date</label>
                </div>

                <div class="form-floating form-floating-outline col-lg-2 col-md-5 col-sm-6 col-12 me-2 mb-3">
                    <input class="form-control" type="date" id="toDateInput" pattern="\d{4}-\d{2}-\d{2}"
                        title="Enter a date in the format YYYY-MM-DD">
                    <label for="toDateInput">To Date</label>
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
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Leave Session</th>
                            <th>Number of days</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {

            $('#reset').last().on('click', function() {
                $('#employeeId').val('');
                $('#leaveStatusSelect').val('0');
                $('#fromDateInput').val('');
                $('#toDateInput').val('');
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
            const fromDate = $("#fromDateInput").val();
            const toDate = $("#toDateInput").val();

            if (leaveStatus !== null) {
                $("#leaveStatusSelect").val(leaveStatus);
            }

            const Status = leaveStatus !== null ? leaveStatus : $("#leaveStatusSelect").val();

            if (!employeeId && !fromDate && !toDate && !Status) {
                return;
            }

            $('#grid').DataTable().ajax.url(
                `{{ config('apiConstants.ADMIN_LEAVE_URLS.ADMIN_LEAVE') }}?employee_id=${employeeId}&leave_status=${Status}&from_date=${fromDate}&to_date=${toDate}`
            ).load();
        }

        function initializeDataTable() {
            return $("#grid").DataTable({
                responsive: true,
                autoWidth: false,
                serverSide: false,
                processing: true,
                order: [
                    [1, "desc"]
                ],
                ajax: {
                    url: `{{ config('apiConstants.ADMIN_LEAVE_URLS.ADMIN_LEAVE') }}`,
                    type: "GET",
                    headers: {
                        Authorization: "Bearer " + getCookie("access_token"),
                    },
                },
                columns: [{
                        data: "id",
                        orderable: false,
                        render: function(data, type, row) {
                            var html = "<ul class='list-inline m-0'><li class='list-inline-item'>";
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton(true, "{{ url('/admin/leaves/request') }}", "Edit",
                                    data, "Edit Leaves") +
                                "</li>";

                            // Delete Button
                            if (row.status !== "Approved") {
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton(true,
                                        "fnShowConfirmDeleteDialog('" + row.employee_name +
                                        "',fnDeleteRecord," +
                                        data + ",'" +
                                        '{{ config('apiConstants.ADMIN_LEAVE_URLS.ADMIN_LEAVE_DELETE') }}' +
                                        "','#grid')", "Delete") +
                                    "</li>";
                            }
                            html += "</ul>";
                            return html;
                        },
                    },
                    {
                        data: "employee_name",
                        render: function(data, type, row) {
                            return `<a href="javascript:void(0);"onclick="fnAddEdit(this,'{{ url('/admin/leaves/request') }}',${row.id}, 'Edit Leaves')"
                    class="user-name" data-id="${row.id}">
                    ${data}
                </a>`;
                        }
                    },
                    {
                        data: "leave_type_name",
                    },
                    {
                        data: "start_date",
                    },
                    {
                        data: "end_date",
                    },
                    {
                        data: "leave_session_id",
                        render: function(data, type, row) {
                            // Map the leave_session_id to corresponding text
                            if (data === 1) {
                                return "Full Day";
                            } else if (data === 2) {
                                return "First Half";
                            } else if (data === 3) {
                                return "Second Half";
                            } else {
                                return "Unknown Session"; // Fallback in case of unexpected values
                            }
                        }
                    },
                    {
                        data: "total_days",
                    },
                    {
                        data: "reason",
                        className: "text-wrap-custom",
                        render: function(data, type, row) {
                            return `<div class="text-wrap-custom">${data}</div>`;
                        }
                    },
                    {
                        data: "status",
                        render: function(data, type, row) {
                            const dangerStatuses = ["Pending", "Rejected", "Cancelled"];
                            if (dangerStatuses.includes(data)) {
                                return `<span class="badge rounded bg-label-danger">${data}</span>`;
                            } else {
                                return `<span class="badge rounded bg-label-success">${data}</span>`;
                            }
                        }
                    }
                ]
            });
        }
    </script>
@endsection
