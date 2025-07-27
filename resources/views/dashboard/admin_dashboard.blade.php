@extends('layouts.layout')
@section('content')
    <div class="container-fluid flex-grow-1 container-p-y overflow-hidden">
        <div class="row gy-5 gx-5">
            <div class="col-12 col-md-12 col-lg-12 col-xxl-12">
                <div class="row gy-5 gx-5">
                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card mb-0 h-100">
                            <div
                                class="card-body position-relative d-flex flex-column flex-sm-row align-items-sm-center align-items-start justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar w-auto h-auto">
                                        <img src="{{ isset($profile_img) && $profile_img ? asset('storage/profile_images/' . $profile_img) : asset('assets/img/avatars/1.png') }}"
                                            alt="User Image" class="w-px-50 h-auto rounded" />
                                    </div>
                                    <h5 class="card-title mb-0 d-flex flex-column align-items-start"> Welcome Back,
                                        <span class="text-primary">{{ $name ?? '' }}</span>
                                    </h5>
                                </div>
                                <p class="mb-2 mt-2">
                                </p>
                                <a class="btn btn-sm btn-primary rounded text-white myProfileLink">View Profile</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="widget-round">
                                        <div class="bg-round">
                                            <i class="mdi mdi-account-outline mdi-24px"></i>
                                            <div class="half-circle">
                                                <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h6 class="mb-0 text-truncate">Today Presents</h6>
                                        <div class="d-flex align-items-center">
                                            <h4 class="mb-0">
                                                <span id="presentEmployeesCount"></span>
                                                <span>/</span>
                                                <span id="totalEmployees"></span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="widget-round">
                                        <div class="bg-round">
                                            <i class="mdi mdi-account-off-outline mdi-24px"></i>
                                            <div class="half-circle">
                                                <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h6 class="mb-0 text-truncate">Today Absent</h5>
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-0">
                                                    <span id="plannedLeaves"></span>
                                                </h4>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="widget-round">
                                        <div class="bg-round">
                                            <i class="mdi mdi-account-clock-outline mdi-24px"></i>
                                            <div class="half-circle">
                                                <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h6 class="mb-0 text-truncate">Pending Requests</h5>
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-0">
                                                    <a id="pendingLeaveRequests"></a>
                                                </h4>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="widget-round">
                                        <div class="bg-round">
                                            <i class="mdi mdi-account-check-outline mdi-24px"></i>
                                            <div class="half-circle">
                                                <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info col-6 w-100">
                                        <h6 class="mb-0 text-truncate">Total Approved Leave</h6>
                                        <div class="d-flex align-items-center">
                                            <h4 class="mb-0">
                                                <a id="totalleaves"></a>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center  gap-2">
                                    <div class="widget-round">
                                        <div class="bg-round">
                                            <i class="mdi mdi-text-box-multiple-outline mdi-24px"></i>
                                            <div class="half-circle">
                                                <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h6 class="mb-0 text-truncate">Total No of Project's</h5>
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-0">
                                                    <a>{{ $totalProjects }}</a>
                                                </h4>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center  gap-2">
                                    <div class="widget-round">
                                        <div class="bg-round">
                                            <i class="mdi mdi-account-multiple-outline mdi-24px"></i>
                                            <div class="half-circle">
                                                <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h6 class="mb-0 text-truncate">Total No of Clients</h5>
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-0">
                                                    <a>{{ $totalClient }}</a>
                                                </h4>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-lg-4 col-md-6 col-sm-12 col-12">
                        <div class="card Admin-widget card-border-shadow-secondary mb-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center  gap-2">
                                    <div class="widget-round">
                                        <div class="bg-round">
                                            <i class="mdi mdi-calendar-check-outline mdi-24px"></i>
                                            <div class="half-circle">
                                                <img src="assets/img/illustrations/half_rectangle.svg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-info">
                                        <h6 class="mb-0 text-truncate">Total No of Tasks</h5>
                                            <div class="d-flex align-items-center">
                                                <h4 class="mb-0">
                                                    <a>{{ $totalTasks }}</a>
                                                </h4>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-12 col-xxl-12">
                <div class="row gy-5 gx-5">
                    <div class="col-xxl-2 col-lg-6 col-md-12 col-12">
                        <div class="card mb-0">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Upcoming Holidays</h5>
                            </div>
                            <div class="card-body position-relative overflow-hidden d-flex flex-column justify-content-between">
                                <div class="row justify-content-between mb-4">
                                    <div class="overflow-auto position-relative" style="height: 290px;">
                                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-2 row-cols-xxl-1" id="holidayContainer"></div>
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
                    <div class="col-xxl-2 col-lg-6 col-md-12 col-12">
                        <div class="card mb-0 overflow-hidden h-100 text-center">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Today's Birthday</h5>
                            </div>
                            <div
                                class="card-body position-relative overflow-hidden h-100 d-flex flex-column justify-content-between">
                                <div
                                    style="position: absolute;top: 0; left: 0; right: 0; bottom: 0;background: url('../assets/img/backgrounds/birthdaybg.jpg') no-repeat center center / cover;opacity: 0.3;">
                                </div>
                                <div class="row position-relative">
                                    <div class="overflow-auto" style="height: 334px;">
                                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-1 row-cols-xxl-1" id="birthdayContainer">
                                            @foreach ($birthdayData as $data)
                                            <div class="mb-2 ps-1 pe-1">
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
                                            @endforeach
                                            @if (count($birthdayData) == 0)
                                               <div class="d-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100"
                                                    style="display: block;">
                                                    <img src="../assets/img/illustrations/notfouund.svg" class="img-fluid"
                                                        alt="img" id="birthdayNotFound">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-5 col-lg-6 col-md-12 col-12">
                        <div class="card mb-0">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Employees By Department</h5>
                            </div>
                            <div class="card-body">
                                <div id="leaveBarChart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-12 col-12">
                        <div class="card mb-0 h-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Attendance Overview</h5>
                            </div>
                            <div class="card-body">
                                <div id="chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-12 col-lg-12 col-xxl-12">
                <div class="row gy-5 gx-5">
                    <div class="col-12 col-lg-6 col-xxl-5">
                        <div class="card mb-0 h-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Projects</h5>
                                <a href="{{ Route('projects') }}" class="btn btn-sm border-primary bg-label-primary"> View
                                    All <i class="mdi mdi-arrow-right"></i></a>
                            </div>
                            <div class="card-body position-relative">
                                <div class="row gy-5 gx-5">
                                    @if (count($selectedProjects) > 0)
                                        @foreach ($selectedProjects as $project)
                                            <div class="col-12 col-md-6 col-lg-6 col-xxl-6">
                                                <div class="card mb-0 shadow-none border">
                                                    <div class="card-body">
                                                        <h3 class="text-primary">
                                                            <a
                                                                href="javascript:void(0);">{{ $project['project_name'] }}</a>
                                                        </h3>
                                                        <div class="d-flex align-items-center mb-3">
                                                            <a href="javascript:void(0);" class="avatar">
                                                                <img src="../assets/img/avatars/1.png"
                                                                    class="img-fluid rounded" alt="img">
                                                            </a>
                                                            <div class="ms-2">
                                                                <h6 class="fw-bold mb-0">
                                                                    <a class="text-black"
                                                                        href="javascript:void(0);">{{ $project['team_leader_name'] ?? 'Not Assigned' }}</a>
                                                                </h6>
                                                                <span class="d-block">Project Leader</span>
                                                            </div>
                                                        </div>

                                                        <div class="d-flex align-items-center mb-3">
                                                            <a href="javascript:void(0);"
                                                                class="avatar bg-label-success rounded d-flex justify-content-center align-items-center">
                                                                <i class="mdi mdi-calendar mdi-24px"></i>
                                                            </a>
                                                            <div class="ms-2">
                                                                <h6 class="fw-bold mb-0">
                                                                    {{ \Carbon\Carbon::parse($project['start_date'])->format('d M Y') }}
                                                                </h6>
                                                                <span class=" d-block">Start Date</span>
                                                            </div>
                                                        </div>

                                                        <div
                                                            class="d-flex align-items-center justify-content-between bg-transparent border-top border-dashed border-start-0 border-end-0 border-bottom-0 pt-2">

                                                            <div class="d-flex align-items-center">
                                                                <span
                                                                    class="avatar-sm bg-label-primary rounded me-2 d-flex justify-content-center align-items-center">
                                                                    <i class="mdi mdi-playlist-check"></i>
                                                                </span>
                                                                <p class="mb-0 fw-semibold">
                                                                    Tasks :
                                                                    <span
                                                                        class="text-success">{{ $project['completedTasksCount'] }}</span>
                                                                    <span
                                                                        class="text-light">/{{ $project['totalTasks'] }}</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- data not found  -->
                                        <div class="d-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100">
                                            <img src="../assets/img/illustrations/notfouund.svg" class="img-fluid"
                                                alt="img">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-xxl-4">
                        <div class="card mb-0 h-100">
                            <div class="card-header d-flex align-items-center justify-content-between">
                                <h5 class="card-title m-0 me-2">Tasks</h5>
                                <a href="{{ Route('tasks') }}" class="btn btn-sm border-primary bg-label-primary"> View
                                    All <i class="mdi mdi-arrow-right"></i></a>
                            </div>
                            <div class="card-body position-relative overflow-auto d-flex justify-content-start align-items-start">
                                <div class="overflow-hidden w-100 position-relative">
                                    @if (count($selectedTasks) > 0)
                                        @foreach ($selectedTasks as $tasks)
                                            <div class="list-group-item border rounded mb-2 p-2">
                                                <div class="row align-items-center row-gap-3">
                                                    <div class="col-md-8">
                                                        <div class="d-flex align-items-center flex-wrap row-gap-3">
                                                            <span><i class="mdi mdi-dots-grid me-2"></i></span>
                                                            <h6 class="mb-0">
                                                                {{ $tasks['title'] }}
                                                            </h6>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div
                                                            class="d-flex align-items-center justify-content-md-end flex-wrap row-gap-3">
                                                            <span class="badge rounded bg-label-secondary px-2 py-1">
                                                                <i class="mdi mdi-circle-medium"></i>
                                                                {{ $tasks['status_name'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <!-- data not found  -->
                                        <div class="d-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100">
                                            <img src="../assets/img/illustrations/notfouund.svg" class="img-fluid"
                                                alt="img">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 col-xxl-3">
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
                                    <div class="overflow-auto" style="max-height: 256px; min-height: 256px;">
                                        <div class="row row-cols-1 row-cols-sm-1 row-cols-md-1 row-cols-lg-1 row-cols-xxl-1"
                                            id="birthdayContainer">
                                             @forelse ($employeesList as $data)
                                                <div class="mb-2 ps-1 pe-1">
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
                                            <div class="d-flex justify-content-center align-items-center position-absolute top-0 start-0 w-100 h-100"
                                                style="display: block;">
                                                <img src="../assets/img/illustrations/notfouund.svg" class="img-fluid"
                                                    alt="img" id="birthdayNotFound">
                                            </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {

                $("#holidayNotFound").hide();

                loadHeaderData();
                loadBarChartData();
                loadAttendanceOverview();
                loadUpcomingHolidays();
            });

            function loadBarChartData() {
                const url = `{{ config('apiConstants.ADMIN_DASHBOARD.ADMIN_DEPARTMENT_EMPLOYEE_COUNT') }}`;
                fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                    if (response.status === 200 && response.data) {
                        let departmentNames = [];
                        let employeeCounts = [];

                        response.data.forEach(item => {
                            departmentNames.push(item.department_name);
                            employeeCounts.push(item.employee_count);
                        });

                        renderBarChart(departmentNames, employeeCounts);
                    } else {
                        console.log('Failed to load chart data.');
                    }
                });
            }

            function renderBarChart(categories, data) {
                var options = {
                    chart: {
                        type: 'bar',
                        height: 320,
                    },
                    series: [{
                        name: 'Employees',
                        data: data
                    }],
                    colors: ['#891ab4'],
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            borderRadiusApplication: 'end',
                            horizontal: true,
                        }
                    },
                    xaxis: {
                        categories: categories,
                    }
                };

                var chart = new ApexCharts(document.querySelector("#leaveBarChart"), options);
                chart.render();
            }

            function loadAttendanceOverview() {
                const url = `{{ config('apiConstants.ADMIN_DASHBOARD.EMPLOYEE_ATTENDANCE_OVERVIEW') }}`;
                fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                    if (response.status === 200 && response.data) {
                        let total = response.data.total_employees || 0;
                        let present = response.data.attendance_count || 0;
                        let leave = response.data.leave_count || 0;
                        let absent = total - present || 0;
                        let FinalAbsent = absent - leave;

                        renderDonutChart(present, FinalAbsent, leave);
                    }
                });
            }

            function loadHeaderData() {
                const url = `{{ config('apiConstants.ADMIN_LEAVE_URLS.ADMIN_LEAVE_HEDERDATA') }}`;
                fnCallAjaxHttpGetEvent(url, null, true, true, function(response) {
                    if (response.status === 200 && response.data) {
                        let present = response.data.presentEmployeesCount || 0;
                        let total = response.data.totalEmployees || 0;
                        let leave = response.data.approvedLeaveRequests || 0;
                        let absent = total - present;
                        let FinalAbsent = absent - leave;

                        // Update the header data
                        $("#presentEmployeesCount").text(present);
                        $("#pendingLeaveRequests").text(response.data.pendingLeaveRequests);
                        $("#plannedLeaves").text(absent); // now shows actual calculated absent count
                        $("#totalEmployees").text(total);
                        $("#totalleaves").text(response.data.approvedLeaveRequests);

                        // Render donut chart dynamically
                        // renderDonutChart(present, FinalAbsent, leave);
                    } else {
                        console.log('Failed to retrieve Data.');
                    }
                });
            }

            function renderDonutChart(present, FinalAbsent, leave) {
                var options = {
                    series: [present, FinalAbsent, leave],
                    chart: {
                        type: 'donut',
                        height: 320
                    },
                    labels: ['Present', 'Absent', 'Leave'],
                    plotOptions: {
                        pie: {
                            startAngle: -90,
                            endAngle: 90,
                            offsetY: 10,
                            donut: {
                                size: '70%' // Optional: controls donut hole size
                            }
                        }
                    },
                    grid: {
                        padding: {
                            bottom: -100
                        }
                    },
                    colors: ['#28a745', '#dc3545', '#ffc107'],
                    legend: {
                        position: 'bottom'
                    },
                    responsive: [{
                        breakpoint: 480,
                        options: {
                            // chart: {
                            //     width: 200
                            // },
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }]
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
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
