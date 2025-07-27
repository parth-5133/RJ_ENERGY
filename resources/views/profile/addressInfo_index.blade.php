@php
    $id = request()->query('id');
    $controller = new App\Http\Controllers\Web\ProfileController();
    $profileHeader = $controller->profileHeader($id);
@endphp
@extends('layouts.layout')
@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6><span class="text-muted fw-light">Account Settings /</span> Address Information</h6>
        {{ $profileHeader }}
        <div class="row">
            <div class="col-md-12">
                @include('profile.nav-tabs')
                <div class="card mb-4">
                    <h5 class="card-header mb-4">Permanent Address</h5>
                    <div class="card-body">
                        <form id="permanentForm" name="commonform" method="POST">
                            <div class="row gy-4">
                                <input type="hidden" id="user_uuid" name="user_uuid" value="{{ request()->get('id') }}" />
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_contact_name"
                                            name="PerAdd_contact_name" placeholder="Contact Name" />
                                        <label for="contact_name">Contact Name <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_contact_name-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_address_line_1"
                                            name="PerAdd_address_line_1" placeholder="Address Line 1" />
                                        <label for="address_line_1">Address Line 1 <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_address_line_1-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_address_line_2"
                                            name="PerAdd_address_line_2" placeholder="Address Line 2" />
                                        <label for="address_line_2">Address Line 2 <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_address_line_2-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_address_line_3"
                                            name="PerAdd_address_line_3" placeholder="Address Line 3" />
                                        <label for="address_line_3">Address Line 3 </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="PerAdd_country" name="PerAdd_country"
                                            onchange="populateStateDropdown(this.value, '#PerAdd_state')">
                                            <option value="">Select Country</option>
                                        </select>
                                        <label for="PerAdd_country">Country <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_country-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="PerAdd_state" name="PerAdd_state">
                                            <option value="">Select State</option>
                                        </select>
                                        <label for="PerAdd_state">State <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_state-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_city" name="PerAdd_city"
                                            placeholder="City" />
                                        <label for="city">City <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_city-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_pin_code"
                                            name="PerAdd_pin_code" placeholder="Pin Code" />
                                        <label for="pin_code">Pin Code <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_pin_code-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="PerAdd_mobile_no" name="PerAdd_mobile_no"
                                            class="form-control" placeholder="Mobile No" />
                                        <label for="mobile_no">Mobile No <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_mobile_no-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_alternate_mobile_no"
                                            name="PerAdd_alternate_mobile_no" placeholder="Alternate Mobile No" />
                                        <label for="alternate_mobile_no">Alternate Mobile No </label>
                                        <span class="text-danger" id="PerAdd_alternate_mobile_no-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control" id="PerAdd_residing_from"
                                            name="PerAdd_residing_from" max="{{ date('Y-m-d') }}"
                                            placeholder="Residing From" />
                                        <label for="residing_from">Residing From <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_residing_from-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_area" name="PerAdd_area"
                                            placeholder="Area" />
                                        <label for="area">Area </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_landmark"
                                            name="PerAdd_landmark" placeholder="Landmark" />
                                        <label for="landmark">Landmark </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_latitude"
                                            name="PerAdd_latitude" placeholder="Latitude" />
                                        <label for="latitude">Latitude <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_latitude-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PerAdd_longitude"
                                            name="PerAdd_longitude" placeholder="Longitude" />
                                        <label for="longitude">Longitude <span style="color:red">*</span></label>
                                        <span class="text-danger" id="PerAdd_longitude-error"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-header mb-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Present Address</h5>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck">
                            <label class="form-check-label" for="defaultCheck"> Same as Permanent Address</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="presentForm" name="commonform" method="POST">
                            <div class="row gy-4">
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_contact_name"
                                            name="contact_name" placeholder="Contact Name" />
                                        <label for="contact_name">Contact Name</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_address_line_1"
                                            name="address_line_1" placeholder="Address Line 1" />
                                        <label for="address_line_1">Address Line 1</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_address_line_2"
                                            name="address_line_2" placeholder="Address Line 2" />
                                        <label for="address_line_2">Address Line 2 </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_address_line_3"
                                            name="address_line_3" placeholder="Address Line 3" />
                                        <label for="address_line_3">Address Line 3 </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="PreAdd_country" name="country"
                                            onchange="populateStateDropdown(this.value, '#PreAdd_state')">
                                            <option value="">Select Country</option>
                                        </select>
                                        <label for="PreAdd_country">Country</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="PreAdd_state" name="state">
                                            <option value="">Select State</option>
                                        </select>
                                        <label for="PreAdd_state">State</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_city" name="city"
                                            placeholder="City" />
                                        <label for="city">City</label>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_pin_code" name="pin_code"
                                            placeholder="Pin Code" />
                                        <label for="pin_code">Pin Code</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="PreAdd_mobile_no" name="mobile_no"
                                            class="form-control" placeholder="Mobile No" />
                                        <label for="mobile_no">Mobile No</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_alternate_mobile_no"
                                            name="alternate_mobile_no" placeholder="Alternate Mobile No" />
                                        <label for="alternate_mobile_no">Alternate Mobile No </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control" id="PreAdd_residing_from"
                                            max="{{ date('Y-m-d') }}" name="residing_from" placeholder="Residing From" />
                                        <label for="residing_from">Residing From</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_area" name="area"
                                            placeholder="Area" />
                                        <label for="area">Area </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_landmark" name="landmark"
                                            placeholder="Landmark" />
                                        <label for="landmark">Landmark </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_latitude" name="latitude"
                                            placeholder="Latitude" />
                                        <label for="latitude">Latitude</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="PreAdd_longitude"
                                            name="longitude" placeholder="Longitude" />
                                        <label for="longitude">Longitude</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <h5 class="card-header mb-4">Emergency Address</h5>
                    <div class="card-body">
                        <form id="emergencyForm" name="commonform" method="POST">
                            <div class="row gy-4">
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_contact_name"
                                            name="contact_name" placeholder="Contact Name" />
                                        <label for="contact_name">Contact Name</label>
                                        <span class="text-danger" id="EmergencyAddress.contact_name-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_address_line_1"
                                            name="address_line_1" placeholder="Address Line 1" />
                                        <label for="address_line_1">Address Line 1</label>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_address_line_2"
                                            name="address_line_2" placeholder="Address Line 2" />
                                        <label for="address_line_2">Address Line 2 </label>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_address_line_3"
                                            name="address_line_3" placeholder="Address Line 3" />
                                        <label for="address_line_3">Address Line 3 </label>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="EmgAdd_country" name="country"
                                            onchange="populateStateDropdown(this.value, '#EmgAdd_state')">
                                            <option value="">Select Country</option>
                                        </select>
                                        <label for="EmgAdd_country">Country</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="EmgAdd_state" name="state">
                                            <option value="">Select State</option>
                                        </select>
                                        <label for="EmgAdd_state">State</label>
                                        <span class="text-danger" id="EmergencyAddress.state-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_city" name="city"
                                            placeholder="City" />
                                        <label for="city">City</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_pin_code" name="pin_code"
                                            placeholder="Pin Code" />
                                        <label for="pin_code">Pin Code</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="EmgAdd_mobile_no" name="mobile_no"
                                            class="form-control" placeholder="Mobile No" />
                                        <label for="mobile_no">Mobile No</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_alternate_mobile_no"
                                            name="alternate_mobile_no" placeholder="Alternate Mobile No" />
                                        <label for="alternate_mobile_no">Alternate Mobile No </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control" id="EmgAdd_residing_from"
                                            name="residing_from" max="{{ date('Y-m-d') }}" placeholder="Residing From" />
                                        <label for="residing_from">Residing From</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_area" name="area"
                                            placeholder="Area" />
                                        <label for="area">Area </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_landmark" name="landmark"
                                            placeholder="Landmark" />
                                        <label for="landmark">Landmark </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_latitude" name="latitude"
                                            placeholder="Latitude" />
                                        <label for="latitude">Latitude</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="EmgAdd_longitude"
                                            name="longitude" placeholder="Longitude" />
                                        <label for="longitude">Longitude</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center p-3 mt-5">
                                <button class="btn rounded-pill btn-outline-secondary me-2" type="button"
                                    onclick="window.history.back();">
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
    </div>
    <!-- / Content -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <script type="text/javascript">
        var allStates = [];
        $(document).ready(function() {


            let url =
                `{{ config('apiConstants.PROFILE_URLS.PROFILE') }}?id={{ request()->get('id') }}&Params='Address' `;

            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    // Populate Country dropdown
                    const countryDropdown = $("#PerAdd_country,#PreAdd_country,#EmgAdd_country");
                    countryDropdown.empty(); // Clear existing options
                    countryDropdown.append(
                        '<option value="">Select Country</option>'); // Add default option
                    response.data.nationalities.forEach(function(nationality) {
                        countryDropdown.append(
                            `<option value="${nationality.id}">${nationality.name}</option>`);
                    });
                    // Store all states data
                    allStates = response.data.state;

                    // Populate state dropdowns if country is selected
                    const countryId = $("#PerAdd_country").val(); // Assume country dropdown ID
                    populateStateDropdown(countryId, "#PerAdd_state");
                    populateStateDropdown(countryId, "#PreAdd_state");
                    populateStateDropdown(countryId, "#EmgAdd_state");
                }
            });

            fnCallAjaxHttpGetEvent(
                "{{ config('apiConstants.PROFILE_URLS.PROFILE') }}?id={{ request()->get('id') }}&Params='Address'",
                null, true,
                true,
                function(response) {
                    if (response.status === 200 && response.data.employeeAddresses) {
                        const employeeAddresses = response.data.employeeAddresses;

                        // Loop through each address and populate fields
                        employeeAddresses.forEach(function(address) {
                            populateAddressFields(address);
                        });
                    }
                });

            function populateAddressFields(address) {
                // Determine which section to update based on address.type
                var prefix = getPrefixForAddressType(address.type);

                // Populate fields
                $(`#${prefix}_contact_name`).val(address.contact_name);
                $(`#${prefix}_address_line_1`).val(address.address_line_1);
                $(`#${prefix}_address_line_2`).val(address.address_line_2);
                $(`#${prefix}_address_line_3`).val(address.address_line_3);
                $(`#${prefix}_country`).val(address.country);
                populateStateDropdown(address.country, `#${prefix}_state`);
                $(`#${prefix}_state`).val(address.state);
                $(`#${prefix}_city`).val(address.city);
                $(`#${prefix}_pin_code`).val(address.pin_code);
                $(`#${prefix}_mobile_no`).val(address.mobile_no);
                $(`#${prefix}_alternate_mobile_no`).val(address.alternate_mobile_no);
                $(`#${prefix}_residing_from`).val(address.residing_from);
                $(`#${prefix}_area`).val(address.area);
                $(`#${prefix}_landmark`).val(address.landmark);
                $(`#${prefix}_latitude`).val(address.latitude);
                $(`#${prefix}_longitude`).val(address.longitude);
            }

            function getPrefixForAddressType(type) {
                switch (type) {
                    case 'Permanent':
                        return 'PerAdd';
                    case 'Present':
                        return 'PreAdd';
                    case 'Emergency':
                        return 'EmgAdd';
                    default:
                        return '';
                }
            }

            function copyPermanentToPresent() {
                $('#PreAdd_contact_name').val($('#PerAdd_contact_name').val());
                $('#PreAdd_address_line_1').val($('#PerAdd_address_line_1').val());
                $('#PreAdd_address_line_2').val($('#PerAdd_address_line_2').val());
                $('#PreAdd_address_line_3').val($('#PerAdd_address_line_3').val());
                $('#PreAdd_country').val($('#PerAdd_country').val()).trigger('change');
                $('#PreAdd_state').val($('#PerAdd_state').val());
                $('#PreAdd_city').val($('#PerAdd_city').val());
                $('#PreAdd_pin_code').val($('#PerAdd_pin_code').val());
                $('#PreAdd_mobile_no').val($('#PerAdd_mobile_no').val());
                $('#PreAdd_alternate_mobile_no').val($('#PerAdd_alternate_mobile_no').val());
                $('#PreAdd_residing_from').val($('#PerAdd_residing_from').val());
                $('#PreAdd_area').val($('#PerAdd_area').val());
                $('#PreAdd_landmark').val($('#PerAdd_landmark').val());
                $('#PreAdd_latitude').val($('#PerAdd_latitude').val());
                $('#PreAdd_longitude').val($('#PerAdd_longitude').val());
            }
            // Checkbox change event
            $('#defaultCheck').change(function() {
                if ($(this).is(':checked')) {
                    copyPermanentToPresent();
                    $('#permanentForm input, #permanentForm select').on('input change', function() {
                        if ($('#defaultCheck').is(':checked')) {
                            copyPermanentToPresent();
                        }
                    });
                } else {
                    $('#presentForm input, #presentForm select').off('input change');
                    clearPresentForm();
                }
            });
        });

        function clearPresentForm() {
            $('#presentForm input, #presentForm select').each(function() {
                $(this).val('').trigger('change');
            });
        }

        function populateStateDropdown(countryId, stateDropdownSelector) {
            const stateDropdown = $(stateDropdownSelector);
            stateDropdown.empty(); // Clear existing options
            stateDropdown.append('<option value="">Select State</option>'); // Add default option
            if (countryId) {
                // Filter states based on selected country
                const filteredStates = allStates.filter(state => state.country_id == countryId);
                filteredStates.forEach(function(state) {
                    stateDropdown.append(`<option value="${state.id}">${state.name}</option>`);
                });
            }
        }


        $("#permanentForm,#presentForm,#emergencyForm").validate({
            rules: {
                PerAdd_contact_name: {
                    required: true,
                    minlength: 3
                },
                PerAdd_address_line_1: {
                    required: true,
                    minlength: 5
                },
                PerAdd_address_line_2: {
                    required: true,
                    minlength: 5
                },
                PerAdd_country: {
                    required: true
                },
                PerAdd_state: {
                    required: true
                },
                PerAdd_city: {
                    required: true,
                    minlength: 3
                },
                PerAdd_pin_code: {
                    required: true,
                    digits: true,
                    minlength: 6,
                    maxlength: 6
                },
                PerAdd_mobile_no: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                PerAdd_alternate_mobile_no: {
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                PerAdd_residing_from: {
                    required: true
                },
                PerAdd_latitude: {
                    required: true,
                    number: true
                },
                PerAdd_longitude: {
                    required: true,
                    number: true
                }
            },
            messages: {
                PerAdd_contact_name: {
                    required: "Please enter the contact name.",
                    minlength: "Contact name must be at least 3 characters long."
                },
                PerAdd_address_line_1: {
                    required: "Please enter address line 1.",
                    minlength: "Address line 1 must be at least 5 characters long."
                },
                PerAdd_address_line_2: {
                    required: "Please enter address line 2.",
                    minlength: "Address line 2 must be at least 5 characters long."
                },
                PerAdd_country: {
                    required: "Please select a country."
                },
                PerAdd_state: {
                    required: "Please select a state."
                },
                PerAdd_city: {
                    required: "Please enter a city.",
                    minlength: "City name must be at least 3 characters long."
                },
                PerAdd_pin_code: {
                    required: "Please enter a pin code.",
                    digits: "Pin code must be a valid number.",
                    minlength: "Pin code must be 6 digits long.",
                    maxlength: "Pin code must be 6 digits long."
                },
                PerAdd_mobile_no: {
                    required: "Please enter a mobile number.",
                    digits: "Mobile number must be a valid number.",
                    minlength: "Mobile number must be 10 digits long.",
                    maxlength: "Mobile number must be 10 digits long."
                },
                PerAdd_alternate_mobile_no: {
                    digits: "Mobile number must be a valid number.",
                    minlength: "Mobile number must be 10 digits long.",
                    maxlength: "Mobile number must be 10 digits long."
                },
                PerAdd_residing_from: {
                    required: "Please enter the residing from date."
                },
                PerAdd_latitude: {
                    required: "Please enter the latitude.",
                    number: "Latitude must be a valid number."
                },
                PerAdd_longitude: {
                    required: "Please enter the longitude.",
                    number: "Longitude must be a valid number."
                }
            },
            errorPlacement: function(error, element) {
                var errorId = element.attr("name") +
                    "-error";
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

                var postData = {

                    PerAdd_contact_name: $("#PerAdd_contact_name").val(),
                    PerAdd_address_line_1: $("#PerAdd_address_line_1").val(),
                    PerAdd_address_line_2: $("#PerAdd_address_line_2").val(),
                    PerAdd_address_line_3: $("#PerAdd_address_line_3").val(),
                    PerAdd_country: $("#PerAdd_country").val(),
                    PerAdd_state: $("#PerAdd_state").val(),
                    PerAdd_city: $("#PerAdd_city").val(),
                    PerAdd_pin_code: $("#PerAdd_pin_code").val(),
                    PerAdd_mobile_no: $("#PerAdd_mobile_no").val(),
                    PerAdd_alternate_mobile_no: $("#PerAdd_alternate_mobile_no").val(),
                    PerAdd_residing_from: $("#PerAdd_residing_from").val(),
                    PerAdd_area: $("#PerAdd_area").val(),
                    PerAdd_landmark: $("#PerAdd_landmark").val(),
                    PerAdd_latitude: $("#PerAdd_latitude").val(),
                    PerAdd_longitude: $("#PerAdd_longitude").val(),

                    PreAdd_contact_name: $("#PreAdd_contact_name").val(),
                    PreAdd_address_line_1: $("#PreAdd_address_line_1").val(),
                    PreAdd_address_line_2: $("#PreAdd_address_line_2").val(),
                    PreAdd_address_line_3: $("#PreAdd_address_line_3").val(),
                    PreAdd_country: $("#PreAdd_country").val(),
                    PreAdd_state: $("#PreAdd_state").val(),
                    PreAdd_city: $("#PreAdd_city").val(),
                    PreAdd_pin_code: $("#PreAdd_pin_code").val(),
                    PreAdd_mobile_no: $("#PreAdd_mobile_no").val(),
                    PreAdd_alternate_mobile_no: $("#PreAdd_alternate_mobile_no").val(),
                    PreAdd_residing_from: $("#PreAdd_residing_from").val(),
                    PreAdd_area: $("#PreAdd_area").val(),
                    PreAdd_landmark: $("#PreAdd_landmark").val(),
                    PreAdd_latitude: $("#PreAdd_latitude").val(),
                    PreAdd_longitude: $("#PreAdd_longitude").val(),

                    EmgAdd_contact_name: $("#EmgAdd_contact_name").val(),
                    EmgAdd_address_line_1: $("#EmgAdd_address_line_1").val(),
                    EmgAdd_address_line_2: $("#EmgAdd_address_line_2").val(),
                    EmgAdd_address_line_3: $("#EmgAdd_address_line_3").val(),
                    EmgAdd_country: $("#EmgAdd_country").val(),
                    EmgAdd_state: $("#EmgAdd_state").val(),
                    EmgAdd_city: $("#EmgAdd_city").val(),
                    EmgAdd_pin_code: $("#EmgAdd_pin_code").val(),
                    EmgAdd_mobile_no: $("#EmgAdd_mobile_no").val(),
                    EmgAdd_alternate_mobile_no: $("#EmgAdd_alternate_mobile_no").val(),
                    EmgAdd_residing_from: $("#EmgAdd_residing_from").val(),
                    EmgAdd_area: $("#EmgAdd_area").val(),
                    EmgAdd_landmark: $("#EmgAdd_landmark").val(),
                    EmgAdd_latitude: $("#EmgAdd_latitude").val(),
                    EmgAdd_longitude: $("#EmgAdd_longitude").val()
                };
                const Url =
                    `{{ config('apiConstants.PROFILE_URLS.ADDRESS') }}?id={{ request()->get('id') }}`;
                fnCallAjaxHttpPostEvent(
                    Url, postData, true, true,
                    function(response) {
                        if (response.status === 200) {
                            location.reload();
                            ShowMsg("bg-success", response.message);
                        } else {
                            ShowMsg("bg-warning", 'The record could not be processed.');
                        }
                    });
            }
        });
    </script>
@endsection
