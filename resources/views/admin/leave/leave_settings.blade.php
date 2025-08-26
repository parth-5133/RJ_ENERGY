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
                        onClick="fnAddEdit(this,'{{ url('/leaves/type/create') }}', 0, 'Add Leave Type')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Leave Type
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            @if ($permissions['canEdit'] || $permissions['canDelete'])
                                <th>Action</th>
                            @endif
                            <th>Name</th>
                            <th>Max Days</th>
                            <th>Carry Forward Max Balance</th>
                            <th>Expiry Date</th>
                            <th>Is CurrentYear</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        $(document).ready(function() {
            $('#reset').last().on('click', function() {
                const table = $('#grid').DataTable();
                table.ajax.url(
                        "{{ config('apiConstants.ADMIN_LEAVE_TYPE_URLS.ADMIN_LEAVE_TYPE_SETTING') }}")
                    .load();
            });
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
                    [1, "asc"]
                ],
                ajax: {
                    url: "{{ config('apiConstants.ADMIN_LEAVE_TYPE_URLS.ADMIN_LEAVE_TYPE_SETTING') }}",
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
                                var html = "<ul class='list-inline m-0'>";

                                // Edit Button
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canEdit'] }},
                                        "{{ url('/leaves/type/create') }}", "Edit",
                                        data, "Edit Leave Type") +
                                    "</li>";

                                // Delete Button
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canDelete'] }},
                                        "fnShowConfirmDeleteDialog('" + row
                                        .leave_type_name + "',fnDeleteRecord," +
                                        data + ",'" +
                                        '{{ config('apiConstants.ADMIN_LEAVE_TYPE_URLS.ADMIN_LEAVE_TYPE_DELETE') }}' +
                                        "','#grid')", "Delete") +
                                    "</li>";

                                return html;
                            },
                        }, {
                            data: "leave_type_name",
                            render: function(data, type, row) {
                                if ({{ $permissions['canEdit'] }}) {
                                    return `<a href="javascript:void(0);"onclick="fnAddEdit(this,'{{ url('/leaves/type/create') }}',${row.id}, 'Edit Leave Type')"
                                         class="user-name" data-id="${row.id}">
                                        ${data}
                                        </a>`;
                                }
                                return data;
                            }

                        }, {
                            data: "max_days",
                        }, {
                            data: "carry_forward_max_balance",
                        }, {
                            data: "expiry_date",
                        }, {
                            data: "is_currentYear",
                            render: function(data) {
                                return data === 1 ?
                                    `<span class="badge rounded bg-label-success">Yes</span>` :
                                    `<span class="badge rounded bg-label-danger">No</span>`;
                            }
                        }
                    @else
                        {
                            data: "leave_type_name",
                        }, {
                            data: "max_days",
                        }, {
                            data: "carry_forward_max_balance",
                        }, {
                            data: "expiry_date",
                        }, {
                            data: "is_currentYear",
                            render: function(data) {
                                return data === 1 ?
                                    `<span class="badge rounded bg-label-success">Yes</span>` :
                                    `<span class="badge rounded bg-label-danger">No</span>`;
                            }
                        }
                    @endif
                ]

            });
        }
    </script>
@endsection
