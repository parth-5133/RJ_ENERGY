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
                        onClick="fnAddEdit(this, '{{ url('policy/create') }}', 0, 'Add Policy',true)">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Policy
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Effective Date</th>
                            <th>Expiration Date</th>
                            <th>Policy Document</th>
                            <th>Issued By</th>
                            @if ($permissions['canDelete'] || $permissions['canEdit'])
                                <th>Status</th>
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
                    url: "{{ config('apiConstants.POLICY_URLS.POLICY') }}",
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

                            html += "<li class='list-inline-item'>" +
                                "<button class='btn btn-sm btn-text-secondary rounded btn-icon item-edit' " +
                                "style='background-color: #c7e9ff !important; color:#009dff !important;' title='View Policy' " +
                                "onClick=\"fnAddEdit(this, '{{ url('policy/view') }}', " + data +
                                ", '" + row.policy_name + "', true)\">" +
                                "<i class='mdi mdi-eye-arrow-right-outline'></i></button>" +
                                "</li>";

                            // Edit Button (This is your existing edit button logic)
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canEdit'] }},
                                    "{{ url('policy/create') }}", "Edit",
                                    data, "Edit Policy", true) +
                                "</li>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.policy_name + "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.POLICY_URLS.POLICY_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";

                            html += "</ul>";
                            return html;
                        },
                    },
                    {
                        data: "policy_name",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('policy/create') }}',${row.id}, 'Edit Policy',true)"
                            class="user-name" data-id="${row.id}">
                            ${data}
                        </a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: "effective_date",
                    },
                    {
                        data: "expiration_date",
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            if (row.document_name && row.document_path) {
                                return `
                <div>
                    <a href="{{ url('/storage/') }}/${row.document_path}" target="_blank" style="display: inline-block; text-decoration: none;">
                        <span style="border-bottom: 1px solid #891ab4;">${row.document_name}</span>
                    </a>
                </div>
            `;
                            }
                            return ''; // Safe fallback if no document
                        }
                    },
                    {
                        data: "issued_by",
                    },
                    @if ($permissions['canDelete'] || $permissions['canEdit'])
                        {
                            data: "is_active",
                            render: function(data) {
                                return data === 1 ?
                                    `<span class="badge rounded bg-label-success">Active</span>` :
                                    `<span class="badge rounded bg-label-danger">Inactive</span>`;
                            }
                        }, {
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
