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
                        onClick="fnAddEdit(this, '{{ url('/holidays/create') }}', 0, 'Add Holiday')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Holiday
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
                            <th>Date</th>
                            <th>Day</th>
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
        $(document).ready(function() {
            initializeDataTable();
        });

        function initializeDataTable() {
            $("#grid").DataTable({
                responsive: true,
                autoWidth: false,
                serverSide: false,
                processing: true,
                language: {
                    "loadingRecords": "&nbsp;",
                    "processing": "<img src='{{ asset('assets/img/illustrations/loader.gif') }}' alt='loader' />"
                },
                order: [
                    [2, 'asc']
                ], // Sort by the holiday date column (third column)
                ajax: {
                    url: "{{ config('apiConstants.ADMIN_HOLIDAY_URLS.HOLIDAY') }}",
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
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canEdit'] }},
                                        "{{ url('/holidays/create') }}", "Edit",
                                        data, "Edit Holiday") +
                                    "</li>";
                                html += "<li class='list-inline-item'>" +
                                    GetEditDeleteButton({{ $permissions['canDelete'] }},
                                        "fnShowConfirmDeleteDialog('" + row.holiday_name +
                                        "',fnDeleteRecord," +
                                        data + ",'" +
                                        '{{ config('apiConstants.ADMIN_HOLIDAY_URLS.HOLIDAY_DELETE') }}' +
                                        "','#grid')", "Delete") +
                                    "</li>";
                                html += "</ul>";
                                return html;
                            },
                        }, {
                            data: "holiday_name",
                            render: function(data, type, row) {
                                if ({{ $permissions['canEdit'] }}) {
                                    return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('/holidays/create') }}',${row.id}, 'Edit Holiday')"
                                class="user-name" data-id="${row.id}">${data}</a>`;
                                }
                                return data;
                            }
                        }, {
                            data: "holiday_date",
                            render: function(data, type, row) {
                                if (type === "display") {
                                    return row.holiday_date_formatted; // Show formatted date to user
                                }
                                return data; // Use raw holiday_date for sorting
                            }
                        }, {
                            data: "day",
                        }, {
                            data: "updated_name",
                        }, {
                            data: "updated_at_formatted",
                        }
                    @else
                        {
                            data: "holiday_name",
                        }, {
                            data: "holiday_date",
                            render: function(data, type, row) {
                                if (type === "display") {
                                    return row.holiday_date_formatted;
                                }
                                return data;
                            }
                        }, {
                            data: "day",
                        }
                    @endif
                ]
            });
        }
    </script>
@endsection
