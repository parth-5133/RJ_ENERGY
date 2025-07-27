@php
    $id = request()->query('id');
    $controller = new App\Http\Controllers\Web\ProfileController();
    $profileHeader = $controller->profileHeader($id);
@endphp
@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6><span class="text-muted fw-light">Account Settings /</span> Job Information</h6>
        {{ $profileHeader }}
        <div class="row">
            <div class="col-md-12">
                @include('profile.nav-tabs')
                <div class="card mb-4">
                    <h5 class="card-header mb-4">Job Details</h5>
                    <div class="card-body">
                        <form id="commonform" name="commonform" class="form-horizontal" method="POST">
                            <input type="hidden" id="user_uuid" name="user_uuid" value="{{ request()->get('id') }}" />
                            <div class="row gy-4">
                                <!-- Job Title -->
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="job_title" name="job_title"
                                            placeholder="Job Title">
                                        <label for="job_title">Job Title <span style="color:red">*</span></label>
                                        <span class="text-danger" id="job_title-error"></span>
                                    </div>
                                </div>
                                <!-- Date of Joining -->
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="date" class="form-control" id="date_of_joining"
                                            name="date_of_joining" max="{{ date('Y-m-d') }}" placeholder="Date of Joining">
                                        <label for="date_of_joining">Date of Joining <span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="date_of_joining-error"></span>
                                    </div>
                                </div>
                                <!-- Department -->
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="department" name="department"
                                            aria-label="Department">
                                            <!-- Options will be populated by JavaScript -->
                                        </select>
                                        <label for="department">Department <span style="color:red">*</span></label>
                                        <span class="text-danger" id="department-error"></span>
                                    </div>
                                </div>
                                <!-- Location -->
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="location" name="location"
                                            placeholder="Location">
                                        <label for="location">Location <span style="color:red">*</span></label>
                                        <span class="text-danger" id="location-error"></span>
                                    </div>
                                </div>
                                <!-- Employee Type -->
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="employee_type" name="employee_type"
                                            aria-label="Employee Type">
                                            <!-- Options will be populated by JavaScript -->
                                        </select>
                                        <label for="employee_type">Employee Type <span style="color:red">*</span></label>
                                        <span class="text-danger" id="employee_type-error"></span>
                                    </div>
                                </div>
                                <!-- Employee Status -->
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="employee_status" name="employee_status"
                                            aria-label="Employee Status">
                                            <!-- Options will be populated by JavaScript -->
                                        </select>
                                        <label for="employee_status">Employee Status <span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="employee_status-error"></span>
                                    </div>
                                </div>
                                <!-- Supervisor/Manager -->
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="reporting_id" name="reporting_id"
                                            aria-label="Select Reporting Manager">
                                            <!-- Options will be populated by JavaScript -->
                                        </select>
                                        <label for="reporting_id">Reporting Manager <span style="color:red">*</span></label>
                                        <span class="text-danger" id="reporting_id-error"></span>
                                    </div>
                                </div>
                                <!-- Employee Grade/Level -->
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="designation" name="designation"
                                            aria-label="Employee Grade/Level">
                                            <!-- Options will be populated by JavaScript -->
                                        </select>
                                        <label for="designation">Designation<span style="color:red">*</span></label>
                                        <span class="text-danger" id="designation-error"></span>
                                    </div>
                                </div>
                                <!-- Work Schedule -->
                                <!-- Work Schedule -->
                                <div class="col-md-4">
                                    <div class="form-floating form-floating-outline">
                                        <select class="form-select" id="work_schedule" name="work_schedule">
                                        </select>
                                        <label for="work_schedule">Work Schedule <span style="color:red">*</span></label>
                                        <span class="text-danger" id="work_schedule-error"></span>
                                    </div>
                                </div>

                                <!-- Job Description -->
                                <div class="col-md-12">
                                    <div class="form-floating form-floating-outline">
                                        <textarea class="form-control h-px-100" id="job_description" name="job_description" placeholder="Job Description"
                                            rows="3"></textarea>
                                        <label for="job_description">Job Description</label>
                                        <span class="text-danger" id="job_description-error"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center p-3 mt-5">
                                <button class="btn rounded-pill btn-outline-secondary me-2" type="button"
                                    data-bs-dismiss="offcanvas" onclick="window.history.back();">
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
    <script type="text/javascript">
        $(document).ready(function() {
            loadJobInfoData();
        });

        function loadJobInfoData() {
            let url =
                `{{ config('apiConstants.PROFILE_URLS.PROFILE') }}?id={{ request()->get('id') }}&Params='Job'`;
            fnCallAjaxHttpGetEvent(url, null, true, true, function(
                response) {
                const employeeJobData = response.data.employeeJob;
                // Call the populateDropdowns function and pass the response data
                populateDropdowns(response.data);
                setFormData(employeeJobData);
            });
        }
        // Function to populate dropdowns
        function populateDropdowns(data) {
            // Clear existing options for employee status, type, and department
            $('#employee_status').empty();
            $('#employee_type').empty();
            $('#department').empty();
            $('#reporting_id').empty();
            $('#designation').empty();
            $('#work_schedule').empty();

            // Add default "Select" options
            $('#employee_status').append(`<option value="">Select Status</option>`);
            $('#employee_type').append(`<option value="">Select Type</option>`);
            $('#department').append(`<option value="">Select Department</option>`);
            $('#reporting_id').append(`<option value="">Select Reporting Manager</option>`);
            $('#designation').append(`<option value="">Select Designation</option>`);
            $('#work_schedule').append(`<option value="">Select Work Schedule</option>`);

            // Set Employee Status
            data.employeeShift.forEach(function(Schedule) {
                $('#work_schedule').append(
                    `<option value="${Schedule.id}">${Schedule.shift_name} , ${Schedule.from_time} - ${Schedule.to_time}</option>`
                );
            });

            // Set Employee Status
            data.employeeStatus.forEach(function(status) {
                $('#employee_status').append(
                    `<option value="${status.id}">${status.name}</option>`
                );
            });

            // Set Employee Type
            data.employeeType.forEach(function(type) {
                $('#employee_type').append(
                    `<option value="${type.id}">${type.name}</option>`
                );
            });

            // Set Department
            data.employeeDepartment.forEach(function(department) {
                $('#department').append(
                    `<option value="${department.id}">${department.name}</option>`
                );
            });


            data.allUser.forEach(function(user) {
                $('#reporting_id').append(
                    `<option value="${user.id}">${user.full_name}</option>`
                );
            });
            data.employeeDesignation.forEach(function(designation) {
                $('#designation').append(
                    `<option value="${designation.id}">${designation.name}</option>`
                );
            });
        }

        function setFormData(employeeJobData) {
            if (employeeJobData) {
                $('#job_title').val(employeeJobData.job_title);
                $('#department').val(employeeJobData.department);
                $('#location').val(employeeJobData.location);
                $('#employee_type').val(employeeJobData.employee_type);
                $('#date_of_joining').val(employeeJobData.date_of_joining);
                $('#employee_status').val(employeeJobData.employee_status);
                $('#reporting_id').val(employeeJobData.reporting_id);
                $('#designation').val(employeeJobData.designation);
                $('#work_schedule').val(employeeJobData.work_schedule);
                $('#job_description').val(employeeJobData.job_description);
            }
        }
        $(document).ready(function() {
            $("#commonform").validate({
                rules: {
                    user_uuid: {
                        required: true
                    },
                    job_title: {
                        required: true,
                        maxlength: 100
                    },
                    department: {
                        required: true
                    },
                    location: {
                        required: true
                    },
                    employee_type: {
                        required: true
                    },
                    date_of_joining: {
                        required: true,
                        date: true
                    },
                    employee_status: {
                        required: true
                    },
                    reporting_id: {
                        required: true
                    },
                    designation: {
                        required: true,
                    },
                    work_schedule: {
                        required: true
                    },
                    job_description: {
                        required: true,
                        maxlength: 500
                    }
                },
                messages: {
                    user_uuid: {
                        required: "User UUID is required."
                    },
                    job_title: {
                        required: "Job title is required.",
                        maxlength: "Job title cannot exceed 100 characters."
                    },
                    department: {
                        required: "Department is required."
                    },
                    location: {
                        required: "Location is required."
                    },
                    employee_type: {
                        required: "Employee type is required."
                    },
                    date_of_joining: {
                        required: "Date of joining is required.",
                        date: "Please enter a valid date."
                    },
                    employee_status: {
                        required: "Employee status is required."
                    },
                    reporting_id: {
                        required: "Reporting ID is required."
                    },
                    designation: {
                        required: "Employee Designation is required.",
                    },
                    work_schedule: {
                        required: "Work schedule is required."
                    },
                    job_description: {
                        required: "Job description is required.",
                        maxlength: "Job description cannot exceed 500 characters."
                    }
                },
                errorPlacement: function(error, element) {
                    var errorId = element.attr("id") + "-error";
                    $("#" + errorId).text(error.text());
                    $("#" + errorId).show();
                    element.addClass("is-invalid");
                },
                success: function(label, element) {
                    var errorId = $(element).attr("id") + "-error";
                    $("#" + errorId).text("");
                    $(element).removeClass("is-invalid");
                },
                submitHandler: function(form) {
                    // Prevent default form submission
                    event.preventDefault();

                    const postData = collectFormData();
                    const Url =
                        `{{ config('apiConstants.PROFILE_URLS.JOB') }}/{{ request()->get('id') }}`;

                    fnCallAjaxHttpPostEvent(Url, postData, true, true, function(response) {
                        if (response.status === 200) {
                            ShowMsg("bg-success", response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        } else {
                            ShowMsg("bg-warning", 'The record could not be processed.');
                        }
                    });
                }
            });

            function collectFormData() {
                return {
                    user_uuid: $("#user_uuid").val(),
                    job_title: $("#job_title").val(),
                    department: $("#department").val(),
                    location: $("#location").val(),
                    employee_type: $("#employee_type").val(),
                    date_of_joining: $("#date_of_joining").val(),
                    employee_status: $("#employee_status").val(),
                    reporting_id: $("#reporting_id").val(),
                    designation: $("#designation").val(),
                    work_schedule: $("#work_schedule").val(),
                    job_description: $("#job_description").val()
                };
            }
        });
    </script>
@endsection
