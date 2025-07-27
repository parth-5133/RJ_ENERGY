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
                        onClick="fnAddEdit(this, '{{ url('/resignation/create') }}', 0, 'Add Resignation',true)">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Resignation
                    </button>
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
                            <th>Resignation Date</th>
                            <th>Resignation Letter</th>
                            <th>Last Working Date</th>
                            <th>Status</th>
                            <th>Modified By</th>
                            <th>Modified On</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            initializeDataTable();
        });

        function initializeDataTable() {
            $('#grid').DataTable({
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
                    url: "{{ config('apiConstants.EMPLOYEE_RESIGNATION_URLS.EMPLOYEE_RESIGNATION') }}",
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

                            // Edit Button (This is your existing edit button logic)
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canEdit'] }},
                                    "{{ url('resignation/create') }}", "Edit",
                                    data, "Edit Resignation", true) +
                                "</li>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.employee_name +
                                    "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.EMPLOYEE_RESIGNATION_URLS.EMPLOYEE_RESIGNATION_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";

                            html += "</ul>";
                            return html;
                        },
                    },
                    {
                        data: "employee_id"
                    },
                    {
                        data: "employee_name",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('resignation/create') }}',${row.id}, 'Edit Resignation',true)"
                            class="user-name" data-id="${row.id}">
                            ${data}
                        </a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: "resignation_date",
                    },
                    {
                        data: "document_name",
                        render: function(data, type, row) {
                            if (data) {
                                return `<a href="{{ url('/storage/') }}/${row.document}" target="_blank">${data}</a>`;
                            }
                            return '';
                        }
                    },
                    {
                        data: "last_working_date",
                    },
                    {
                        data: "status",
                        render: function(data, type, row) {
                            if (data === "Pending") {
                                return `<span class="badge rounded bg-label-warning">${data}</span>`;
                            }
                            if (data === "Rejected") {
                                return `<span class="badge rounded bg-label-danger">Rejected</span>`;
                            }
                            if (data === "Approved") {
                                return `<span class="badge rounded bg-label-success">Approved</span>`;
                            }
                        }
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
