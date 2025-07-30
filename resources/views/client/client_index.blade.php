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
                        onClick="fnAddEdit(this, '{{ url('user/create') }}', 0, 'Add New Customer',true)">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Adds New Customer
                    </button>
                @endif
            </div>
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div
                    class="col-12 d-flex align-items-center flex-nowrap px-5 py-4 justify-content-center justify-content-sm-end">
                    <a href="javascript:void(0)"
                        class="btn btn-sm btn-danger waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0 rounded d-flex flex-wrap"
                        id="btnPdf">
                        <i class="mdi mdi-file-pdf-box me-1"></i> Export PDF
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-sm btn-info waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0 rounded d-flex flex-wrap"
                        id="btnCsv">
                        <i class="mdi mdi-file-delimited-outline me-1"></i> Export CSV
                    </a>

                    <a href="javascript:void(0)"
                        class="btn btn-sm btn-success waves-effect waves-light mb-3 mb-xxl-0 mb-sm-0 rounded d-flex flex-wrap"
                        id="btnExcel">
                        <i class="mdi mdi-file-excel-box me-1"></i> Export Excel
                    </a>
                </div>
            </div>
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Client ID</th>
                            <th>Client Name</th>
                            <th>Email</th>
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
                        title: 'Client Report',
                        className: 'buttons-excel d-none',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        title: 'Client Report',
                        className: 'buttons-csv d-none',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Client Report',
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
                    url: "{{ config('apiConstants.CLIENT_URLS.CLIENT') }}",
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
                                    "{{ url('user/create') }}", "Edit",
                                    data, "Edit Client") +
                                "</li>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.name + "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.USER_API_URLS.USER_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";

                            html += "</ul>";
                            return html;
                        },
                    },
                    {
                        data: "employee_id",
                    },
                    {
                        data: "name",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('user/create') }}',${row.id}, 'Edit Client')"
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
                        data: "updated_name",
                    },
                    {
                        data: "updated_at_formatted",
                    },
                    {
                        data: "is_active",
                        render: function(data) {
                            return data === 1 ?
                                `<span class="badge rounded bg-label-success">Active</span>` :
                                `<span class="badge rounded bg-label-danger">Inactive</span>`;
                        }
                    }

                ]
            });
        }
    </script>
@endsection
