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
                        onClick="fnAddEdit(this, '{{ url('financial-year/create') }}', 0, 'Add Financial Year')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Financial Year
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Display Year</th>
                            <th>Current Year</th>
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
                    url: "{{ config('apiConstants.FINANCIAL_YEAR_URLS.FINANCIAL_YEAR') }}",
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
                                    "{{ url('financial-year/create') }}", "Edit",
                                    data, "Edit Financial Year") +
                                "</li>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.from_date + "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.FINANCIAL_YEAR_URLS.FINANCIAL_YEAR_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";
                            return html;
                        },
                    },
                    {
                        data: "from_date",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('financial-year/create') }}',${row.id}, 'Edit Financial Year')"
                            class="user-name" data-id="${row.id}">
                            ${data}
                        </a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: "to_date",
                    },
                    {
                        data: "display_year",
                    },
                    {
                        data: "is_currentYear",
                        render: function(data) {
                            return data === 1 ?
                                `<span class="badge rounded bg-label-success">Yes</span>` :
                                `<span class="badge rounded bg-label-danger">No</span>`;
                        }
                    },
                    {
                        data: "updated_name",
                    },
                    {
                        data: "updated_at",
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
