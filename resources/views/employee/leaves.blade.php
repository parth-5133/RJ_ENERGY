@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>{{$menuName}}</b></h5>
                </div>
                @if ($permissions['canAdd'])
                    <button id="btnAdd" type="submit" class="btn btn-primary waves-effect waves-light"
                        onClick="fnAddEdit(this,'{{ url('/employee/leaves/request') }}', 0, 'Leave Request')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Leave
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Leave Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Number of days </th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Approved By</th>
                            <th>Comments</th>
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
                    url: "{{ config('apiConstants.EMPLOYEE_LEAVE_URLS.EMPLOYEE_LEAVE') }}",
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
                                GetEditDeleteButton({{ $permissions['canEdit'] }},
                                    "{{ url('/employee/leaves/request') }}", "Edit",
                                    data, "Edit Leaves") +
                                "</li>";
                            html += "<li class='list-inline-item'>" + GetEditDeleteButton(
                                {{ $permissions['canDelete'] }},
                                "fnShowConfirmDeleteDialog('" + row.leave_type + "',fnDeleteRecord," +
                                data +
                                ",'" +
                                '{{ config('apiConstants.EMPLOYEE_LEAVE_URLS.EMPLOYEE_LEAVE_DELETE') }}' +
                                "','#grid')",
                                "Delete") + "</li></ul>";
                            return html;
                        },
                    },
                    {
                        data: "leave_type",

                        render: function(data, type, row) {
                            if (row.status === "Approved") {
                                return data;
                            }
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="javascript:void(0);"onclick="fnAddEdit(this,'{{ url('/employee/leaves/request') }}',${row.id}, 'Edit Leaves')"
                    class="user-name" data-id="${row.id}">
                    ${data}
                </a>`;
                            }
                        }
                    },
                    {
                        data: "start_date",
                    },
                    {
                        data: "end_date",
                    },
                    {
                        data: "total_days",
                    },
                    {
                        data: "reason",
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
                        data: "approved_by",
                    },
                    {
                        data: "comments",
                    },

                ]
            });
        }
    </script>
@endsection
