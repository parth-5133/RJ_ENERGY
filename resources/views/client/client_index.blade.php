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
                        onClick="fnAddEdit(this, '{{ url('/client/create') }}', 0, 'Add Solar Application',true)">
                        <span class="tf-icons mdi mdi-plus">&nbsp;</span>Adds New Solar Application
                    </button>
                @endif
            </div>
            <div class="col-12 d-flex align-items-center flex-wrap p-4">
                <!-- Registrar -->
                <div class="form-floating form-floating-outline col-md-2 col-12 me-4 mb-3">
                    <select class="form-select" id="registrarSelect" aria-label="Registrar">
                        <option value="">Select Registrar</option>
                    </select>
                    <label for="registrarSelect">Registrar</label>
                </div>
                <!-- Channel Partner -->
                <div class="form-floating form-floating-outline col-md-2 col-12 me-4 mb-3">
                    <select class="form-select" id="channelPartnerSelect" aria-label="Channel Partner">
                        <option value="">Select Channel Partner</option>
                    </select>
                    <label for="channelPartnerSelect">Channel Partner</label>
                </div>

                <!-- Installer -->
                <div class="form-floating form-floating-outline col-md-2 col-12 me-4 mb-3">
                    <select class="form-select" id="installerSelect" aria-label="Installer">
                        <option value="">Select Installer</option>
                    </select>
                    <label for="installerSelect">Installer</label>
                </div>

                <!-- Buttons -->
                <a href="javascript:void(0)" class="btn btn-sm btn-primary waves-effect waves-light mb-3 me-2"
                    id="searchButton">
                    <i class="mdi mdi-magnify"></i>
                </a>
                <a href="javascript:void(0)" class="btn btn-sm btn-primary waves-effect waves-light mb-3 me-2"
                    id="reset">
                    <i class="mdi mdi-replay me-1"></i> Reset
                </a>
            </div>
            <div class="card-datatable text-nowrap">
                <table id="grid" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>System Entry Date</th>
                            <th>Customer Name</th>
                            <th>Consumer No</th>
                            <th>Solar Capacity</th>
                            <th>Mobile Number</th>
                            <th>Customer Email</th>
                            <th>Aadhar Linked Mobile Number</th>
                            <th>DISCOM Name</th>
                            <th>Channel Partner</th>
                            <th>Installation Team</th>
                            <th>Registrar</th>
                            <th>Quotation Amount</th>
                            <th>Is Completed</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            initializeDataTable();

            lodeData();

            $("#searchButton").click(function() {
                filterGrid();
            });
            $("#reset").click(function() {
                lodeData()
                const table = $('#grid').DataTable();
                table.ajax.url("{{ config('apiConstants.CLIENT_URLS.CLIENT') }}").load();
            });
        });

        function lodeData() {
            fnCallAjaxHttpGetEvent("{{ url('/api/V1/Get-filter') }}", null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    let data = response.data;

                    // Registrar
                    let $registrar = $("#registrarSelect");
                    $registrar.empty().append(new Option("Select Registrar", ""));
                    data.registrar.forEach(function(item) {
                        $registrar.append(new Option(item.full_name, item.id));
                    });

                    // Channel Partners
                    let $channelPartner = $("#channelPartnerSelect");
                    $channelPartner.empty().append(new Option("Select Channel Partner", ""));
                    data.channel_partners.forEach(function(item) {
                        $channelPartner.append(new Option(item.legal_name, item.id));
                    });

                    // Installers
                    let $installer = $("#installerSelect");
                    $installer.empty().append(new Option("Select Installer", ""));
                    data.installers.forEach(function(item) {
                        $installer.append(new Option(item.name, item.id));
                    });
                } else {
                    console.error("Failed to retrieve dropdown data.");
                }
            });
        }

        function filterGrid() {
            const registrarSelect = $("#registrarSelect").val();
            const channelPartnerSelect = $("#channelPartnerSelect").val();
            const installerSelect = $("#installerSelect").val();

            if (!registrarSelect && !channelPartnerSelect && !installerSelect) {
                return;
            }

            $('#grid').DataTable().ajax.url(
                `{{ config('apiConstants.CLIENT_URLS.CLIENT') }}?registrar=${registrarSelect}&channel_partner=${channelPartnerSelect}&installer=${installerSelect}`
            ).load();
        }

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
                    url: "{{ config('apiConstants.CLIENT_URLS.CLIENT') }}",
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
                                    "{{ url('/client/create') }}", "Edit",
                                    data, "Edit Solar Application", true) +
                                "</li>";

                            // Delete Button
                            html += "<li class='list-inline-item'>" +
                                GetEditDeleteButton({{ $permissions['canDelete'] }},
                                    "fnShowConfirmDeleteDialog('" + row.customer_name +
                                    "',fnDeleteRecord," +
                                    data + ",'" +
                                    '{{ config('apiConstants.USER_API_URLS.USER_DELETE') }}' +
                                    "','#grid')", "Delete") +
                                "</li>";

                            // Accept Button (Extra Menu)
                            html += "<li class='list-inline-item'>" +
                                "<button type='button' onclick='acceptcustomer(" + data +
                                ")' class='btn btn-sm btn-success' title='Accept'>" +
                                "<i class='mdi mdi-check-circle'></i>" +
                                "</button>" +
                                "</li>";

                            html += "</ul>";
                            return html;
                        },
                    },
                    {
                        data: "created_at",
                    },
                    {
                        data: "customer_name",
                    },
                    {
                        data: "customer_number",
                        render: function(data, type, row) {
                            if ({{ $permissions['canEdit'] }}) {
                                return `<a href="{{ url('/client/details') }}/${row.id}"
                           class="text-primary">${data}</a>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: "capacity",
                    },
                    {
                        data: "alternate_mobile",
                    },
                    {
                        data: "email",
                    },
                    {
                        data: "mobile",
                    },
                    {
                        data: "solar_company",
                    },
                    {
                        data: "channel_partner_name",
                    },
                    {
                        data: "installer_name",
                    },
                    {
                        data: "assign_to_name",
                    },
                    {
                        data: "amount",
                    },
                    {
                        data: "is_completed",
                        render: function(data) {
                            return data === 1 ?
                                `<span class="badge rounded bg-label-success">Completed</span>` :
                                `<span class="badge rounded bg-label-danger">Pending</span>`;
                        }
                    }
                ]
            });
        }

        function acceptcustomer(id) {
            var Url = "{{ config('apiConstants.CLIENT_URLS.CLIENT_ACCEPT') }}";

            var postData = {
                id: id,
            };

            fnCallAjaxHttpPostEvent(Url, postData, true, true, function(response) {
                if (response.status === 200) {
                    $('#grid').DataTable().ajax.reload();
                    ShowMsg("bg-success", response.message);
                } else {
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        }
    </script>
@endsection
