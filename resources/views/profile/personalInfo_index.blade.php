@php
    $id = request()->query('id');
    $controller = new App\Http\Controllers\Web\ProfileController();
    $profileHeader = $controller->profileHeader($id);
@endphp
@extends('layouts.layout')
@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6><span class="text-muted fw-light">Account Settings /</span> Personal Information</h6>
        {{ $profileHeader }}
        <div class="row">
            <div class="col-md-12">
                @include('profile.nav-tabs')
                <div class="card mb-4">
                    <h5 class="card-header mb-4">Personal Details</h5>
                    <div class="card-body">
                        <form id="commonform" name="commonform" class="form-horizontal" method="POST">
                            <div class="row gy-4">
                                <input type="hidden" id="user_uuid" name="user_uuid" value="{{ request()->get('id') }}" />
                                <!-- First Name -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control onlyLetter" type="text" id="firstName"
                                            name="firstName" placeholder="First Name" readonly />
                                        <label for="firstName">First Name<span style="color:red">*</span></label>
                                        <span class="text-danger" id="firstName-error"></span> <!-- Error span -->
                                    </div>
                                </div>
                                <!-- Middle Name -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control onlyLetter" type="text" id="middleName"
                                            name="middleName" placeholder="Middle Name" readonly />
                                        <label for="middleName">Middle Name</label>
                                        <span class="text-danger" id="middleName-error"></span> <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control onlyLetter" type="text" id="lastName" name="lastName"
                                            placeholder="Last Name" readonly />
                                        <label for="lastName">Last Name<span style="color:red">*</span></label>
                                        <span class="text-danger" id="lastName-error"></span> <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Personal Email -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="email" class="form-control" id="personal_email" name="personal_email"
                                            placeholder="Personal Email" />
                                        <label for="personal_email">Personal Email<span style="color:red">*</span></label>
                                        <span class="text-danger" id="personal_email-error"></span>
                                        <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                            max="{{ date('Y-m-d') }}" placeholder="Date of Birth" />
                                        <label for="date_of_birth">Date of Birth<span style="color:red">*</span></label>
                                        <span class="text-danger" id="date_of_birth-error"></span> <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Place of Birth -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control onlyLetter" id="place_of_birth"
                                            name="place_of_birth" placeholder="Place of Birth" />
                                        <label for="place_of_birth">Place of Birth<span style="color:red">*</span></label>
                                        <span class="text-danger" id="place_of_birth-error"></span>
                                        <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="select2 form-select" id="gender" name="gender">
                                            <option value="">Select Gender</option>
                                            <option value="m">Male</option>
                                            <option value="f">Female</option>
                                            <option value="o">Other</option>
                                        </select>
                                        <label for="gender">Gender<span style="color:red">*</span></label>
                                        <span class="text-danger" id="gender-error"></span> <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="tel" class="form-control onlyNumber" id="phone_number"
                                            name="phone_number" placeholder="Phone Number" maxlength="10" />
                                        <label for="phone_number">Phone Number<span style="color:red">*</span></label>
                                        <span class="text-danger" id="phone_number-error"></span> <!-- Error span -->
                                    </div>
                                </div>
                                <!-- Emergency Phone Number -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control onlyNumber" id="emergency_phone_number"
                                            name="emergency_phone_number" placeholder="Emergency Phone Number"
                                            maxlength="10" />
                                        <label for="emergency_phone_number">Emergency Phone Number<span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="emergency_phone_number-error"></span>
                                        <!-- Error span -->
                                    </div>
                                </div>
                                <!-- Alternate Phone Number -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="tel" class="form-control onlyNumber" id="alternate_phone_number"
                                            name="alternate_phone_number" placeholder="Alternate Phone Number"
                                            maxlength="10" />
                                        <label for="alternate_phone_number">Alternate Phone Number</label>
                                        <span class="text-danger" id="alternate_phone_number-error"></span>
                                        <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Marital Status -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="marital_status_id" name="marital_status_id">
                                            <option value="">Select Marital Status</option>
                                        </select>
                                        <label for="marital_status_id">Marital Status<span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="marital_status_id-error"></span>
                                        <!-- Error span -->
                                    </div>
                                </div>
                                <!-- Disability Status -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="select2 form-select" id="disability_status"
                                            name="disability_status">
                                            <option value="0">Select Disability Status</option>
                                            <option value="1">Yes</option>
                                            <option value="2">No</option>
                                            <option value="3">Do not wish to disclose</option>
                                        </select>
                                        <label for="disability_status">Disability Status<span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="disability_status-error"></span>
                                        <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Citizenship -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="citizenship" name="citizenship"
                                            placeholder="Citizenship (comma separated)"
                                            title="Enter multiple citizenships separated by commas, e.g., 'US, Canada'" />
                                        <label for="citizenship">Citizenship</label>
                                        <span class="text-danger" id="citizenship-error"></span> <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Religion -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control onlyLetter" id="religion"
                                            name="religion" placeholder="Religion" />
                                        <label for="religion">Religion</label>
                                        <span class="text-danger" id="religion-error"></span> <!-- Error span -->
                                    </div>
                                </div>

                                <!-- Blood Group -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select id="blood_group" name="blood_group" class="select2 form-select">
                                            <option value="">Select Blood Group</option>
                                        </select>
                                        <label for="blood_group">Blood Group</label>
                                        <span class="text-danger" id="blood_group-error"></span> <!-- Error span -->
                                    </div>
                                </div>
                                <!-- Aadhaar Number -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control onlyNumber" id="aadhaar_number"
                                            name="aadhaar_number" placeholder="Aadhaar Number" maxlength="12" />
                                        <label for="aadhaar_number">Aadhaar Number<span style="color:red">*</span></label>
                                        <span class="text-danger" id="aadhaar_number-error"></span> <!-- Error span -->
                                    </div>
                                </div>

                                <!-- PAN Number -->
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="pan_number" name="pan_number"
                                            placeholder="PAN Number" maxlength="10" style="text-transform: uppercase;" />
                                        <label for="pan_number">PAN Number<span style="color:red">*</span></label>
                                        <span class="text-danger" id="pan_number-error"></span> <!-- Error span -->
                                    </div>
                                </div>
                            </div>

                            <!-- Button Section -->
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
    <script type="text/javascript">
        $(document).ready(function() {
            loadPersonalInfoData();
            installInputFilters();
        });

        function installInputFilters() {
            $(".onlyNumber").inputFilter(value => /^\d*$/.test(value), "Must be an unsigned integer");
            $(".onlyLetter").inputFilter(value => /^[a-z]*$/i.test(value), "Must use alphabetic Latin characters");
        }

        function loadPersonalInfoData() {
            let url =
                `{{ config('apiConstants.PROFILE_URLS.PROFILE') }}?id={{ request()->get('id') }}&Params='Personal'`;
            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    populateDropdowns(response.data);
                    populateUserInfo(response.data);
                }
            });
        }

        function populateDropdowns(data) {
            populateDropdown("#marital_status_id", data.marital_statuses, "Select Marital Status");
            populateDropdown("#blood_group", data.blood_groups, "Select Blood Group");
        }

        function populateDropdown(selector, items, defaultText) {
            const dropdown = $(selector);
            dropdown.empty().append(`<option value="0">${defaultText}</option>`);
            items.forEach(item => {
                dropdown.append(`<option value="${item.id}">${item.name}</option>`);
            });
        }

        function populateUserInfo(data) {
            if (data.employeeInfo) {
                const {
                    employeeInfo,
                    user
                } = data;
                const imagePath = employeeInfo.profile_image ? "{{ asset('storage/profile_images') }}" + '/' +
                    employeeInfo
                    .profile_image : '';

                $("#uploadedAvatar").attr('src', imagePath);
                $("#profile_upload").html(employeeInfo.profile_image);
                $("#date_of_birth").val(employeeInfo.date_of_birth);
                $("#gender").val(employeeInfo.gender);
                $("#marital_status_id").val(employeeInfo.marital_status_id);
                $("#religion").val(employeeInfo.religion);
                $("#blood_group").val(employeeInfo.blood_group);
                $("#citizenship").val(employeeInfo.citizenship);
                $("#disability_status").val(employeeInfo.disability_status);
                $("#personal_email").val(employeeInfo.personal_email);
                $("#phone_number").val(employeeInfo.phone_number);
                $("#alternate_phone_number").val(employeeInfo.alternate_phone_number);
                $("#emergency_phone_number").val(employeeInfo.emergency_phone_number);
                $("#place_of_birth").val(employeeInfo.place_of_birth);
                $("#aadhaar_number").val(employeeInfo.aadhaar_number);
                $("#pan_number").val(employeeInfo.pan_number);
            }
            $("#firstName").val(data.user.first_name);
            $("#middleName").val(data.user.middle_name);
            $("#lastName").val(data.user.last_name);
        }

        $(document).ready(function() {
            // Initialize form validation on the form
            $("#commonform").validate({
                rules: {
                    date_of_birth: {
                        required: true,
                        date: true
                    },
                    place_of_birth: {
                        required: true,
                        maxlength: 100
                    },
                    gender: {
                        required: true
                    },
                    marital_status_id: {
                        required: true
                    },
                    nationality: {
                        required: true
                    },
                    citizenship: {
                        required: false,
                        maxlength: 15
                    },
                    disability_status: {
                        required: true
                    },
                    personal_email: {
                        required: true,
                        email: true
                    },
                    phone_number: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    alternate_phone_number: {
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    emergency_phone_number: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    religion: {
                        maxlength: 50
                    },
                    aadhaar_number: {
                        required: true,
                        digits: true,
                        maxlength: 12
                    },
                    pan_number: {
                        required: true,
                        maxlength: 10,
                        pattern: /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/
                    }
                },
                messages: {
                    date_of_birth: {
                        required: "Date of birth is required.",
                        date: "Please enter a valid date."
                    },
                    place_of_birth: {
                        required: "Place of birth is required.",
                        maxlength: "Place of birth cannot exceed 100 characters."
                    },
                    gender: {
                        required: "Gender is required."
                    },
                    marital_status_id: {
                        required: "Marital status is required."
                    },
                    nationality: {
                        required: "Nationality is required."
                    },
                    citizenship: {
                        required: "Citizenship is required.",
                        maxlength: "Citizenship cannot exceed 15 characters."
                    },
                    disability_status: {
                        required: "Disability status is required."
                    },
                    personal_email: {
                        required: "Email is required.",
                        email: "Please enter a valid email address."
                    },
                    phone_number: {
                        required: "Phone number is required.",
                        digits: "Only digits are allowed.",
                        minlength: "Phone number should be at least 10 digits.",
                        maxlength: "Phone number cannot exceed 15 digits."
                    },
                    alternate_phone_number: {
                        digits: "Only digits are allowed.",
                        minlength: "Alternate phone number should be at least 10 digits.",
                        maxlength: "Alternate phone number cannot exceed 15 digits."
                    },
                    emergency_phone_number: {
                        required: "Emergency phone number is required.",
                        digits: "Only digits are allowed.",
                        minlength: "Emergency phone number should be at least 10 digits.",
                        maxlength: "Emergency phone number cannot exceed 15 digits."
                    },
                    religion: {
                        maxlength: "Religion cannot exceed 50 characters."
                    },
                    aadhaar_number: {
                        required: "Aadhaar number is required.",
                        digits: "Only digits are allowed.",
                        maxlength: "Aadhaar number cannot exceed 12 digits."
                    },
                    pan_number: {
                        required: "PAN number is required.",
                        maxlength: "PAN number cannot exceed 10 characters.",
                        pattern: "PAN number must be in the format ABCDE1234F."
                    }
                },
                errorPlacement: function(error, element) {
                    var errorId = element.attr("id") + "-error";
                    $("#" + errorId).text(error.text()).show();
                    element.addClass("is-invalid");
                },
                success: function(label, element) {
                    var errorId = $(element).attr("id") + "-error";
                    $("#" + errorId).text("");
                    $(element).removeClass("is-invalid");
                },
                submitHandler: function(form) {
                    event.preventDefault();

                    const postData = collectFormData();
                    const url = `{{ config('apiConstants.PROFILE_URLS.PROFILE') }}`;

                    fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                        if (response.status === 200) {
                            ShowMsg("bg-success", response.message);
                        } else {
                            ShowMsg("bg-warning", 'The record could not be processed.');
                        }
                    });
                }
            });
        });

        function collectFormData() {
            return {
                user_uuid: $("#user_uuid").val(),
                date_of_birth: $("#date_of_birth").val(),
                place_of_birth: $("#place_of_birth").val(),
                gender: $("#gender").val(),
                marital_status_id: $("#marital_status_id").val(),
                citizenship: $("#citizenship").val(),
                disability_status: $("#disability_status").val(),
                personal_email: $("#personal_email").val(),
                phone_number: $("#phone_number").val(),
                alternate_phone_number: $("#alternate_phone_number").val(),
                emergency_phone_number: $("#emergency_phone_number").val(),
                religion: $("#religion").val(),
                blood_group: $("#blood_group").val(),
                aadhaar_number: $("#aadhaar_number").val(),
                pan_number: $("#pan_number").val()
            };
        }
    </script>
@endsection
