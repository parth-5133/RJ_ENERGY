@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="head-label text-center">
                    <h5 class="card-title mb-0"><b>Document List</b></h5>
                </div>
            </div>
            <hr class="my-0">
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Document Name</th>
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
                    url: "{{ config('apiConstants.API_URLS.ROLES') }}",
                    type: "GET",
                    headers: {
                        Authorization: "Bearer " + getCookie("access_token"),
                    },
                },
                columns: [{
                        data: "name",
                        render: function(data, type, row) {
                            if (true) {
                                return `<a href="javascript:void(0);" onclick="fnAddEdit(this,'{{ url('role/create') }}',${row.id}, 'Edit Role')"
                            class="user-name" data-id="${row.id}">
                            ${data}
                        </a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: "id",
                        orderable: false,
                        render: function(data, type, row) {
                            var html = "<ul class='list-inline m-0'>";

                            // Permission Button
                            html += "<li class='list-inline-item'>" +
                                "<button class='btn btn-sm btn-text-success rounded btn-icon item-edit' style='background-color: #cfffd4 !important; color:#00890e !important;' title='Permissions' " +
                                "onclick=\"window.location.href='{{ url('permission') }}/" +
                                data + "'\">" +
                                "<i class='mdi mdi-signal-cellular-outline'></i></button>" +
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
