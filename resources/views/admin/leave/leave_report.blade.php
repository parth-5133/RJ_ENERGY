@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y ">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>{{ $menuName }}</b></h5>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap px-5 py-4">
                <div class="col-md-6 col-12 d-flex align-items-center flex-wrap">
                    <div class="form-floating form-floating-outline col-md-6 col-12 me-2 mb-2 mb-xxl-0 mb-sm-0">
                        <select class="form-select" id="employeeId" aria-label="Default select example">
                        </select>
                        <label for="exampleFormControlSelect1">Employee Name</label>
                    </div>
                    <a href="javascript:void(0)"
                        class="btn btn-sm btn-primary waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0 rounded"
                        id="searchButton">
                        <i class="mdi mdi-magnify"></i>
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-sm btn-primary waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0 rounded" id="reset">
                        <i class="mdi mdi-replay me-1"></i> Reset
                    </a>
                </div>
                <div class="col-md-6 col-12 d-flex align-items-center flex-nowrap justify-content-center justify-content-sm-end">
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
                            @if ($permissions['canEdit'] || $permissions['canDelete'])
                                <th>Action</th>
                            @endif
                            <th>Employee Name</th>
                            <th>Remaining Leaves</th>
                            <th>Total Leaves</th>
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
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();

        $('#btnExcel').click(function() {
            $('#grid').DataTable().button('.buttons-excel').trigger();
        });
        $('#btnCsv').click(function() {
            $('#grid').DataTable().button('.buttons-csv').trigger();
        });
        $('#btnPdf').click(function() {
            $('#grid').DataTable().button('.buttons-pdf').trigger();
        });


        $(document).ready(function() {

            $('#reset').last().on('click', function() {
                $('#employeeId').val('0');
                loadEmployeeDropdown('employeeId', '{{ config('apiConstants.USER_API_URLS.USER') }}', true,
                    true);
                filterGrid();
            });

            loadEmployeeDropdown('employeeId', '{{ config('apiConstants.USER_API_URLS.USER') }}', true, true);

            initializeDataTable();

            $("#searchButton").click(function() {
                filterGrid();
            });
        });

        function initializeDataTable() {
            $("#grid").DataTable({
                buttons: [{
                        extend: 'excelHtml5',
                        title: 'Leave Report',
                        className: 'buttons-excel d-none',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Leave Report',
                        className: 'buttons-csv d-none',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Leave Report',
                        className: 'buttons-pdf d-none',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    }
                ],

                responsive: true,
                autoWidth: false,
                serverSide: false,
                processing: true,
                'language': {
                    "loadingRecords": "&nbsp;",
                    "processing": "<img src='{{ asset('assets/img/illustrations/loader.gif') }}' alt='loader' />"
                },
                order: [
                    [1, "asc"]
                ],
                ajax: {
                    url: "{{ config('apiConstants.ADMIN_LEAVE_URLS.ALL_LEAVE_DATA') }}",
                    type: "GET",
                    headers: {
                        Authorization: "Bearer " + getCookie("access_token"),
                    },
                },
                columns: [
                    @if ($permissions['canDelete'] || $permissions['canEdit'])
                        {
                            data: "employee_id",
                            orderable: false,
                            render: function(data, type, row) {
                                var html = "<ul class='list-inline m-0'>";

                                // Edit Button
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton(true,
                                        "{{ url('/leaves/report') }}", "Edit",
                                        data, "Edit Leaves Balance") +
                                    "</li>";

                                return html;
                            },
                        }, {
                            data: "employee_name",
                            render: function(data, type, row) {
                                if ({{ $permissions['canEdit'] }}) {
                                    return `<a href="javascript:void(0);"onclick="fnAddEdit(this,'{{ url('/leaves/report') }}',${row.employee_id}, 'Edit Leaves Balance')"
                    class="user-name" data-id="${row.employee_id}">
                    ${data}
                </a>`;
                                }
                                return data;
                            }
                        }, {
                            data: "remaining_leave_balance",
                        }, {
                            data: "total_days",
                        }, {
                            data: "updated_name",
                        }, {
                            data: "updated_at_formatted",
                        }
                    @else
                        {
                            data: "employee_name",
                            render: function(data, type, row) {
                                if ({{ $permissions['canEdit'] }}) {
                                    return `<a href="javascript:void(0);"onclick="fnAddEdit(this,'{{ url('/leaves/report') }}',${row.employee_id}, 'Edit Leaves Balance')"
                    class="user-name" data-id="${row.employee_id}">
                    ${data}
                </a>`;
                                }
                                return data;
                            }
                        }, {
                            data: "remaining_leave_balance",
                        }, {
                            data: "total_days",
                        }
                    @endif

                ]
            });
        }

        function filterGrid(leaveStatus = null) {
            const employeeId = $("#employeeId").val();
            $('#grid').DataTable().ajax.url(
                `{{ config('apiConstants.ADMIN_LEAVE_URLS.ALL_LEAVE_DATA') }}?employee_id=${employeeId}`
            ).load();
        }
    </script>
@endsection
