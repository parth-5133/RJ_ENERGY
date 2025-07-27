@php
    $id = request()->query('id');
    $controller = new App\Http\Controllers\Web\ProfileController();
    $profileHeader = $controller->profileHeader($id);
@endphp
@extends('layouts.layout')
@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6><span class="text-muted fw-light">Account Settings /</span> Experience Information</h6>
        {{ $profileHeader }}
        <div class="row">
            <div class="col-md-12">
                @include('profile.nav-tabs')
                <div class="card mb-4">
                    <h5 class="card-header mb-4">Experience Details</h5>
                    <div class="card-body">
                        <form id="commonform" name="commonform" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="user_uuid" name="user_uuid" value="{{ request()->get('id') }}" />
                            <div class="row gy-4">
                                <input type="hidden" id="id" name="id" />
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="organization_name"
                                            name="organization_name" placeholder="Organization Name" />
                                        <label for="organization_name">Organization Name <span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="organization_name-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="date" id="from_date" name="from_date"
                                            max="{{ date('Y-m-d') }}" placeholder="From Date" />
                                        <label for="from_date">From Date <span style="color:red">*</span></label>
                                        <span class="text-danger" id="from_date-error"></span>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="date" id="to_date" name="to_date"
                                            max="{{ date('Y-m-d') }}" placeholder="To Date" />
                                        <label for="to_date">To Date <span style="color:red">*</span></label>
                                        <span class="text-danger" id="to_date-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="designation" name="designation"
                                            placeholder="Designation" />
                                        <label for="designation">Designation <span style="color:red">*</span></label>
                                        <span class="text-danger" id="designation-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="country" name="country"
                                            onchange="populateStateDropdown(this.value, '#state')">
                                            <option value="">Select Country</option>
                                        </select>
                                        <label for="PerAdd_country">Country <span style="color:red">*</span></label>
                                        <span class="text-danger" id="country-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="state" name="state">
                                            <option value="">Select State</option>
                                        </select>
                                        <label for="state">State <span style="color:red">*</span></label>
                                        <span class="text-danger" id="state-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="city" name="city"
                                            placeholder="City" />
                                        <label for="city">City <span style="color:red">*</span></label>
                                        <span class="text-danger" id="city-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="file" id="experience_letter"
                                            name="experience_letter" />
                                        <label for="experience_letter">Experience Letter</label>
                                        <span class="text-danger" id="experience_letter-error"></span>
                                        <a href="#" id="experience_letter_filename" target="_blank"
                                            class="form-text"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center p-3">
                                <button class="btn rounded-pill btn-outline-secondary me-2" type="button"
                                    id="closeFormButton" onclick="window.history.back();">
                                    <span class="tf-icons mdi mdi-cancel me-1"></span>Cancel
                                </button>
                                <button type="submit" class="btn rounded-pill btn-primary waves-effect waves-light">
                                    <span class="tf-icons mdi mdi-checkbox-marked-circle-outline me-1"></span>Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap Table with Header - Light -->
        <div class="card">
            <div class="table-responsive text-nowrap">
                <table id="grid" class="table">
                    <thead class="table-light">
                        <tr>
                            <th>Organization Name</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Designation</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Experience Letter</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var allStates = [];

        $(document).ready(function() {

            // Event listener for the Cancel button
            $('#closeFormButton').click(function() {
                $('#commonform input, #commonform textarea, #commonform select').each(function() {
                    $(this).val('').trigger('change');
                });
                $('#experience_letter_filename').attr('href', '#').text('');
            });

            loadExperienceData();
            loadcountryData();
        });

        function loadExperienceData() {
            const userId = "{{ $id }}";
            const Url = `{{ config('apiConstants.PROFILE_URLS.EXPERIENCE_LIST') }}/${userId}`;
            fnCallAjaxHttpGetEvent(Url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    let tbody = $('#grid tbody');
                    tbody.empty();
                    response.data.forEach(function(item) {
                        let fileLink = item.experience_letter ?
                            `<a href="{{ url('/storage/') }}/${item.experience_letter}" target="_blank">${item.file_display_name}</a>` :
                            `${item.file_display_name}`;
                        let row = `
                        <tr>
                            <td>${item.organization_name}</td>
                            <td>${item.from_date}</td>
                            <td>${item.to_date}</td>
                            <td>${item.designation}</td>
                            <td>${item.country_name}</td>
                            <td>${item.state_name}</td>
                            <td>${item.city}</td>
                             <td>${fileLink}</td>
                            <td>
                                <ul class="list-inline m-0">
                                    <li class="list-inline-item">
                                       <button class="btn btn-sm btn-text-primary rounded btn-icon item-edit waves-effect waves-light edit-button" data-id="${item.id}"><i class='mdi mdi-pencil-outline'></i></button>
                                    </li>
                                    <li class="list-inline-item">
                                       ${GetEditDeleteButton('True', `fnShowConfirmDeleteDialog('${item.organization_name}', fnDeleteRecord, ${item.id}, '{{ config('apiConstants.PROFILE_URLS.EXPERIENCE_DELETE') }}', '#grid')`, "Delete")}
                                    </li>
                                </ul>
                            </td>
                        </tr>`;
                        tbody.append(row);
                    });
                    bindEditButtons();
                } else {
                    console.log('Failed to retrieve data.');
                }
            });
        }

        function loadcountryData() {
            fnCallAjaxHttpGetEvent("{{ config('apiConstants.PROFILE_URLS.PROFILE') }}?id={{ request()->get('id') }}",
                null,
                true, true,
                function(
                    response) {
                    if (response.status === 200 && response.data) {
                        populateDropdown("#country", response.data.nationalities, "Select Country");
                        allStates = response.data.state;
                    } else {
                        console.error('Failed to retrieve data.');
                    }
                });
        }

        function fetchExperienceData(Id) {
            fnCallAjaxHttpGetEvent(`{{ config('apiConstants.PROFILE_URLS.EXPERIENCE_VIEW') }}/${Id}`, null, true, true,
                function(
                    response) {
                    if (response.status === 200 && response.data) {
                        const experienceData = response.data.employeeExperience;
                        setExperienceFormData(experienceData);
                        populateDropdown("#country", response.data.nationalities, "Select Country", experienceData
                            .country);
                        allStates = response.data.state;
                        populateStateDropdown(experienceData.country, '#state', experienceData.state);
                    }
                });
        }

        function bindEditButtons() {
            $('.edit-button').on('click', function() {
                const id = $(this).data('id');
                fetchExperienceData(id);
            });
        }

        function setExperienceFormData(experienceData) {

            $('#id').val(experienceData.id);
            $('#organization_name').val(experienceData.organization_name);
            $('#from_date').val(experienceData.from_date);
            $('#to_date').val(experienceData.to_date);
            $('#designation').val(experienceData.designation);
            $('#city').val(experienceData.city);

            const fileName = experienceData.file_display_name;
            const filePath = experienceData.experience_letter;
            const fileLink = "{{ url('/storage/') }}/" + filePath;
            $("#experience_letter_filename").text(fileName).attr('href', fileLink);
        }

        function populateDropdown(selector, data, defaultOption, selectedId = null) {
            const dropdown = $(selector);
            dropdown.empty();
            dropdown.append(`<option value="">${defaultOption}</option>`);
            data.forEach(function(item) {
                const isSelected = item.id == selectedId ? 'selected' : '';
                dropdown.append(`<option value="${item.id}" ${isSelected}>${item.name}</option>`);
            });
        }

        function populateStateDropdown(countryId, stateDropdownSelector, selectedStateId = null) {
            const stateDropdown = $(stateDropdownSelector);
            stateDropdown.empty();
            stateDropdown.append('<option value="">Select State</option>');
            if (countryId) {
                const filteredStates = allStates.filter(state => state.country_id == countryId);
                filteredStates.forEach(function(state) {
                    const isSelected = state.id == selectedStateId ? 'selected' : '';
                    stateDropdown.append(`<option value="${state.id}" ${isSelected}>${state.name}</option>`);
                });
            }
        }

        $(document).ready(function() {
            $("#commonform").validate({
                rules: {
                    organization_name: {
                        required: true,
                        maxlength: 35,
                    },
                    from_date: {
                        required: true,
                        date: true,
                    },
                    to_date: {
                        required: true,
                        date: true,
                        greaterThan: "#from_date"
                    },
                    designation: {
                        required: true,
                        maxlength: 20,
                    },
                    country: {
                        required: true,
                    },
                    state: {
                        required: true,
                    },
                    city: {
                        required: true,
                        maxlength: 20,
                    },
                    experience_letter: {
                        extension: "pdf|doc|docx",
                    }
                },
                messages: {
                    organization_name: {
                        required: "Organization name is required",
                        maxlength: "Organization name cannot exceed 35 characters",
                    },
                    from_date: {
                        required: "From date is required",
                        date: "Please enter a valid date",
                    },
                    to_date: {
                        required: "To date is required",
                        date: "Please enter a valid date",
                        greaterThan: "To date must be more than from date"
                    },
                    designation: {
                        required: "Designation is required",
                        maxlength: "Designation cannot exceed 20 characters",
                    },
                    country: {
                        required: "Country is required",
                    },
                    state: {
                        required: "State is required",
                    },
                    city: {
                        required: "City is required",
                        maxlength: "City cannot exceed 20 characters",
                    },
                    experience_letter: {
                        extension: "Only PDF, DOC, and DOCX files are allowed",
                        filesize: "The file size must be less than 10MB",
                    }
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
                    var formData = new FormData(form);
                    var url =
                        `{{ config('apiConstants.PROFILE_URLS.EXPERIENCE') }}/{{ request()->get('id') }}`;

                    fnCallAjaxHttpPostEventWithoutJSON(url, formData, true, true, function(response) {
                        location.reload();
                        const messageClass = response.status === 200 ? "bg-success" :
                            "bg-warning";
                        const messageText = response.status === 200 ? response.message :
                            'The record could not be processed.';
                        ShowMsg(messageClass, messageText);
                    });
                }
            });
            $.validator.addMethod("greaterThan", function(value, element, param) {
                var startDate = $(param).val();
                if (startDate === "") {
                    return true;
                }
                return new Date(value) > new Date(startDate);
            }, "To date must be greater than From date");
        });
    </script>
@endsection
