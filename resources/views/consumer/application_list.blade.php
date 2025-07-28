@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>My Applications</b></h5>
                </div>
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Solar Capacity</th>
                            <th>Roof Type</th>
                            <th>Subsidy Claimed</th>
                            <th>Purchase Mode</th>
                            <th>Loan Required</th>
                            <th>Action</th>
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
                    url: "{{ config('apiConstants.PROPOSAL_URLS.PROPOSAL_LIST') }}",
                    type: "GET",
                    headers: {
                        Authorization: "Bearer " + getCookie("access_token"),
                    },
                },
                columns: [{
                        data: "application_id",
                    },
                    {
                        data: "solar_capacity",
                    },
                    {
                        data: "roof_type",
                    },
                    {
                        data: "subsidy_claimed",
                    },
                    {
                        data: "purchase_mode",
                    },
                    {
                        data: "loan_required",
                    },
                    {
                        data: "id",
                        orderable: false,
                        render: function(data, type, row) {
                            var html = "<ul class='list-inline m-0'>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton(true,
                                    "fnShowConfirmDeleteDialog('" + row.application_id +
                                    "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.PROPOSAL_URLS.PROPOSAL_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";

                            html += "</ul>";
                            return html;
                        },
                    }
                ]
            });
        }
    </script>
@endsection
