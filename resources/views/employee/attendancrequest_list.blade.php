@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>Attendance Request</b></h5>
                </div>
                <button id="btnAdd" type="submit" class="btn btn-primary waves-effect waves-light"
                    onClick="fnAddEdit(this,'{{ url('/employee/attendance/request') }}', 0, 'Attendance Request')">
                    <span class="tf-icons mdi mdi-plus">&nbsp;</span>Attendance Request
                </button>
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Attendance Status</th>
                            <th>Attendance Date</th>
                            <th>Attendance Time</th>
                            <th>Note</th>
                            <th>status</th>
                            <th>Comment</th>
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
                    [0, "desc"]
                ],
                ajax: {
                    url: "{{ url('/api/V1/employee/Attendance/request') }}",
                    type: "GET",
                    headers: {
                        Authorization: "Bearer " + getCookie("access_token"),
                    },
                },
                columns: [{
                        data: "id",
                        orderable: false,
                        orderable: false,
                        render: function(data, type, row) {
                            if (row.status === "Approved") {
                                return "";
                            }
                            var html = "<ul class='list-inline m-0'><li class='list-inline-item'>";
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton(true, "{{ url('/employee/attendance/request') }}",
                                    "Edit",
                                    data, "Edit Attendance Request ") +
                                "</li>";
                            // Delete Button
                            if (row.status !== "Approved") {
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton(true,
                                        "fnShowConfirmDeleteDialog('" + row.attendance_status +
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
                        data: "attendance_status",

                        render: function(data, type, row) {
                            if (row.status === "Approved") {
                                return data;
                            }
                            return `<a href="javascript:void(0);"onclick="fnAddEdit('{{ url('/employee/leaves/request') }}',${row.id}, 'Edit Leaves')"
                    class="user-name" data-id="${row.id}">
                    ${data}
                </a>`;
                        }
                    },
                    {
                        data: "attendance_date",
                    },
                    {
                        data: "attendance_time",
                    },
                    {
                        data: "note",
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
                    },
                    {
                        data: "comment",
                    },

                ]
            });
        }
    </script>
@endsection
