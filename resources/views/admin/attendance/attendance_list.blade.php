@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-header attendance-header d-flex justify-content-between align-items-center flex-wrap">
                <h5 class="mb-3 mb-sm-0">Attendance Report</h5>
                <div class="d-flex flex-wrap align-items-center">
                    <div class="d-flex align-items-center me-3 mb-2 mb-sm-0">
                        <span class="bg-success"></span>
                        <small class="ms-2">Present</small>
                    </div>
                    <div class="d-flex align-items-center me-3 mb-2 mb-sm-0">
                        <span class="bg-danger"></span>
                        <small class="ms-2">Absent</small>
                    </div>
                    <div class="d-flex align-items-center me-3 mb-2 mb-sm-0">
                        <span class="bg-warning"></span>
                        <small class="ms-2">Leave</small>
                    </div>
                    <div class="d-flex align-items-center me-3 mb-2 mb-sm-0">
                        <span style="background: linear-gradient(to left, #ff4c51 50%, #56ca00 50%);"></span>
                        <small class="ms-2">Half Day</small>
                    </div>
                    <div class="d-flex align-items-center me-3 mb-2 mb-sm-0">
                        <span class="bg-info"></span>
                        <small class="ms-2">Holiday</small>
                    </div>
                    <div class="d-flex align-items-center me-3 mb-2 mb-sm-0">
                        <span class="bg-secondary"></span>
                        <small class="ms-2">Weekend</small>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex align-items-center flex-wrap p-4">
                <div class="form-floating form-floating-outline col-md-3 col-12 me-4 mb-3 mb-xxl-0 mb-sm-0">
                    <select class="form-select" id="employeeId" aria-label="Default select example">
                    </select>
                    <label for="exampleFormControlSelect1">Employee</label>
                </div>
                <div class="form-floating form-floating-outline col-md-3 col-12 me-4 mb-3 mb-xxl-0 mb-sm-0">
                    <select class="form-select" id="monthSelect" aria-label="Select Month">
                        <option value="1">Jan</option>
                        <option value="2">Feb</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">Jun</option>
                        <option value="7">July</option>
                        <option value="8">Aug</option>
                        <option value="9">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>
                    <label for="monthSelect">Month</label>
                </div>

                <div class="form-floating form-floating-outline col-md-3 col-12 me-4 mb-3 mb-xxl-0 mb-sm-0">
                    <select class="form-select" id="yearSelect" aria-label="Select Year">
                    </select>
                    <label for="yearSelect">Year</label>
                </div>


                <a href="javascript:void(0)"
                    class="btn btn-sm btn-primary waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0" id="searchButton">
                    <i class="mdi mdi-magnify"></i>
                </a>

                <a href="javascript:void(0)"
                    class="btn btn-sm btn-primary waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0" id="reset">
                    <i class="mdi mdi-replay me-1"></i> Reset
                </a>

                <a href="javascript:void(0)"
                    class="btn btn-sm btn-success waves-effect waves-light mb-3 me-2 mb-xxl-0 mb-sm-0" id="exportExcel">
                    <i class="mdi mdi-file-excel me-1"></i> Export Excel
                </a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered attendance-table mb-1">
                    <thead>
                        <tr class="text-nowrap">
                            <th class="sticky-col">Employee</th>
                            <!-- Placeholder for dynamically generated date columns -->
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <!-- Attendance data will be populated here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- Make the modal larger if necessary -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Attendance Info #<span class="text-primary fw-bold"
                            id="Employee_Id"></span> - <span class="text-primary fw-bold" id="Employee_Name"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span class="mdi mdi-window-close"></span></button>
                </div>
                <div class="modal-body">
                    <!-- Timesheet Section -->
                    <div class="row g-4">
                        <div class="col-12 col-md-6 col-xxl-8">
                            <div class="card punch-status mb-6 h-100">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <h5 class="card-title m-0 me-2">Timesheet</h5>
                                    <p class="text-primary mb-0">
                                        Check In at <br> <span class="punch-details"></span>
                                    </p>
                                </div>
                                <div class="punch-info pt-4 text-center">
                                    <div class="punch-hours mb-4">
                                        <span class="text-black"><b>0.00 hrs</b> <br> Total</span>
                                    </div>
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start">
                                        <div class="clock-in-content">
                                            <h5 class="mb-1">Work Time</h5>
                                            <p id="Work-hours" class="mb-0">00 Hrs : 00 Min</p>
                                        </div>
                                        <div class="statistics">
                                            <ul class="nav">
                                                <li class="border">
                                                    <h6 class="mb-1">Break</h6>
                                                    <p class="mb-0" id="Break-hours">1 Hrs 20 Min</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Today Activity Section -->
                        <div class="col-12 col-md-6 col-xxl-4">
                            <div class="card mb-6 h-100">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="mb-0">Today Activity</h5>
                                    </div>
                                </div>
                                <div class="card-body pt-5" style="height: 276px; overflow: auto;">
                                    <ul class="timeline card-timeline mb-0">
                                        <!-- Timeline items will be dynamically injected here -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        // Declare global variables
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth() + 1;
        const currentYear = currentDate.getFullYear();

        $(document).ready(function() {

            loadEmployeeDropdown('employeeId', '{{ config('apiConstants.USER_API_URLS.USER') }}', false, true);

            $('#reset').last().on('click', function() {
                $('#employeeId').val('0');
                $('#monthSelect').val(currentMonth);
                $('#yearSelect').val(currentYear);
                loadEmployeeDropdown('employeeId', '{{ config('apiConstants.USER_API_URLS.USER') }}', false,
                    true);
                loadAttendance();
            });

            populateYearDropdown('yearSelect', -15, 0);

            $('#monthSelect').val(currentMonth);
            $('#yearSelect').val(currentYear);

            loadAttendance();

            $("#searchButton").click(function() {
                filterAttendanceGrid();
            });

            // Excel export handler
            $("#exportExcel").click(function() {
                exportAttendanceTableToExcel();
            });
        });

        function loadAttendance(employeeId = '', selectedMonth = '', selectedYear = '') {
            selectedMonth = selectedMonth || currentMonth;
            selectedYear = selectedYear || currentYear;

            const url =
                `{{ config('apiConstants.ADMIN_ATTENDANCE_URLS.ADMIN_ATTENDANCE') }}?employee_id=${employeeId}&month=${selectedMonth}&year=${selectedYear}`;

            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    populateAttendanceTable(response.data.users); // Populate table with attendance data
                } else {
                    console.log('Failed to retrieve Data.');
                }
            });
        }

        function filterAttendanceGrid() {
            const employeeId = $("#employeeId").val();
            const selectedMonth = $("#monthSelect").val();
            const selectedYear = $("#yearSelect").val();

            const url =
                `{{ config('apiConstants.ADMIN_ATTENDANCE_URLS.ADMIN_ATTENDANCE_FILTER') }}?employee_id=${employeeId}&month=${selectedMonth}&year=${selectedYear}`;
            // Call to load filtered data via AJAX
            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    populateAttendanceTable(response.data.users); // Populate the table with filtered data
                } else {
                    console.log('Failed to retrieve filtered data.');
                }
            });
        }

        function populateAttendanceTable(users) {
            const tableBody = $(".table-border-bottom-0");
            tableBody.empty(); // Clear current table content

            // Get selected month and year
            const selectedMonth = $("#monthSelect").val();
            const selectedYear = $("#yearSelect").val();

            // Calculate days in the selected month
            const dayNames = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
            const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();

            // Generate the date headers dynamically
            const tableHeaderRow = $(".table thead tr.text-nowrap");
            tableHeaderRow.find(".date-column").remove();

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(selectedYear, selectedMonth - 1, day);
                const dayHeader =
                    `<th class="date-column">${day}</br><b class="text-center">${dayNames[date.getDay()]} (hr)</b></th>`;
                tableHeaderRow.append(dayHeader);
            }

            let workingTimes;

            users.forEach(user => {
                let row = `<tr><th class="sticky-col">${user.full_name}</th>`;

                for (let day = 1; day <= daysInMonth; day++) {
                    const status = user.attendance_status[day] || ''; // Get the status for the day

                    const fullDate =
                        `${selectedYear}-${String(selectedMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    let onclickAttr = '';
                    if (['P', 'P/A', 'A/P'].includes(status)) {
                        onclickAttr =
                            `onclick="handleStatusClick('${user.id}', '${fullDate}', '${status}')"`; // Add onclick event if status is present or half-day
                    }

                    // Retrieve working time for the current day
                    workingTimes = user.working_times[day] || ''; // Default to empty string if no working time

                    let cellMarkup = '';

                    if (status === 'P') {
                        cellMarkup =
                            `<td><span title="Present" class="bg-success" ${onclickAttr}></span>${workingTimes ? `<small>(${workingTimes})</small>` : ''}</td>`;
                    } else if (status === 'A') {
                        cellMarkup = `<td><span title="Absent" class="bg-danger"></span></td>`;
                    } else if (status === 'L') {
                        cellMarkup = `<td><span title="Leave" class="bg-warning"></span></td>`;
                    } else if (status === 'H') {
                        cellMarkup = `<td><span title="Holiday" class="bg-info"></span></td>`;
                    } else if (status === 'WE') {
                        cellMarkup = `<td class="weekend-col"><span title="Weekend"></span></td>`;
                    } else if (status === 'P/A') {
                        cellMarkup =
                            `<td><span title="Half Day" class="first_half-col" ${onclickAttr}></span>${workingTimes ? `<small>(${workingTimes})</small>` : ''}</td>`;
                    } else if (status === 'A/P') {
                        cellMarkup =
                            `<td><span title="Half Day" class="second_half-col" ${onclickAttr}></span>${workingTimes ? `<small>(${workingTimes})</small>` : ''}</td>`;
                    } else {
                        cellMarkup = `<td><span></span></td>`;
                    }


                    // Add cell markup to the row
                    row += cellMarkup;
                }

                row += `</tr>`;
                tableBody.append(row);
            });
        }

        function handleStatusClick(userID, fullDate) {
            loadTodayActivity(userID, fullDate);
            const url = `{{ config('apiConstants.EMPLOYEE_DASHBOARD.GET_TIME_SHEET') }}?userID=${userID}&day=${fullDate}`;

            fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                if (response.status === 200 && response.data) {
                    // Update the punch details
                    $('.punch-details').html('<span>' + (response.data.firstPunchIn ? moment(response.data
                                .firstPunchIn).format("ddd, DD/MM/yyyy h:mm A") :
                            moment().format("ddd, DD/MM/yyyy")
                        ) +
                        '</span>'
                    );
                    totalHoursWorked(response.data);
                    totalHoursBreak(response.data);
                    totalWorkTime(response.data);
                    employeeData(response.data);

                    // Open the Bootstrap modal
                    $('#statusModal').modal('show');
                } else {
                    console.log('Failed to retrieve last action data.');
                }
            });
        }

        function employeeData(data) {
            if (data.employeeData) {
                $("#Employee_Name").text(data.employeeData.full_name);
                $("#Employee_Id").text(data.employeeData.employee_id);
            }
        }

        function totalHoursWorked(data) {
            if (data.hoursWorked) {
                const totalWorkedTime = data.hoursWorked;
                $('.punch-hours').html(`
            <span class="text-black">
                <b>${totalWorkedTime} hrs</b> <br> Total
            </span>
            `);
            }
        }

        function totalHoursBreak(data) {
            if (data.totalBreakTime) {
                const totalBreakTime = data.totalBreakTime;
                const [BreakHours, BreakMinutes] = totalBreakTime.split(':').map(Number);
                const finalBreakMinutes = (BreakHours * 60) + BreakMinutes;
                const finalBreakHours = Math.floor(finalBreakMinutes / 60);
                const BreakMinutesPart = finalBreakMinutes % 60;
                document.getElementById('Break-hours').innerHTML = `${finalBreakHours} Hrs ${BreakMinutesPart} Min`;
            }
        }

        function totalWorkTime(data) {
            if (data.totalWorkTime) {
                const totalWorkTime = data.totalWorkTime;
                const [WorkHours, WorkMinutes] = totalWorkTime.split(':').map(Number);
                const finalWorkMinutes = (WorkHours * 60) + WorkMinutes;
                const finalWorkHours = Math.floor(finalWorkMinutes / 60);
                const WorkMinutesPart = finalWorkMinutes % 60;
                document.getElementById('Work-hours').innerHTML =
                    `${String(finalWorkHours).padStart(2, '0')} Hrs : ${String(WorkMinutesPart).padStart(2, '0')} Min`;
            } else {
                document.getElementById('Work-hours').innerHTML = `00 Hrs : 00 Min`;
            }
        }

        function loadTodayActivity(userID, fullDate) {
            const url =
                `{{ config('apiConstants.EMPLOYEE_DASHBOARD.GET_TODAY_ACTIVITY') }}?userID=${userID}&day=${fullDate}`;

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

        function exportAttendanceTableToExcel() {
            const employeeId = $("#employeeId").val() || '';
            const selectedMonth = $("#monthSelect").val() || currentMonth;
            const selectedYear = $("#yearSelect").val() || currentYear;

            const baseUrl = "{{ config('apiConstants.ADMIN_ATTENDANCE_URLS.ADMIN_ATTENDANCE_EXPORT') }}";

            const fullUrl = `${baseUrl}?employee_id=${employeeId}&month=${selectedMonth}&year=${selectedYear}`;

            window.location.href = fullUrl;
        }
    </script>
@endsection
