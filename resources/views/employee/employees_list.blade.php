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

            <div class="col-12 d-flex justify-content-between align-items-center">
                <div class="col-12 d-flex align-items-center flex-nowrap px-5 py-4 justify-content-center justify-content-sm-end">
                    <a href="javascript:void(0)"
                        class="btn btn-sm btn-danger waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0 rounded d-flex flex-wrap" id="btnPdf">
                        <i class="mdi mdi-file-pdf-box me-1"></i> Export PDF
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-sm btn-info waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0 rounded d-flex flex-wrap" id="btnCsv">
                        <i class="mdi mdi-file-delimited-outline me-1"></i> Export CSV
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-sm btn-success waves-effect waves-light mb-3 mb-xxl-0 mb-sm-0 rounded d-flex flex-wrap" id="btnExcel">
                        <i class="mdi mdi-file-excel-box me-1"></i> Export Excel
                    </a>
                </div>
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
                            <th>Department</th>
                            <th>Employee Type</th>
                            <th>Joining Date</th>
                            <th>Status</th>
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

        $('#btnExcel').click(function() {
            $('#grid').DataTable().button('.buttons-excel').trigger();
        });
        $('#btnCsv').click(function() {
            $('#grid').DataTable().button('.buttons-csv').trigger();
        });
        $('#btnPdf').click(function() {
            $('#grid').DataTable().button('.buttons-pdf').trigger();
        });

        function initializeDataTable() {
            $("#grid").DataTable({
                buttons: [{
                        extend: 'excelHtml5',
                        title: 'Employee Report',
                        className: 'buttons-excel d-none',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Employee Report',
                        className: 'buttons-csv d-none',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Employee Report',
                        className: 'buttons-pdf d-none',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7]
                        }
                    }
                ],
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
                        data: "department_name",
                    }, {
                        data: "employee_type_name",
                    }, {
                        data: "date_of_joining",
                    }, {
                        data: "employee_status_name",
                        render: function(data, type, row) {
                            if (data == "Active") {
                                return `<span class="badge bg-label-success me-1">${data}</span>`;
                            } else if (data == "Retired") {
                                return `<span class="badge bg-label-danger me-1">${data}</span>`;
                            } else {
                                return `<span class="badge bg-label-warning me-1">${data ? data : ''}</span>`;
                            }
                        }
                    },
                    @if ($permissions['canDelete'] || $permissions['canEdit'])
                        {
                            data: "updated_name",
                        }, {
                            data: "updated_at_formatted",
                        }
                    @endif
                ]
            });
        }
    </script>
@endsection
