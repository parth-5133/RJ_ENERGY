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
                        onClick="fnAddEdit(this, '{{ url('company/create') }}', 0, 'Add Company')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Company
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Company Name</th>
                            <th>Phone</th>
                            <th>GST Number</th>
                            <th>PAN Number</th>
                            <th>Logo</th>
                            <th>Country</th>
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
                    url: "{{ config('apiConstants.COMPANY_URLS.COMPANY') }}",
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
                                    "{{ url('company/create') }}", "Edit",
                                    data, "Edit Company") +
                                "</li>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.legal_name + "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.COMPANY_URLS.COMPANY_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";
                            return html;
                        },
                    },
                    {
                        data: "legal_name",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('company/create') }}',${row.id}, 'Edit Company')"
                            class="user-name" data-id="${row.id}">
                            ${data}
                        </a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `${data.phone ?? data.alternate_mobile_no}`;
                        }
                    },
                    {
                        data: "gst_number"
                    },
                    {
                        data: "pan_number"
                    },
                    {
                        data: "logo_url",
                        render: function(data) {
                            return `<img src="${data}" alt="Logo" height="40"/>`;
                        }
                    },
                    {
                        data: "country_name"
                    },
                    {
                        data: "updated_name"
                    },
                    {
                        data: "updated_at"
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

