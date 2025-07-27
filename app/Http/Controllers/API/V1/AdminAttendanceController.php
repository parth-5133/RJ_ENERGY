<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\AccessLevel;
use App\Helpers\GetCompanyId;
use Illuminate\Support\Facades\DB;
use App\Constants\ResMessages;
use App\Helpers\ApiResponse;
use DateTime;
use App\Models\User;
use App\Models\Role;
use App\Models\AttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use Carbon\Carbon;
use App\Helpers\JWTUtils;
use App\Exports\ExlExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;

class AdminAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $currentAccessLevel = AccessLevel::getAccessLevel();
        $currentDay = date('j');
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companiesId = GetCompanyId::GetCompanyId();

        if (!$companiesId) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        // Get employees under current access level
        $employees = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('users.id', DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS full_name"))
            ->where('roles.access_level', '<', $currentAccessLevel->access_level)
            ->whereNotIn('roles.code', [$this->AdminRoleCode, $this->superAdminRoleCode, $this->clientRoleCode])
            ->whereNull('users.deleted_at')
            ->where('users.company_id', $companiesId)
            ->get();

        $employeeIds = $employees->pluck('id')->toArray();

        // Fetch all required attendance and leave data in bulk
        $regularAttendances = DB::table('employee_attendances')
            ->select('employee_id', 'status', 'date', 'check_in_time', 'check_out_time')
            ->whereIn('employee_id', $employeeIds)
            ->where('session_type', 'regular')
            ->get()
            ->groupBy('employee_id');

        $breakAttendances = DB::table('employee_attendances')
            ->select('employee_id', 'date', 'check_in_time', 'check_out_time')
            ->whereIn('employee_id', $employeeIds)
            ->where('session_type', 'break')
            ->get()
            ->groupBy('employee_id');

        $approvedLeaves = DB::table('leave_requests')
            ->select('employee_id', 'start_date', 'end_date', 'status')
            ->where('status', 'Approved')
            ->whereIn('employee_id', $employeeIds)
            ->where('company_id', $companiesId)
            ->get()
            ->groupBy('employee_id');

        $usersWithAttendanceStatus = $employees->map(function ($employee) use ($regularAttendances, $approvedLeaves, $breakAttendances, $currentDay) {
            $employeeId = $employee->id;
            $attendanceStatuses = [];
            $workingTimes = [];

            $leaves = $approvedLeaves[$employeeId] ?? collect();
            $attendances = $regularAttendances[$employeeId] ?? collect();
            $breaks = $breakAttendances[$employeeId] ?? collect();

            for ($day = 1; $day <= $currentDay; $day++) {
                $date = sprintf('%04d-%02d-%02d', date('Y'), date('n'), $day);
                $dayOfWeek = (new DateTime($date))->format('N');
                $isWeekend = in_array($dayOfWeek, [6, 7]);

                $attendance = $attendances->firstWhere('date', $date);
                $leaveOnDay = $leaves->first(function ($leave) use ($date) {
                    return $date >= $leave->start_date && $date <= $leave->end_date;
                });

                if ($attendance && $attendance->check_in_time) {
                    $checkIn = new DateTime($attendance->check_in_time);
                    $checkOut = $attendance->check_out_time ? new DateTime($attendance->check_out_time) : null;

                    if ($checkOut && $checkOut < $checkIn) {
                        $checkOut->modify('+1 day'); // Night shift adjustment
                    }

                    $hoursWorked = 0;
                    if ($checkIn && $checkOut) {
                        $interval = $checkIn->diff($checkOut);
                        $hoursWorked = $interval->h + ($interval->i / 60);
                    }

                    $status = 'P';
                    if (!$checkOut) {
                        $workingTimes[$day] = '';
                    } else {
                        $checkInHour = (int)$checkIn->format('H');
                        $checkInMinute = (int)$checkIn->format('i');

                        $status = ($checkInHour > 13 || ($checkInHour == 13 && $checkInMinute > 0))
                            ? ($hoursWorked < 6 ? 'A/P' : 'P')
                            : ($hoursWorked < 6 ? 'P/A' : 'P');

                        $workingTimes[$day] = sprintf('%02d:%02d hr', $interval->h, $interval->i);
                    }
                } elseif ($leaveOnDay) {
                    $status = 'L';
                    $workingTimes[$day] = '';
                } else {
                    $status = $isWeekend ? 'WE' : 'A';
                    $workingTimes[$day] = '';
                }

                $attendanceStatuses[$day] = $status;

                // Subtract break time
                if (!empty($workingTimes[$day])) {
                    $breaksOnDate = $breaks->where('date', $date);
                    $totalBreakMinutes = 0;

                    foreach ($breaksOnDate as $break) {
                        if ($break->check_in_time && $break->check_out_time) {
                            $breakIn = new DateTime($break->check_in_time);
                            $breakOut = new DateTime($break->check_out_time);
                            $breakInterval = $breakIn->diff($breakOut);
                            $totalBreakMinutes += ($breakInterval->h * 60) + $breakInterval->i;
                        }
                    }

                    if ($totalBreakMinutes > 0) {
                        [$hours, $minutes] = explode(':', str_replace(' hr', '', $workingTimes[$day]));
                        $totalWorkMinutes = ($hours * 60) + $minutes;

                        $netMinutes = max(0, $totalWorkMinutes - $totalBreakMinutes);
                        $netHours = floor($netMinutes / 60);
                        $netMinutes %= 60;

                        $workingTimes[$day] = sprintf('%02d:%02d', $netHours, $netMinutes);
                    }
                }
            }

            $employee->attendance_status = $attendanceStatuses;
            $employee->working_times = $workingTimes;

            return $employee;
        });

        return ApiResponse::success(['users' => $usersWithAttendanceStatus], ResMessages::RETRIEVED_SUCCESS);
    }

    public function filterAttendance(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $selectedMonth = $request->query('month');
        $selectedYear = $request->query('year');

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        $user = User::find($userId);
        $roleId = $user->role_id;
        $role = Role::find($roleId);
        $accessLevel = $role ? $role->access_level : null;

        if (is_null($employeeId) || $employeeId == 0) {
            $employeeId = User::join('roles', 'users.role_id', '=', 'roles.id')
                ->where('roles.access_level', '<', $accessLevel)
                ->pluck('users.id')
                ->toArray();
        } elseif (!is_array($employeeId)) {
            $employeeId = [$employeeId];
        }

        $employees = User::whereIn('id', $employeeId)
            ->select('id', 'first_name', 'last_name')
            ->get()
            ->map(function ($employee) {
                $employee->full_name = trim($employee->first_name . ' ' . $employee->last_name);
                return $employee;
            });

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);

        $employeeAttendance = DB::table('employee_attendances')
            ->select('employee_id', 'status', 'date', 'check_in_time', 'check_out_time')
            ->whereIn('employee_id', $employeeId)
            ->where('session_type', 'regular')
            ->whereMonth('date', $selectedMonth)
            ->whereYear('date', $selectedYear)
            ->get()
            ->groupBy('employee_id');

        $approvedLeaves = DB::table('leave_requests')
            ->select('employee_id', 'start_date', 'end_date', 'status')
            ->where('status', 'Approved')
            ->whereIn('employee_id', $employeeId)
            ->where(function ($query) use ($selectedMonth, $selectedYear) {
                $query->where(function ($subQuery) use ($selectedMonth, $selectedYear) {
                    $subQuery->whereMonth('start_date', $selectedMonth)
                        ->whereYear('start_date', $selectedYear);
                })->orWhere(function ($subQuery) use ($selectedMonth, $selectedYear) {
                    $subQuery->whereMonth('end_date', $selectedMonth)
                        ->whereYear('end_date', $selectedYear);
                });
            })
            ->get()
            ->groupBy('employee_id');

        $usersWithAttendanceStatus = $employees->map(function ($employee) use ($employeeAttendance, $approvedLeaves, $daysInMonth, $selectedMonth, $selectedYear) {
            $employeeId = $employee->id;
            $attendanceStatuses = [];
            $workingTimes = [];

            $leavesForEmployee = $approvedLeaves[$employeeId] ?? [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $dateString = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                $attendanceDate = new DateTime($dateString);
                $dayOfWeek = $attendanceDate->format('N');

                if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                    if (isset($employeeAttendance[$employeeId]) && isset($employeeAttendance[$employeeId]->where('date', $dateString)->first()->check_in_time)) {
                        $attendance = $employeeAttendance[$employeeId]->where('date', $dateString)->first();

                        $checkIn = $attendance->check_in_time ? new DateTime($attendance->check_in_time) : null;
                        $checkOut = $attendance->check_out_time ? new DateTime($attendance->check_out_time) : null;

                        if ($checkIn && $checkOut && $checkOut < $checkIn) {

                            $checkOut->modify('+1 day');
                        }

                        if ($checkIn && !$checkOut) {
                            $attendanceStatuses[$day] = 'P';
                            $workingTimes[$day] = '';
                        } elseif ($checkIn && $checkOut) {
                            $interval = $checkIn->diff($checkOut);
                            $hoursWorked = $interval->h + ($interval->i / 60);

                            $workingTimes[$day] = sprintf('%02d:%02d hr', $interval->h, $interval->i);

                            $checkInHour = (int)$checkIn->format('H');
                            $checkInMinute = (int)$checkIn->format('i');

                            if ($checkInHour > 13 || ($checkInHour == 13 && $checkInMinute > 0)) {
                                $attendanceStatuses[$day] = $hoursWorked < 6 ? 'A/P' : 'P';
                            } else {
                                $attendanceStatuses[$day] = $hoursWorked < 6 ? 'P/A' : 'P';
                            }
                        }
                    } else {
                        $attendanceStatuses[$day] = 'WE';
                        $workingTimes[$day] = '';
                    }
                } else {
                    if (isset($employeeAttendance[$employeeId]) && isset($employeeAttendance[$employeeId]->where('date', $dateString)->first()->check_in_time)) {
                        $attendance = $employeeAttendance[$employeeId]->where('date', $dateString)->first();

                        $checkIn = new DateTime($attendance->check_in_time);
                        $checkOut = new DateTime($attendance->check_out_time);

                        if ($checkIn && $checkOut && $checkOut < $checkIn) {
                            $checkOut->modify('+1 day');
                        }

                        $interval = $checkIn->diff($checkOut);
                        $hoursWorked = $interval->h + ($interval->i / 60);

                        $workingTimes[$day] = sprintf('%02d:%02d hr', $interval->h, $interval->i);

                        $checkInHour = (int)$checkIn->format('H');
                        $checkInMinute = (int)$checkIn->format('i');

                        if ($checkInHour > 13 || ($checkInHour == 13 && $checkInMinute > 0)) {
                            $attendanceStatuses[$day] = $hoursWorked < 6 ? 'A/P' : 'P';
                        } else {
                            $attendanceStatuses[$day] = $hoursWorked < 6 ? 'P/A' : 'P';
                        }
                    } else {

                        foreach ($leavesForEmployee as $leave) {
                            if ($dateString >= $leave->start_date && $dateString <= $leave->end_date) {
                                $attendanceStatuses[$day] = 'L';
                                $workingTimes[$day] = '';
                                continue 2;
                            }
                        }
                        $attendanceStatuses[$day] = 'A';
                        $workingTimes[$day] = '';
                    }
                }
            }

            $employee->attendance_status = $attendanceStatuses;
            // Get break times for this employee
            $breakTimes = DB::table('employee_attendances')
                ->select('date', 'check_in_time', 'check_out_time')
                ->where('employee_id', $employeeId)
                ->where('session_type', 'break')
                ->whereMonth('date', $selectedMonth)
                ->whereYear('date', $selectedYear)
                ->get()
                ->groupBy('date');

            // Subtract break time from working time
            foreach ($workingTimes as $day => $workTime) {
                if (!empty($workTime)) {
                    $dateString = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                    $dayBreaks = $breakTimes[$dateString] ?? collect();

                    $totalBreakMinutes = 0;
                    foreach ($dayBreaks as $break) {
                        if ($break->check_in_time && $break->check_out_time) {
                            $breakIn = new DateTime($break->check_in_time);
                            $breakOut = new DateTime($break->check_out_time);
                            $breakInterval = $breakIn->diff($breakOut);
                            $totalBreakMinutes += ($breakInterval->h * 60) + $breakInterval->i;
                        }
                    }

                    if ($totalBreakMinutes > 0) {
                        // Parse current work time
                        list($hours, $minutes) = explode(':', str_replace(' hr', '', $workTime));
                        $totalWorkMinutes = ((int)$hours * 60) + (int)$minutes;

                        // Subtract break time
                        $netMinutes = max(0, $totalWorkMinutes - $totalBreakMinutes);
                        $netHours = floor($netMinutes / 60);
                        $netMinutes = $netMinutes % 60;

                        // Format the result
                        $workingTimes[$day] = sprintf('%02d:%02d hr', $netHours, $netMinutes);
                    }
                }
            }

            $employee->working_times = $workingTimes;

            return $employee;
        });

        $data = [
            'users' => $usersWithAttendanceStatus,
        ];

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function getAllAttendanceRequest(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $companiesId = GetCompanyId::GetCompanyId();

        // Get filter parameters
        $employeeId = $request->query('employee_id');
        $leaveStatus = $request->query('leave_status');

        if ($companiesId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $attendanceRequestsData = DB::table('attendance_requests')
            ->leftJoin('users as updater', 'attendance_requests.updated_by', '=', 'updater.id')
            ->join('users as employee', 'attendance_requests.employee_id', '=', 'employee.id')
            ->select(
                'attendance_requests.employee_id',
                'employee.employee_id as employeeId',
                'employee.first_name',
                'employee.last_name',
                DB::raw("CONCAT(employee.first_name, ' ', employee.last_name) as full_name"),
                'attendance_requests.note',
                'attendance_requests.id',
                'attendance_requests.attendance_status',
                DB::raw("DATE_FORMAT(attendance_requests.attendance_date, '%d/%m/%Y') as formatted_attendance_date"),
                DB::raw("DATE_FORMAT(attendance_requests.attendance_time, '%H:%i') as formatted_attendance_time"),
                'attendance_requests.status',
                'attendance_requests.comment',
                DB::raw("CONCAT(COALESCE(updater.first_name, ''), ' ', COALESCE(updater.last_name, '')) as updated_name"),
                DB::raw("DATE_FORMAT(COALESCE(attendance_requests.updated_at,''), '%d/%m/%Y') as updated_at_formatted")
            )
            ->whereNull('attendance_requests.deleted_at');

        // Apply employee filter if provided
        if ($employeeId && $employeeId != '') {
            $attendanceRequestsData->where('attendance_requests.employee_id', $employeeId);
        }

        // Apply status filter if provided
        if ($leaveStatus && $leaveStatus != '0') {
            if ($leaveStatus == '1') {
                $attendanceRequestsData->where('attendance_requests.status', 'Approved');
            } elseif ($leaveStatus == '2') {
                $attendanceRequestsData->where('attendance_requests.status', 'Rejected');
            } elseif ($leaveStatus == '3') {
                $attendanceRequestsData->where('attendance_requests.status', 'Pending');
            }
        }

        if ($companiesId) {
            $attendanceRequestsData->where('attendance_requests.company_id', $companiesId);
        }

        $attendanceRequestsData = $attendanceRequestsData->orderByDesc('attendance_requests.id')->get();

        $attendanceRequestsData = $attendanceRequestsData->map(function ($item) {
            if ($item->attendance_status === 'check_in') {
                $item->attendance_status = 'Check In';
            } elseif ($item->attendance_status === 'check_out') {
                $item->attendance_status = 'Check Out';
            }
            return $item;
        });

        return ApiResponse::success($attendanceRequestsData, ResMessages::RETRIEVED_SUCCESS);
    }
    public function updateAttendanceRequest(UpdateAttendanceRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $Id = $request->input('id');
        $attendanceDate = $request->input('attendance_date');
        $attendanceTime = $request->input('attendance_time');
        $attendanceStatus = $request->input('attendance_status');
        $request_Status = $request->input('request_status');
        $comment = $request->input('comment');

        $attendanceRequest = DB::table('attendance_requests')->find($Id);
        $employeeId = $attendanceRequest->employee_id;

        if ((int) $request_Status === 1) {
            if ($attendanceStatus === 'check_out') {
                $attendanceRecord = DB::table('employee_attendances')
                    ->where('employee_id', $employeeId)
                    ->where('date', $attendanceDate)
                    ->where('session_type', 'regular')
                    ->first();

                if (!$attendanceRecord) {
                    return ApiResponse::error('No attendance record found for the given employee ID and date.', ResMessages::NOT_FOUND);
                }
            }

            $attendanceDateTime = Carbon::createFromFormat('H:i:s', $attendanceTime)->format('H:i:s');

            if ($attendanceStatus === 'Check in') {

                $existingRecord = DB::table('employee_attendances')
                    ->where('employee_id', $employeeId)
                    ->where('date', $attendanceDate)
                    ->where('session_type', 'regular')
                    ->first();

                if ($existingRecord) {
                    DB::table('attendance_requests')->where('id', $Id)->update(['status' => 'Approved', 'comment' => $comment ? $comment : null, 'updated_by' => $currentUser->id, 'updated_at' => now()]);
                    return ApiResponse::error(null, 'Check-in already completed for this employee on the given date.');
                }
                DB::table('employee_attendances')->insert([
                    'employee_id' => $employeeId,
                    'date' => $attendanceDate,
                    'session_type' => 'regular',
                    'check_in_time' => $attendanceDateTime,
                    'status' => 'active',
                    'created_at' => now(),
                ]);
                DB::table('attendance_requests')->where('id', $Id)->update(['status' => 'Approved', 'comment' => $comment ? $comment : null, 'updated_by' => $currentUser->id, 'updated_at' => now()]);
                return ApiResponse::success(null, 'Check-in record created successfully.');
            } elseif ($attendanceStatus === 'Check out') {
                DB::table('employee_attendances')
                    ->where('employee_id', $employeeId)
                    ->where('date', $attendanceDate)
                    ->where('session_type', 'regular')
                    ->update(['check_out_time' => $attendanceDateTime, 'check_out_date' => $attendanceDate]);
            }

            DB::table('attendance_requests')->where('id', $Id)->update(['status' => 'Approved', 'comment' => $comment ? $comment : null, 'updated_by' => $currentUser->id, 'updated_at' => now()]);
            return ApiResponse::success(null, 'Attendance record updated successfully.');
        } else {

            DB::table('attendance_requests')
                ->where('id', $Id)
                ->update([
                    'status' => $request_Status,
                    'comment' => $comment ? $comment : null,
                    'updated_by' =>  $currentUser->id,
                    'updated_at' => now(),
                ]);

            DB::table('employee_attendances')
                ->where('employee_id', $employeeId)
                ->where('date', $attendanceDate)
                ->update([
                    'check_in_time' => $attendanceStatus === 'Check in' ? null : DB::raw('check_in_time'),
                    'check_out_time' => $attendanceStatus === 'Check out' ? null : DB::raw('check_out_time'),
                ]);

            return ApiResponse::success(null, 'Attendance record updated successfully.');
        }
    }
    public function getAttendanceRequest(Request $request)
    {
        $employee_id = $request->employee_id;

        $data = DB::table('attendance_requests')
            ->join('users', 'attendance_requests.employee_id', '=', 'users.id')
            ->where('attendance_requests.id', $employee_id)
            ->select(
                'attendance_requests.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name"),
                'users.employee_id as Employee_Id',
            )
            ->first();

        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
    public function deleteAttendanceRequest($id)
    {
        $data = AttendanceRequest::find($id);

        if ($data) {
            $data->delete();
            return ApiResponse::success($data, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND,);
        }
    }
    public function getAllAttendanceRequestByID()
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();

        $data = DB::table('attendance_requests')
            ->join('users', 'attendance_requests.employee_id', '=', 'users.id')
            ->where('attendance_requests.employee_id', $currentUser->id)
            ->select(
                'attendance_requests.*',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name"),
                'users.employee_id as Employee_Id',
            )
            ->whereNull('attendance_requests.deleted_at')
            ->orderByDesc('id')
            ->get();

        $data = $data->map(function ($item) {
            // Format the attendance_date to dd/mm/yyyy
            $item->attendance_date = Carbon::parse($item->attendance_date)->format('d/m/Y');

            // Customize the attendance_status field
            if ($item->attendance_status === 'check_in') {
                $item->attendance_status = 'Check In';
            } elseif ($item->attendance_status === 'check_out') {
                $item->attendance_status = 'Check Out';
            }
            return $item;
        });

        if ($data) {
            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($data, ResMessages::NOT_FOUND);
        }
    }
    public function exportAttendance(Request $request)
    {
        $employeeId = $request->query('employee_id');
        $month = $request->query('month');
        $year = $request->query('year');

        // Get all employees or specific employee
        $employeesQuery = DB::table('users')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.code', $this->employeeRoleCode)
            ->whereNull('users.deleted_at')
            ->where('users.company_id', GetCompanyId::GetCompanyId())
            ->select('users.id', 'users.first_name', 'users.last_name');

        if (!empty($employeeId) && $employeeId != 0) {
            $employeesQuery->where('users.id', $employeeId);
        }

        $employees = $employeesQuery->get();

        // Get days in month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Prepare headers (Employee Name + Day Columns)
        $headers = ['Employee Name'];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $headers[] = sprintf('%02d', $day);
        }

        // Get attendance data
        $attendanceData = DB::table('employee_attendances')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->select('employee_id', 'date', 'check_in_time', 'check_out_date', 'check_out_time', 'session_type')
            ->get();

        // Group data by employee_id and day
        $attendanceByEmployeeAndDate = [];

        foreach ($attendanceData as $record) {
            $day = Carbon::parse($record->date)->day;

            if (!$record->check_in_time || !$record->check_out_time || !$record->check_out_date) {
                continue;
            }

            try {
                $checkIn = Carbon::parse($record->date . ' ' . $record->check_in_time);
                $checkOut = Carbon::parse($record->check_out_date . ' ' . $record->check_out_time);

                if ($checkOut->lessThan($checkIn)) {
                    continue;
                }

                $duration = $checkOut->diffInSeconds($checkIn);
                $employeeId = $record->employee_id;
                $sessionType = strtolower(trim($record->session_type));

                // Initialize storage
                if (!isset($attendanceByEmployeeAndDate[$employeeId][$day])) {
                    $attendanceByEmployeeAndDate[$employeeId][$day] = [
                        'work' => 0,
                        'break' => 0,
                    ];
                }

                if ($sessionType === 'break') {
                    $attendanceByEmployeeAndDate[$employeeId][$day]['break'] += $duration;
                } else {
                    $attendanceByEmployeeAndDate[$employeeId][$day]['work'] += $duration;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Prepare day labels
        $days = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $days[] = [
                'day' => $date->format('d') . '(Hrs)',
                'weekday' => $date->format('D'),
            ];
        }

        // Prepare data for Excel
        $data = [];
        foreach ($employees as $employee) {
            $row = [$employee->first_name . ' ' . $employee->last_name];
            for ($day = 1; $day <= $daysInMonth; $day++) {
                if (isset($attendanceByEmployeeAndDate[$employee->id][$day])) {
                    $totalWork = $attendanceByEmployeeAndDate[$employee->id][$day]['work'];
                    $totalBreak = $attendanceByEmployeeAndDate[$employee->id][$day]['break'];
                    $netWork = max(0, $totalWork - $totalBreak);
                    $row[] = gmdate('H:i:s', $netWork);
                } else {
                    $row[] = '';
                }
            }
            $data[] = $row;
        }

        // Export to Excel
        $export = new ExlExport($data, $days, date('F', mktime(0, 0, 0, $month, 10)), $year);
        return Excel::download($export, 'attendance-report.xlsx');
    }
}
