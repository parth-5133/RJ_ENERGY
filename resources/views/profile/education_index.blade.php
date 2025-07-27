@php
    $id = request()->query('id');
    $controller = new App\Http\Controllers\Web\ProfileController();
    $profileHeader = $controller->profileHeader($id);
@endphp
@extends('layouts.layout')
@section('content')
    <!-- Content -->
    <div class="container-fluid flex-grow-1 container-p-y">
        <h6><span class="text-muted fw-light">Account Settings /</span> Education and Qualifications</h6>
        {{ $profileHeader }}
        <div class="row">
            <div class="col-md-12">
                @include('profile.nav-tabs')
                <div class="card mb-4">
                    <h5 class="card-header mb-4">Education Details</h5>
                    <div class="card-body">
                        <form id="commonform" name="commonform" method="POST">
                            <input type="hidden" id="user_uuid" name="user_uuid" value="{{ request()->get('id') }}" />
                            <div class="row gy-4">
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="highest_degree" name="highest_degree"
                                            placeholder="Highest Degree/Qualification" />
                                        <label for="highest_degree">Highest Degree/Qualification <span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="highest_degree-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="institution_name"
                                            name="institution_name" placeholder="Institution Name" />
                                        <label for="institution_name">Institution Name <span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="institution_name-error"></span>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="field_of_study" name="field_of_study"
                                            placeholder="Field of Study" />
                                        <label for="field_of_study">Field of Study <span style="color:red">*</span></label>
                                        <span class="text-danger" id="field_of_study-error"></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="year_of_graduation"
                                            name="year_of_graduation" placeholder="Year of Graduation" />
                                        <label for="year_of_graduation">Year of Graduation <span
                                                style="color:red">*</span></label>
                                        <span class="text-danger" id="year_of_graduation-error"></span>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="certifications" name="certifications"
                                            placeholder="Certifications" />
                                        <label for="certifications">Certifications</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating form-floating-outline">
                                        <input class="form-control" type="text" id="skills" name="skills"
                                            placeholder="Skills" />
                                        <label for="skills">Skills</label>
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
    <script type="text/javascript">
        $(document).ready(function() {
            loadEducationData();
        });

        function loadEducationData() {

            let url =
                `{{ config('apiConstants.PROFILE_URLS.PROFILE') }}?id={{ request()->get('id') }}&Params='Education'`;
            fnCallAjaxHttpGetEvent(url, null, true, true,
                function(response) {
                    if (response.status === 200 && response.data.employeeEducation) {
                        populateFormFields(response.data.employeeEducation);
                        // Populate form fields with user data
                    }
                });
        }

        function populateFormFields(employeeEducation) {
            if (employeeEducation) {
                $("#highest_degree").val(employeeEducation.highest_degree);
                $("#institution_name").val(employeeEducation.institution_name);
                $("#field_of_study").val(employeeEducation.field_of_study);
                $("#year_of_graduation").val(employeeEducation.year_of_graduation);
                $("#certifications").val(employeeEducation.certifications);
                $("#skills").val(employeeEducation.skills);
            }
        }

        $("#commonform").validate({
            rules: {
                highest_degree: {
                    required: true,
                    maxlength: 100,
                },
                institution_name: {
                    required: true,
                    maxlength: 150,
                },
                field_of_study: {
                    required: true,
                    maxlength: 100,
                },
                year_of_graduation: {
                    required: true,
                    digits: true,
                    minlength: 4,
                    maxlength: 4, // Validating for a 4-digit year
                },
                certifications: {
                    maxlength: 500, // Optional, adjust based on requirements
                },
                skills: {
                    maxlength: 500, // Optional, adjust based on requirements
                }
            },
            messages: {
                highest_degree: {
                    required: "Highest degree is required",
                    maxlength: "Highest degree cannot be more than 100 characters",
                },
                institution_name: {
                    required: "Institution name is required",
                    maxlength: "Institution name cannot be more than 150 characters",
                },
                field_of_study: {
                    required: "Field of study is required",
                    maxlength: "Field of study cannot be more than 100 characters",
                },
                year_of_graduation: {
                    required: "Year of graduation is required",
                    digits: "Year of graduation must be numeric",
                    minlength: "Year of graduation must be 4 digits",
                    maxlength: "Year of graduation must be 4 digits",
                },
                certifications: {
                    maxlength: "Certifications field cannot be more than 500 characters",
                },
                skills: {
                    maxlength: "Skills field cannot be more than 500 characters",
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
                // Prevent default form submission
                event.preventDefault();

                const postData = collectFormData();
                const Url = `{{ config('apiConstants.PROFILE_URLS.EDUCATION') }}/{{ request()->get('id') }}`;

                fnCallAjaxHttpPostEvent(Url, postData, true, true, function(response) {
                    if (response.status === 200) {
                        ShowMsg("bg-success", response.message);
                    } else {
                        ShowMsg("bg-warning", 'The record could not be processed.');
                    }
                });
            }
        });

        // Function to collect form data
        function collectFormData() {
            return {
                user_uuid: $("#user_uuid").val(),
                highest_degree: $("#highest_degree").val(),
                institution_name: $("#institution_name").val(),
                field_of_study: $("#field_of_study").val(),
                year_of_graduation: $("#year_of_graduation").val(),
                certifications: $("#certifications").val(),
                skills: $("#skills").val(),
            };
        }
    </script>
@endsection
