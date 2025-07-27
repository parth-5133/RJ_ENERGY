@php
    $id = request()->query('id');
    $controller = new App\Http\Controllers\Web\ProfileController();
    $profileHeader = $controller->profileHeader($id);
@endphp
@extends('layouts.layout')
@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6><span class="text-muted fw-light">Account Settings /</span> Vehicle Information</h6>
        {{ $profileHeader }}
        <div class="row">
            <div class="col-md-12">
                @include('profile.nav-tabs')
                <div class="card mb-4">
                    <h5 class="card-header mb-4">Vehicle Details</h5>
                    <div class="card-body">
                        <form id="commonform" name="commonform" method="POST">
                            <input type="hidden" id="user_uuid" name="user_uuid" value="{{ request()->get('id') }}" />
                            <div class="row gy-4">
                                <input type="hidden" id="id" name="id" />
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="vehicle_make" name="vehicle_make"
                                            placeholder="Make" />
                                        <label for="vehicle_make">Make <span style="color:red">*</span></label>
                                        <span class="text-danger" id="vehicle_make-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="vehicle_model" name="vehicle_model"
                                            placeholder="Model" />
                                        <label for="vehicle_model">Model <span style="color:red">*</span></label>
                                        <span class="text-danger" id="vehicle_model-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-control" id="vehicle_type" name="vehicle_type">
                                            <option value="0" disabled selected>Select Vehicle Type</option>
                                            <option value="1">2 Wheel</option>
                                            <option value="2">3 Wheel</option>
                                            <option value="3">4 Wheel</option>
                                        </select>
                                        <label for="vehicle_type">Vehicle Type <span style="color:red">*</span></label>
                                        <span class="text-danger" id="vehicle_type-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="vehicle_number" name="vehicle_number"
                                            placeholder="Vehicle No" />
                                        <label for="vehicle_number">Vehicle No <span style="color:red">*</span></label>
                                        <span class="text-danger" id="vehicle_number-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="driving_license_no"
                                            name="driving_license_no" placeholder="Driving License No" />
                                        <label for="driving_license_no">Driving License No <span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="driving_license_no-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center p-3 mt-5">
                                <button class="btn rounded-pill btn-outline-secondary me-2" type="button"
                                    id="closeFormButton" data-bs-dismiss="offcanvas" onclick="window.history.back();">
                                    <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
                                </button>
                                <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light"
                                    id="submitButton">
                                    <span class="tf-icons mdi mdi-checkbox-marked-circle-outline me-1"></span>Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table id="grid" class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Make</th>
                            <th>Model</th>
                            <th>Vehicle Type</th>
                            <th>Vehicle No</th>
                            <th>Driving License No</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->

    <script type="text/javascript">
        $(document).ready(function() {
            // Event listener for the Cancel button
            $('#closeFormButton').click(function() {
                $('#commonform input,#commonform select').each(function() {
                    $(this).val('').trigger('change');
                });
            });

            loadVehicleData();
        });

        $("#commonform").validate({
            rules: {
                user_uuid: {
                    required: true,
                },
                vehicle_make: {
                    required: true,
                    maxlength: 50,
                },
                vehicle_model: {
                    required: true,
                    maxlength: 50,
                },
                vehicle_type: {
                    required: true,
                },
                vehicle_number: {
                    required: true,
                    maxlength: 20,
                },
                driving_license_no: {
                    required: true,
                    maxlength: 20,
                },
            },
            messages: {
                user_uuid: {
                    required: "User UUID is required",
                },
                vehicle_make: {
                    required: "Vehicle make is required",
                    maxlength: "Vehicle make cannot be more than 50 characters",
                },
                vehicle_model: {
                    required: "Vehicle model is required",
                    maxlength: "Vehicle model cannot be more than 50 characters",
                },
                vehicle_type: {
                    required: "Vehicle type is required",
                },
                vehicle_number: {
                    required: "Vehicle number is required",
                    maxlength: "Vehicle number cannot be more than 20 characters",
                },
                driving_license_no: {
                    required: "Driving license number is required",
                    maxlength: "Driving license number cannot be more than 20 characters",
                },
            },
            errorPlacement: function(error, element) {
                var errorId = element.attr("name") + "-error";
                $("#" + errorId).text(error.text());
                $("#" + errorId).show();
                element.addClass("is-invalid");
            },
            success: function(label, element) {
                var errorId = $(element).attr("name") + "-error";
                $("#" + errorId).text("");
                $(element).removeClass("is-invalid");
            },
            submitHandler: function(form) {
                event.preventDefault();

                const postData = {
                    id: $("#id").val(),
                    user_uuid: $("#user_uuid").val(),
                    vehicle_make: $("#vehicle_make").val(),
                    vehicle_model: $("#vehicle_model").val(),
                    vehicle_type: $("#vehicle_type").val(),
                    vehicle_number: $("#vehicle_number").val(),
                    driving_license_no: $("#driving_license_no").val(),
                };

                const Url = `{{ config('apiConstants.PROFILE_URLS.VEHICLE') }}/{{ request()->get('id') }}`;

                fnCallAjaxHttpPostEvent(Url, postData, true, true, function(response) {
                    if (response.status === 200) {
                        location.reload();
                        loadVehicleData();
                        ShowMsg("bg-success", response.message);
                    } else {
                        ShowMsg("bg-warning", 'The record could not be processed.');
                    }
                });
            }
        });

        function bindEditButtons() {
            $('.edit-button').on('click', function() {
                const id = $(this).data('id');
                fetchVehicleData(id);
            });
        }

        function fetchVehicleData(Id) {
            fnCallAjaxHttpGetEvent(`{{ config('apiConstants.PROFILE_URLS.VEHICLE_VIEW') }}/${Id}`, null, true, true,
                function(
                    response) {
                    if (response.status === 200 && response.data.employeeVehicle) {
                        // Populate form fields with user data
                        $("#id").val(response.data.employeeVehicle.id);
                        $("#vehicle_make").val(response.data.employeeVehicle.vehicle_make);
                        $("#vehicle_model").val(response.data.employeeVehicle.vehicle_model);
                        $("#vehicle_type").val(response.data.employeeVehicle.vehicle_type);
                        $("#vehicle_number").val(response.data.employeeVehicle.vehicle_number);
                        $("#driving_license_no").val(response.data.employeeVehicle.driving_license_no);
                    } else {
                        console.log('Failed to retrieve  data.');
                    }
                });
        }

        function loadVehicleData() {
            const userId = "{{ $id }}";
            const Url = `{{ config('apiConstants.PROFILE_URLS.VEHICLE_LIST') }}/${userId}`;
            fnCallAjaxHttpGetEvent(Url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    let tbody = $('#grid tbody');
                    tbody.empty();
                    response.data.forEach(function(item) {
                        let vehicleTypeText = '';
                        // Check the value of vehicle_type and assign appropriate text
                        if (item.vehicle_type == 1) {
                            vehicleTypeText = '2 Wheel';
                        } else if (item.vehicle_type == 2) {
                            vehicleTypeText = '3 Wheel';
                        } else if (item.vehicle_type == 3) {
                            vehicleTypeText = '4 Wheel';
                        }

                        let row = `
                <tr>
                    <td>${item.vehicle_make}</td>
                    <td>${item.vehicle_model}</td>
                    <td>${vehicleTypeText}</td> <!-- Use the conditional vehicle type text -->
                    <td>${item.vehicle_number}</td>
                    <td>${item.driving_license_no}</td>
                    <td>
                        <ul class="list-inline m-0">
                            <li class="list-inline-item">
                               <button class="btn btn-sm btn-text-primary rounded btn-icon item-edit waves-effect waves-light edit-button" data-id="${item.id}"><i class='mdi mdi-pencil-outline'></i></button>
                            </li>
                            <li class="list-inline-item">
                               ${GetEditDeleteButton('True', `fnShowConfirmDeleteDialog('${item.vehicle_model}', fnDeleteRecord, ${item.id}, '{{ config('apiConstants.PROFILE_URLS.VEHICLE_DELETE') }}', '#grid')`, "Delete")}
                            </li>
                        </ul>
                    </td>
                </tr>`;
                        tbody.append(row);
                    });
                    bindEditButtons();
                } else {
                    console.error('No data available');
                }
            });
        }
    </script>
@endsection
