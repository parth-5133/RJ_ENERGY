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
                        onClick="fnAddEdit(this, '{{ url('email-settings/create') }}', 0, 'Add Email Configuration')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Configuration
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Driver</th>
                            <th>Host</th>
                            <th>From Address</th>
                            <th>CC Address</th>
                            <th>From Name</th>
                            <th>Status</th>
                            <th>Modified By</th>
                            <th>Modified Date</th>
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
                    [7, "desc"]
                ],
                ajax: {
                    url: "{{ config('apiConstants.EMAIL_SETTINGS_URLS.EMAIL_SETTINGS') }}",
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

                            // Edit Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canEdit'] }},
                                    "{{ url('email-settings/create') }}", "Edit",
                                    data, "Edit Email Configuration") +
                                "</li>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.mail_from_name +
                                    "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.EMAIL_SETTINGS_URLS.EMAIL_SETTINGS_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";

                            // Test Connection Button
                            html += "<li class='list-inline-item'>" +
                                "<button class='btn btn-sm btn-text-success rounded btn-icon item-edit' style='background-color: #cfffd4; color:#00890e;' title='Test Connection' " +
                                "onclick=\"testEmailConnection(" + data + ")\">" +
                                "<i class='mdi mdi-email-check-outline'></i></button>" +
                                "</li>";

                            html += "</ul>";
                            return html;
                        },
                    },
                    {
                        data: "mail_driver",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('email-settings/create') }}',${row.id}, 'Edit Email Configuration')"
                            class="user-name" data-id="${row.id}">
                            ${data}
                        </a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: "mail_host",
                    },
                    {
                        data: "mail_from_address",
                    },
                    {
                        data: "cc_mail_username",
                    },
                    {
                        data: "mail_from_name",
                    },
                    {
                        data: "is_active",
                        render: function(data) {
                            return data === 1 ?
                                `<span class="badge rounded bg-label-success">Active</span>` :
                                `<span class="badge rounded bg-label-danger">Inactive</span>`;
                        }
                    },
                    {
                        data: "updated_name",
                    },
                    {
                        data: "updated_at_formatted",
                    },
                ]
            });
        }

        function testEmailConnection(id) {
            var url = "{{ config('apiConstants.EMAIL_SETTINGS_URLS.EMAIL_SETTINGS_TEST_CONNECTION') }}";
            var postData = {
                id: id
            };

            fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                if (response.status === 200) {
                    ShowMsg("bg-success", response.message);
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        }
    </script>
@endsection
