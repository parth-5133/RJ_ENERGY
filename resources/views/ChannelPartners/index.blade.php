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
                        onClick="fnAddEdit(this, '{{ url('channel-partners/create') }}', 0, 'Add Channel Partners')">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Add Channel Partners
                    </button>
                @endif
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Channel Partners Name</th>
                            <th>Phone</th>
                            <th>GST Number</th>
                            <th>PAN Number</th>
                            <th>Logo</th>
                            <th>State</th>
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
                    url: "{{ config('apiConstants.CHANNEL_PARTNERS_URLS.CHANNEL_PARTNERS') }}",
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
                                    "{{ url('channel-partners/create') }}", "Edit",
                                    data, "Edit Channel Partners") +
                                "</li>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.legal_name + "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.CHANNEL_PARTNERS_URLS.CHANNEL_PARTNERS_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";
                            return html;
                        },
                    },
                    {
                        data: "legal_name",
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `${data.phone}`;
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
                        data: "city"
                    },
                    {
                        data: "pin_code"
                    }
                ]
            });
        }
    </script>
@endsection
