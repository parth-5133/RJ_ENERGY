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
                        onClick="fnAddEdit(this, '{{ url('deduction/create') }}', 0, 'Add Deduction')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Deduction
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Deduction</th>
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
                    [1, "desc"]
                ],
                ajax: {
                    url: "{{ config('apiConstants.DEDUCTION_URLS.DEDUCTION') }}",
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
                                    "{{ url('deduction/create') }}", "Edit",
                                    data, "Edit Deduction") +
                                "</li>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.deduction_name +
                                    "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.DEDUCTION_URLS.DEDUCTION_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";

                            return html;
                        },
                    },
                    {
                        data: "deduction_name",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('deduction/create') }}',${row.id}, 'Edit Deduction')"
                            class="user-name" data-id="${row.id}">
                            ${data}
                        </a>`;
                            }
                            return data;
                        }
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
