@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row gy-5 gx-5">
            <div class="col-12 col-md-12 col-lg-6 col-xxl-4">
                <div class="card">
                    <div
                        class="card-body position-relative d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar">
                                <img src="{{ isset($profile_img) && $profile_img ? asset('storage/profile_images/' . $profile_img) : asset('assets/img/avatars/1.png') }}"
                                    alt="User Image" class="w-px-40 h-auto rounded-circle" />
                            </div>
                            <h5 class="card-title mb-0 d-flex flex-column flex-sm-row align-items-sm-center"> Welcome Back,
                                <span class="text-primary ms-1">{{ $name ?? '' }}</span>
                            </h5>
                        </div>
                        <p class="mb-2 mt-2">
                        </p>
                        <a class="btn btn-sm btn-primary rounded text-white myProfileLink">View Profile</a>
                    </div>
                </div>

                <div class="row gy-5 gx-5">
                    <div class="col-12 col-md-12 col-lg-12
                     col-xxl-12">
                        <div class="card mb-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Upcoming Holidays</h5>
                            </div>
                            <div
                                class="card-body position-relative overflow-hidden d-flex flex-column justify-content-between">
                                <div class="row justify-content-between mb-4">
                                    <div class="overflow-auto position-relative" style="height: 173px;">
                                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-2 row-cols-xxl-1"
                                            id="holidayContainer">
                                        </div>
                                        <!-- Show when no holiday data is found -->
                                        <div class="d-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100"
                                            style="display: none;">
                                            <img src="../assets/img/illustrations/notfouund.svg" class="img-fluid"
                                                alt="img" id="holidayNotFound">
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('holidays') }}" class="btn btn-sm btn-primary mx-auto">
                                    View All
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-6 col-xxl-4">
                <div class="card punch-status mb-0 h-100">
                    <div class="card-header d-flex flex-column flex-sm-row align-items-center justify-content-between">
                        <h5 class="card-title m-0">Timesheet</h5>
                        <p class="text-primary mb-0 text-center text-sm-end">
                            Check In at <br> <span class="punch-details d-inline-block"></span>
                        </p>
                    </div>

                    <div id="punchCardSection">
                        <div class="punch-info pb-0 text-center">
                            <div class="punch-hours mb-4">
                                <span class="text-black"><b>0.00 hrs</b> <br> Total</span>
                            </div>
                            <div class="punch-animation">
                                <span style="--step:0;"></span>
                                <span style="--step:1;"></span>
                                <span style="--step:2;"></span>
                                <span style="--step:3;"></span>
                                <span style="--step:4;"></span>
                                <span style="--step:5;"></span>
                                <span style="--step:6;"></span>
                                <span style="--step:7;"></span>
                                <span style="--step:8;"></span>
                                <span style="--step:9;"></span>
                                <span style="--step:10;"></span>
                                <span style="--step:11;"></span>
                                <span style="--step:12;"></span>
                                <span style="--step:13;"></span>
                                <span style="--step:14;"></span>
                                <span style="--step:15;"></span>
                                <span style="--step:16;"></span>
                                <span style="--step:17;"></span>
                                <span style="--step:18;"></span>
                                <span style="--step:19;"></span>
                                <span style="--step:20;"></span>
                                <span style="--step:21;"></span>
                                <span style="--step:22;"></span>
                                <span style="--step:23;"></span>
                                <span style="--step:24;"></span>
                                <span style="--step:25;"></span>
                                <span style="--step:26;"></span>
                                <span style="--step:27;"></span>
                                <span style="--step:28;"></span>
                                <span style="--step:29;"></span>
                                <span style="--step:30;"></span>
                                <span style="--step:31;"></span>
                                <span style="--step:32;"></span>
                                <span style="--step:33;"></span>
                                <span style="--step:34;"></span>
                                <span style="--step:35;"></span>
                                <span style="--step:36;"></span>
                                <span style="--step:37;"></span>
                                <span style="--step:38;"></span>
                                <span style="--step:39;"></span>
                                <span style="--step:40;"></span>
                                <span style="--step:41;"></span>
                                <span style="--step:42;"></span>
                                <span style="--step:43;"></span>
                                <span style="--step:44;"></span>
                                <span style="--step:45;"></span>
                                <span style="--step:46;"></span>
                                <span style="--step:47;"></span>
                                <span style="--step:48;"></span>
                                <span style="--step:49;"></span>
                            </div>
                            <div
                                class="d-flex flex-column flex-sm-row justify-content-between align-items-center text-center text-md-center mb-4">
                                <div class="mb-sm-0 mb-4 col-sm-4 col-12">
                                    <button type="button" class="btn btn-primary punch-btn" id="BtncheckIn">
                                        Check In
                                    </button>
                                    <button type="button" class="btn btn-primary punch-btn d-none" id="BtncheckOut">
                                        Check Out
                                    </button>
                                </div>
                                <div class="mb-sm-0 mb-4 clock-in-content text-center col-sm-4 col-12">
                                    <h5 class="mb-0">Work Time</h5>
                                    <p id="Work-hours" class="mb-0">00 Hrs : 00 Min</p>
                                </div>
                                <div class="mb-sm-0 mb-2 col-sm-4 col-12">
                                    <button type="button" class="btn btn-alter-primary punch-btn d-none"
                                        id="BtnbreakIn">
                                        Break In
                                    </button>
                                    <button type="button" class="btn btn-alter-primary punch-btn d-none"
                                        id="BtnbreakOut">
                                        Break Out
                                    </button>
                                </div>
                            </div>
                            <div class="statistics">
                                <ul
                                    class="list-unstyled d-flex align-items-center justify-content-between flex-column flex-sm-row w-100 p-0 gap-4">
                                    <li class="border py-2 px-6 rounded w-100 w-sm-auto overflow-hidden">
                                        <h6 class="mb-1">Remaining</h6>
                                        <p class="mb-0" id="remaining-time">8 Hrs 30 Min</p>
                                    </li>
                                    <li class="border py-2 px-6 rounded w-100 w-sm-auto overflow-hidden">
                                        <h6 class="mb-1">Overtime</h6>
                                        <p class="mb-0">0 Hrs 00 Min</p>
                                    </li>
                                    <li class="border py-2 px-6 rounded w-100 w-sm-auto overflow-hidden">
                                        <h6 class="mb-1">Break</h6>
                                        <p class="mb-0" id="Break-hours">1 Hrs 20 Min</p>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="view-attendance border border-start-0 border-end-0 border-bottom-0">
                            <a href="{{ Route('Attendance.report') }}" class="btn btn-sm border-primary bg-label-primary">
                                View Attendance
                                <i class="mdi mdi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-12 col-xxl-4">
                <div class="row gy-5 gx-5">
                    <div class="col-12 col-md-6 col-lg-6 col-xxl-5">
                        <div class="card mb-0 h-100">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-0">Today Activity</h5>
                                </div>
                            </div>
                            <div class="card-body" style="height: 365px; overflow: auto;">
                                <ul class="timeline card-timeline mb-0">
                                    <!-- Timeline items will be dynamically injected here -->
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 col-xxl-7">
                        <div class="card h-100 mb-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Assignment Progress</h5>
                            </div>

                            <div class="card-body">
                                <ul class="p-0 m-0">
                                    <li class="d-block mb-8">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">Today</h6>
                                            <strong class="today-hours"></strong>
                                        </div>
                                        <div class="progress mt-1" style="height: 14px;">
                                            <div class="progress-bar progress-bar-striped today-progress-bar"
                                                role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </li>

                                    <li class="d-block mb-8">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">This Week</h6>
                                            <strong class="week-hours"></strong>
                                        </div>
                                        <div class="progress mt-1" style="height: 14px">
                                            <div class="progress-bar progress-bar-striped bg-success week-progress-bar"
                                                role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </li>

                                    <li class="d-block mb-8">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">This Month</h6>
                                            <strong class="month-hours"></strong>
                                        </div>
                                        <div class="progress mt-1" style="height: 14px">
                                            <div class="progress-bar progress-bar-striped bg-info month-progress-bar"
                                                role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </li>

                                    <li class="d-block">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6 class="mb-0">Remaining</h6>
                                            <strong class="remaining-hours"></strong>
                                        </div>
                                        <div class="progress mt-1" style="height: 14px">
                                            <div class="progress-bar bg-warning remaining-progress-bar" role="progressbar"
                                                style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-12 col-xxl-12">
                <div class="row gy-5 gx-5">
                    {{-- <div class="col-12 col-md-12 col-lg-6 col-xxl-5">
                        <div class="card overflow-hidden mb-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title mb-0">Working hours</h5>
                                <p class="text-primary mb-0">
                                    <span class="startOfWeek"></span>
                                    -
                                    <span class="endOfWeek"></span>
                                </p>
                            </div>
                            <div class="card-body pt-3">
                                <div class="wrapper">
                                    <canvas id="myChart4"></canvas>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-12 col-md-12 col-lg-6 col-xxl-4">
                        <div class="card mb-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Attendance &amp; Leaves</h5>
                                <div class="dropdown bg-white px-8 pe-4 py-2" style="border-radius: 8px;">
                                    <a class="dropdown-toggle" data-bs-toggle="dropdown">
                                        <span id="currentYear"></span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end mt-3 py-2" id="yearDropdown">
                                    </div>
                                </div>
                            </div>
                            <div class="dynamic-leaves-container card-body overflow-auto" style="height: 353px">
                                <!-- Dynamic row with leave boxes will be appended here -->
                            </div>
                            <div
                                class="view-attendance border border-start-0 border-end-0 border-bottom-0 d-flex align-items-center justify-content-between">
                                <a href="{{ Route('Leave') }}" class="btn btn-sm border-primary bg-label-primary">
                                    Apply Leave <i class="mdi mdi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-12 col-md-12 col-lg-6 col-xxl-3">
                        <div class="card mb-0 overflow-hidden h-100 text-center">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Employees</h5>
                                <a href="{{ url('/employees') }}" class="btn btn-sm border-primary bg-label-primary">
                                    View All <i class="mdi mdi-arrow-right"></i>
                                </a>
                            </div>
                            <div
                                class="card-body position-relative overflow-hidden h-100 d-flex flex-column justify-content-between">
                                <div class="row">
                                    <div class="overflow-auto" style="max-height: 390px; min-height: 240px;">
                                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-1 row-cols-xxl-1"
                                            id="birthdayContainer">
                                            @forelse ($employeesList as $data)
                                                <div class="mb-2">
                                                    <div class="d-flex align-items-center border-bottom pb-2 bg-white">
                                                        <div class="avatar me-2">
                                                            <img src="{{ isset($data['profile_image']) && $data['profile_image'] ? asset('storage/profile_images/' . $data['profile_image']) : asset('assets/img/avatars/1.png') }}"
                                                                alt="Avatar" class="rounded">
                                                        </div>
                                                        <div class="d-flex flex-column align-items-start">
                                                            <h6 class="mb-0">{{ $data['name'] }}</h6>
                                                            <small class="text-muted">{{ $data['department'] }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <!-- Data not found -->
                                                <div class="d-flex justify-content-center align-items-center w-100"
                                                    style="height: 300px">
                                                    <img src="../assets/img/illustrations/notfouund.svg" class="img-fluid"
                                                        alt="img">
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Check In Modal -->
    <div class="modal fade" id="checkInModal" tabindex="-1" role="dialog" aria-labelledby="checkInModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="box-shadow: none">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkInModalLabel">Check In Note</h5>
                </div>
                <div class="modal-body">
                    <form id="checkInForm">
                        <div class="form-floating form-floating-outline mb-2">
                            <textarea class="form-control h-px-100" id="checkInNote" name="checkInNote" rows="3" minlength="15" required
                                oninput="validateNote()"></textarea>
                            <label for="checkInNote">Note <span style="color:red">*</span></label>
                            <span class="text-danger" id="note-error"></span>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn rounded-pill btn-primary me-2">Submit</button>
                            <button type="button" class="btn rounded-pill btn-outline-gray waves-effect waves-light"
                                data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {

            $("#holidayNotFound").hide();

            const today = new Date();
            const startOfWeek = new Date(today.setDate(today.getDate() - (today.getDay() === 0 ? 6 : today
                .getDay() - 1)));
            const endOfWeek = new Date(startOfWeek.getTime() + 4 * 24 * 60 * 60 * 1000);
            const formatDate = (date) => date.toISOString().split('T')[0].split('-').reverse().join('/');

            $(".startOfWeek").text(formatDate(startOfWeek));
            $(".endOfWeek").text(formatDate(endOfWeek));

            showButtonsBasedOnLastAction();
            loadTodayActivity();
            loadProgressData();
            loadWorkinghoursData();
            loadUpcomingHolidays();

            const currentYear = new Date().getFullYear();
            $('#currentYear').text(currentYear);
            loadLeavesData(currentYear);
            const dropdown = $('#yearDropdown');
            for (let i = 0; i < 6; i++) {
                const year = currentYear - i;
                dropdown.append(`
            <a class="dropdown-item waves-effect ${year === currentYear ? 'active' : ''}"  onclick="setYear(${year})">
                <span class="align-middle">${year}</span>
            </a>
        `);
            }
        });

        function validateNote() {
            const noteField = document.getElementById('checkInNote');
            const errorField = document.getElementById('note-error');

            if (noteField.value.length < 15) {
                errorField.textContent = "Note must be at least 15 characters.";
            } else {
                errorField.textContent = "";
            }
        }

        function setYear(year) {
            $('#currentYear').text(year);
            loadLeavesData(year);
        }

        function setupButtonClick() {
            // Remove any existing click event listeners to prevent duplication
            $(document).off('click', '.punch-btn');

            // Attach the click event listener
            $(document).on('click', '.punch-btn', function() {
                const buttonId = $(this).attr('id');

                fnCallAjaxHttpGetEvent("{{ config('apiConstants.EMPLOYEE_DASHBOARD.LAST_CHECKOUT_RECORD') }}",
                    null,
                    true,
                    true,
                    function(response) {
                        if (response.status === 200 && response.data) {
                            $('#note-error').empty();

                            if (buttonId === 'BtncheckIn') {
                                if (response.data.lastCheckoutRecord === null && response.data
                                    .attendanceRequestRecord === null) {
                                    ShowMsg("bg-warning",
                                        "You have not checked out, So please add attendance request.");
                                } else {
                                    requestLocationPermission('checkIn');
                                }
                            } else if (buttonId === 'BtncheckOut') {
                                const confirmation = confirm("Are you sure you want to check out?");
                                if (confirmation) {
                                    getLocationAndSubmit('check_out', 'regular');
                                }
                            } else if (buttonId === 'BtnbreakIn') {
                                getLocationAndSubmit('break_in', 'break');
                            } else if (buttonId === 'BtnbreakOut') {
                                getLocationAndSubmit('break_out', 'break');
                            }
                        }
                    }
                );
            });

            // Enable the button if modal is closed without checking in
            $('#checkInModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                $('#BtncheckIn').prop("disabled", false);
            });
        }

        function requestLocationPermission(action) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        if (action === 'checkIn') {
                            $('#BtncheckIn').prop("disabled", true);
                            $('#checkInModal').modal('show');
                        }
                    },
                    function(error) {
                        handleLocationError(error);
                    }
                );
            } else {
                ShowMsg("bg-warning", "Geolocation is not supported by this browser.");
            }
        }

        function handleLocationError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    ShowMsg("bg-warning",
                        "You denied the request for location. Please enable location permissions to proceed."
                    );
                    break;
                case error.POSITION_UNAVAILABLE:
                    ShowMsg("bg-warning", "Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    ShowMsg("bg-warning", "The request to get your location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    ShowMsg("bg-warning", "An unknown error occurred.");
                    break;
            }
        }

        function setupCheckInFormSubmission() {
            // Remove any existing submit event listeners to prevent duplication
            $('#checkInForm').off('submit');

            // Attach the submit event listener
            $('#checkInForm').on('submit', function(e) {
                e.preventDefault();
                const note = $('#checkInNote').val().trim();
                getLocationAndSubmit('check_in', 'regular', note);
            });

            // Reset modal data and remove event listeners when the modal is hidden
            $('#checkInModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
                // Reset the form fields
                $('#checkInForm')[0].reset();

                // Clear any error messages
                $('#note-error').text('');

                // Re-enable the Check In button
                $('#BtncheckIn').prop('disabled', false);

                // Remove any lingering event listeners
                $('#checkInForm').off('submit');
            });
        }

        function getLocationAndSubmit(action, sessionType, note = null) {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        const accuracy = position.coords.accuracy;
                        console.log(position.coords);

                        const postData = {
                            action: action,
                            session_type: sessionType,
                            note: note,
                            latitude: latitude,
                            longitude: longitude,
                            accuracy: accuracy
                        };
                        submitPunchData(postData);
                    },
                    function(error) {
                        handleLocationError(error);
                    }
                );
            }
        }

        function submitPunchData(postData) {
            const url = `{{ config('apiConstants.EMPLOYEE_DASHBOARD.EMPLOYEE_DASHBOARD') }}`;

            fnCallAjaxHttpPostEvent(url, postData, true, true, function(response) {
                if (response.status === 200) {
                    $('#checkInModal').modal('hide');
                    ShowMsg("bg-success", response.message);
                    // window.location.reload(true);

                    $("#punchCardSection").load(location.href + " #punchCardSection", function() {
                        showButtonsBasedOnLastAction();
                        loadTodayActivity();
                        loadProgressData();
                        loadWorkinghoursData();
                        loadUpcomingHolidays();
                        loadLeavesData(year);
                    });
                } else {
                    $('#BtncheckIn').prop("disabled", false);
                    ShowMsg("bg-warning", 'The record could not be processed.');
                }
            });
        }

        function showButtonsBasedOnLastAction() {
            const url = `{{ config('apiConstants.EMPLOYEE_DASHBOARD.GET_TIME_SHEET') }}`;

            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {

                    $('.punch-details').html('<span>' +
                        (response.data.firstPunchIn ?
                            moment(response.data.firstPunchIn).format("ddd, DD/MM/yyyy h:mm A") :
                            moment().format("ddd, DD/MM/yyyy")
                        ) +
                        '</span>');

                    totalHoursWorked(response.data);
                    totalHoursBreak(response.data);
                    totalWorkTime(response.data);
                    calculateRemainingTime(response.data);
                    handleButtonVisibility(response.data);
                    setupButtonClick();
                    setupCheckInFormSubmission();
                } else {
                    console.log('Failed to retrieve last action data.');
                }
            });
        }

        function handleButtonVisibility(data) {
            const checkOutTime = data.checkInOut?.check_out_time;

            const breakInTime = data.breakInOut?.check_in_time || null;
            const breakOutTime = data.breakInOut?.check_out_time || null;
            // Check In/Out button visibility
            if (checkOutTime === null) {
                $('#BtncheckOut').removeClass('d-none').show();
                $('#BtnbreakIn').removeClass('d-none').show();
                $('#BtncheckIn').addClass('d-none').hide();
            } else {
                $('#BtncheckIn').removeClass('d-none').show();
                $('#BtncheckOut').addClass('d-none').hide();
            }
            // Break In/Out button visibility
            if (breakInTime === null && checkOutTime === null) {
                $('#BtnbreakOut').addClass('d-none').hide();
            } else if (breakInTime !== null && breakOutTime === null) {
                $('#BtnbreakOut').removeClass('d-none').show();
                $('#BtnbreakIn').addClass('d-none').hide();
                $('#BtncheckOut').addClass('d-none').hide();
            }
        }

        function totalHoursWorked(data) {
            if (data.hoursWorked) {
                // Format the total worked hours as needed
                const totalWorkedTime = data.hoursWorked;
                // Update the HTML inside the punch-hours div
                $('.punch-hours').html(`
            <span class="text-black">
                <b>${totalWorkedTime} hrs</b> <br> Total
            </span>
               `);

            }
        }

        function calculateRemainingTime(data) {
            const hoursWorked = data.hoursWorked ||
                '00:00'; // Default to '00:00' if data.hoursWorked is missing
            const companyWorkingTimeMinutes = (8 * 60) + 30; // 8 hours 30 minutes = 510 minutes

            // Check if hoursWorked is in a valid format, otherwise default to 00:00
            let [workedHours, workedMinutes] = hoursWorked.split(':').map(Number);

            // If workedHours or workedMinutes are NaN (invalid), set them to 0
            workedHours = isNaN(workedHours) ? 0 : workedHours;
            workedMinutes = isNaN(workedMinutes) ? 0 : workedMinutes;

            // Convert worked time to total minutes
            const totalWorkedMinutes = (workedHours * 60) + workedMinutes;

            // Calculate remaining minutes
            const remainingMinutes = companyWorkingTimeMinutes - totalWorkedMinutes;

            // If remainingMinutes is less than 0, set it to 0
            const finalRemainingMinutes = Math.max(remainingMinutes, 0);

            // Convert remaining minutes back to hours and minutes
            const remainingHours = Math.floor(finalRemainingMinutes / 60);
            const remainingMinutesPart = finalRemainingMinutes % 60;

            // Dynamically update the <p> element
            document.getElementById('remaining-time').innerHTML =
                `${remainingHours} Hrs ${remainingMinutesPart} Min`;
        }

        function totalWorkTime(data) {
            if (data.totalWorkTime) {
                const totalWorkTime = data.totalWorkTime;
                const [WorkHours, WorkMinutes] = totalWorkTime.split(':').map(Number);

                // Calculate total work time
                const finalWorkMinutes = (WorkHours * 60) + WorkMinutes; // Corrected calculation
                const finalWorkHours = Math.floor(finalWorkMinutes / 60);
                const WorkMinutesPart = finalWorkMinutes % 60;

                // Format the hours and minutes to always show two digits
                const formattedHours = String(finalWorkHours).padStart(2, '0');
                const formattedMinutes = String(WorkMinutesPart).padStart(2, '0');

                // Update the element with ID 'Work-hours'
                document.getElementById('Work-hours').innerHTML =
                    `${formattedHours} Hrs : ${formattedMinutes} Min`;
            } else {
                // Handle no data case
                document.getElementById('Work-hours').innerHTML = `00 Hrs : 00 Min`;
            }
        }

        function totalHoursBreak(data) {
            if (data.totalBreakTime) {
                const totalBreakTime = data.totalBreakTime;
                const [BreakHours, BreakMinutes] = totalBreakTime.split(':').map(Number);

                // If you want to convert BreakMinutes to total hours and minutes
                const finalBreakMinutes = (BreakHours * 60) + BreakMinutes; // Total minutes
                const finalBreakHours = Math.floor(finalBreakMinutes / 60);
                const BreakMinutesPart = finalBreakMinutes % 60;

                // Correct the element ID selector (remove #)
                document.getElementById('Break-hours').innerHTML =
                    `${finalBreakHours} Hrs ${BreakMinutesPart} Min`;
            }
        }

        function loadTodayActivity() {
            const url = `{{ config('apiConstants.EMPLOYEE_DASHBOARD.GET_TODAY_ACTIVITY') }}`;

            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    let activities = response.data;
                    const timeline = $('.card-timeline');
                    timeline.empty();

                    Object.keys(activities).forEach(time => {
                        let activityType = activities[time];
                        const futureDate = new Date();
                        let timeAgo = moment(time, "HH:mm:ss").format("hh:mm A");

                        let sessionType = activityType.includes('break') ? 'Break' : 'Check';
                        let checkType = activityType.includes('in') ? 'In' : 'Out';

                        let timeDisplay = `
                    <li class="timeline-item timeline-item-transparent">
                        <span class="timeline-point timeline-point"></span>
                        <div class="timeline-event">
                            <div class="timeline-header mb-1">
                                <h6 class="mb-0">${sessionType} ${checkType}</h6>
                            </div>
                            <small class="text-muted">${timeAgo}</small>
                        </div>
                    </li>
                `;

                        timeline.append(timeDisplay);
                    });
                }
            });
        }

        function loadProgressData() {
            const url = `{{ config('apiConstants.EMPLOYEE_DASHBOARD.GET_PROGRESS_DATA') }}`;

            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    let data = response.data;

                    // Update the "Today" progress
                    document.querySelector('.today-progress-bar').style.width =
                        `${data.today.percentage}%`;
                    document.querySelector('.today-progress-bar').setAttribute('aria-valuenow', data
                        .today
                        .percentage);
                    document.querySelector('.today-hours').innerHTML =
                        `${data.today.hours} <small>/ ${data.today.target} hrs</small>`;

                    // Update the "This Week" progress
                    document.querySelector('.week-progress-bar').style.width =
                        `${data.week.percentage}%`;
                    document.querySelector('.week-progress-bar').setAttribute('aria-valuenow', data.week
                        .percentage);
                    document.querySelector('.week-hours').innerHTML =
                        `${data.week.hours} <small>/ ${data.week.target} hrs</small>`;

                    // Update the "This Month" progress
                    document.querySelector('.month-progress-bar').style.width =
                        `${data.month.percentage}%`;
                    document.querySelector('.month-progress-bar').setAttribute('aria-valuenow', data
                        .month
                        .percentage);
                    document.querySelector('.month-hours').innerHTML =
                        `${data.month.hours} <small>/ ${data.month.target} hrs</small>`;

                    document.querySelector('.remaining-progress-bar').style.width =
                        `${data.remaining.percentage}%`;
                    document.querySelector('.remaining-progress-bar').setAttribute('aria-valuenow', data
                        .remaining
                        .remainingPercentage);
                    document.querySelector('.remaining-hours').innerHTML =
                        `${data.remaining.hours} <small>/ ${data.remaining.target} hrs</small>`;
                } else {
                    console.log('Failed to retrieve progress data.');
                }
            });
        }

        function loadWorkinghoursData() {
            const url = `{{ config('apiConstants.EMPLOYEE_DASHBOARD.GET_WORKING_HOURS_DATA') }}`;

            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {

                    var ctx = document.getElementById("myChart4").getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: response.data.labels,
                            datasets: response.data.datasets,
                        },
                        options: {
                            tooltips: {
                                displayColors: true,
                                callbacks: {
                                    mode: 'x',
                                },
                            },
                            scales: {
                                xAxes: [{
                                    stacked: true,
                                    gridLines: {
                                        display: false,
                                    },
                                    ticks: {
                                        autoSkip: false, // Prevent skipping labels
                                        maxRotation: 45, // Rotate labels for better readability
                                        minRotation: 0
                                    }
                                }],
                                yAxes: [{
                                    stacked: true,
                                    ticks: {
                                        beginAtZero: true, // Start Y-axis from 0
                                        min: 0, // Minimum value for Y-axis
                                        max: 10, // Maximum value for Y-axis
                                        callback: function(value, index, values) {
                                            return value +
                                                ' hrs'; // Add unit label to Y-axis values
                                        }
                                    },
                                    type: 'linear',
                                }]
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                position: 'bottom'
                            },
                        }
                    });
                } else {
                    console.log('Failed to retrieve progress data.');
                }
            });
        }

        function loadLeavesData(year) {
            const url = `{{ config('apiConstants.EMPLOYEE_DASHBOARD.GET_LEAVES_DATA') }}?year=${year}`;
            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    const container = document.querySelector('.dynamic-leaves-container');
                    container.innerHTML = '';

                    const row = document.createElement('div');
                    row.classList.add('row', 'gy-5', 'gx-5');

                    response.data.leaveTypes.forEach(leave => {
                        const leaveBox = document.createElement('div');
                        leaveBox.classList.add('col-sm-6');
                        leaveBox.innerHTML = `
                    <div class="attendance-details border h-100">
                        <h5 class="text-primary mb-4">${leave.leave_type_name}</h5>
                        <p class="mb-0">Allowance : <small class="text-success">${leave.max_days} days / year</small></p>
                        <p class="mb-0">Approve Pending : ${leave.pending_days} </p>
                        <p class="mb-0">Leave Taken : ${leave.approved_days} </p>
                        <p class="mb-0">Remaining Balance : ${leave.remaining_balance} </p>
                    </div>
                `;
                        row.appendChild(leaveBox);
                    });

                    container.appendChild(row);
                } else {
                    console.log('Failed to retrieve LeavesData.');
                }
            });
        }

        function loadUpcomingHolidays() {
            const url = `{{ config('apiConstants.EMPLOYEE_DASHBOARD.GET_HOLIDAY_DATA') }}`;
            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    const holidayContainer = document.getElementById('holidayContainer');
                    holidayContainer.innerHTML = '';

                    $("#holidayNotFound").hide();

                    if (response.data.length > 0) {
                        // Sort the holidays by holiday_date
                        response.data.sort((a, b) => new Date(a.holiday_date) - new Date(b.holiday_date));

                        response.data.forEach(holiday => {
                            // Existing holiday rendering code
                            const holidayDiv = `
                            <div class="mb-2 ps-1 pe-1">
                                <div class="d-flex border p-1 rounded">
                                    <div class="avatar me-2">
                                        <span class="avatar-initial rounded bg-label-success">
                                            <i class="mdi mdi-calendar-outline mdi-24px"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="holiday-name mb-0">${holiday.holiday_date}</h6>
                                        <small class="mb-0 text-nowrap d-block overflow-hidden text-primary" style="width: 116px; text-overflow: ellipsis;" title="${holiday.holiday_name}">${holiday.holiday_name}</small>
                                    </div>
                                </div>
                            </div>
                        `;
                            holidayContainer.insertAdjacentHTML('beforeend', holidayDiv);
                        });
                    } else {
                        $("#holidayNotFound").show();
                    }
                } else {
                    $("#holidayNotFound").show();
                    console.error('Failed to retrieve holidays:', response.message);
                }
            });
        }
    </script>
@endsection
