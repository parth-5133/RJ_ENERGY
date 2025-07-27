<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\JWTUtils;
use App\Helpers\ApiResponse;
use App\Models\EmployeeAttendance;
use App\Models\AttendanceRequest;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Constants\ResMessages;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\AttendanceRequestStore;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendAttendanceRequestToAdmin;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Helpers\DateHelper;
use App\Helpers\GetCompanyId;
use App\Helpers\GetYear;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\Role;
use App\Helpers\NotificationHelper;

class EmployeeDashboardController extends Controller
{

    public function fetchTimeSheetData(Request $request)
    {
        // Check if userID and day parameters are present in the request
        if ($request->has('userID') && $request->has('day')) {
            $userId = $request->input('userID');
            $day = $request->input('day');
        } else {
            // If parameters are not provided, use the current logged-in user and today's date
            $currentUser = JWTUtils::getCurrentUserByUuid();
            $userId = $currentUser->id;
            $day = now()->format('Y-m-d');
        }

        $employeeData = User::where('id', $userId)
            ->select('employee_id')
            ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name")
            ->first();

        // Fetch the attendance data for the specific user and target date
        $attendanceData = EmployeeAttendance::where('employee_id', $userId)
            ->where('date', $day)
            ->get()
            ->groupBy('session_type');

        // Existing logic for handling attendance data
        $checkInOut = $attendanceData->get('regular') ? $attendanceData->get('regular')->last() : null;
        $breakInOut = $attendanceData->get('break') ? $attendanceData->get('break')->last() : null;

        $checkInRecords = $attendanceData->get('regular');

        $firstChekInTime = $checkInRecords ? $checkInRecords->min('check_in_time') : null;
        $firstPunchInDate = $checkInRecords ? $checkInRecords->min('date') : null;
        $firstPunchIn = $firstPunchInDate && $firstChekInTime ? $firstPunchInDate . ' ' . $firstChekInTime : null;
        $lastChekOutTime = $checkInRecords ? $checkInRecords->max('check_out_time') : null;

        // Calculate total hours worked
        if ($firstChekInTime) {
            $firstChekInTime = DateHelper::createFromTimeStringWithTimezone($firstChekInTime);
            $lastChekOutTime = $lastChekOutTime
                ? DateHelper::createFromTimeStringWithTimezone($lastChekOutTime)
                : now()->setTimezone('Asia/Kolkata');

            // Adjust for overnight transition if check-out is earlier than check-in
            if ($lastChekOutTime->lt($firstChekInTime)) {
                $lastChekOutTime->addDay(); // Add 1 day to check-out time
            }

            $totalWorkTimeInMinutes = $firstChekInTime->diffInMinutes($lastChekOutTime);

            $totalWorkTimeHours = floor($totalWorkTimeInMinutes / 60);
            $totalWorkTimeMinutes = $totalWorkTimeInMinutes % 60;
            $hoursWorked = sprintf('%02d:%02d', $totalWorkTimeHours, $totalWorkTimeMinutes);
        } else {
            $hoursWorked = '00:00';
        }

        // Calculate total work time
        if ($firstChekInTime) {
            $firstChekInTime = DateHelper::createFromTimeStringWithTimezone($firstChekInTime);
            $lastChekOutTime = $lastChekOutTime
                ? DateHelper::createFromTimeStringWithTimezone($lastChekOutTime)
                : now()->setTimezone('Asia/Kolkata');

            // Adjust for overnight transition if check-out is earlier than check-in
            if ($lastChekOutTime->lt($firstChekInTime)) {
                $lastChekOutTime->addDay(); // Add 1 day to check-out time
            }

            $totalWorkTimeInMinutes = $firstChekInTime->diffInMinutes($lastChekOutTime);

            $totalWorkTimeHours = floor($totalWorkTimeInMinutes / 60);
            $totalWorkTimeMinutes = $totalWorkTimeInMinutes % 60;
            $totalWorkTime = sprintf('%02d:%02d', $totalWorkTimeHours, $totalWorkTimeMinutes);
        } else {
            $totalWorkTime = '00:00';
        }

        // Calculate total break time
        $breakRecords = $attendanceData->get('break');
        $totalBreakTimeInMinutes = 0;
        if ($breakRecords && $breakRecords->count() > 0) {
            foreach ($breakRecords as $break) {
                if ($break->check_in_time && $break->check_out_time) {
                    $checkInTime = Carbon::parse($break->check_in_time);
                    $checkOutTime = Carbon::parse($break->check_out_time);
                    $totalBreakTimeInMinutes += $checkInTime->diffInMinutes($checkOutTime);
                }
            }
            $totalBreakTimeHours = floor($totalBreakTimeInMinutes / 60);
            $totalBreakTimeMinutes = $totalBreakTimeInMinutes % 60;
            $totalBreakTime = sprintf('%02d:%02d', $totalBreakTimeHours, $totalBreakTimeMinutes);
        } else {
            $totalBreakTime = '00:00';
        }

        // Adjust total work time if break time is present
        if (isset($totalBreakTimeInMinutes) && $totalBreakTimeInMinutes > 0) {
            $workHoursAndMinutes = explode(':', $totalWorkTime);
            $workTotalInMinutes = ($workHoursAndMinutes[0] * 60) + $workHoursAndMinutes[1];
            $remainingWorkTimeInMinutes = $workTotalInMinutes - $totalBreakTimeInMinutes;
            $remainingWorkTimeInMinutes = max($remainingWorkTimeInMinutes, 0);
            $remainingWorkHours = floor($remainingWorkTimeInMinutes / 60);
            $remainingWorkMinutes = $remainingWorkTimeInMinutes % 60;
            $totalWorkTime = sprintf('%02d:%02d', $remainingWorkHours, $remainingWorkMinutes);
        }

        // Prepare data for the response
        $data = [
            'firstPunchIn' => $firstPunchIn,
            'checkInOut' => $checkInOut ?? null,
            'breakInOut' => $breakInOut ?? null,
            'hoursWorked' => $hoursWorked,
            'totalWorkTime' => $totalWorkTime,
            'totalBreakTime' => $totalBreakTime,
            'employeeData' => $employeeData,
        ];

        // Return success response with data
        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function storeAttendance(StoreAttendanceRequest $request)
    {
        $validated = $request->validated();

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        // Check for presence of latitude and longitude
        if (empty($validated['latitude']) || empty($validated['longitude'])) {
            return ApiResponse::error(null, 'Latitude and longitude are required.');
        }

        $session_type = $validated['session_type'];

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        if (!$userId) {
            return ApiResponse::error(null, 'Invalid user ID.');
        }

        $today = now()->format('Y-m-d');

        $currentTime = now()->setTimezone('Asia/Kolkata')->format('H:i:s');
        switch ($validated['action']) {
            case 'check_in':
                $existingCheckIn = EmployeeAttendance::where('employee_id', $userId)
                    ->where('date', $today)
                    ->where('session_type', $session_type)
                    ->where('status', 'complete')
                    ->first();

                if (!$existingCheckIn) {
                    EmployeeAttendance::create([
                        'company_id' => $CompanyId,
                        'employee_id' => $userId,
                        'date' => $today,
                        'check_in_time' => $currentTime,
                        'session_type' => $session_type,
                        'status' => 'active',
                        'note' => $validated['note'] ?? null,
                        'latitude' => $validated['latitude'],
                        'longitude' => $validated['longitude'],
                        'accuracy' => $validated['accuracy'],
                    ]);

                    return ApiResponse::success(null, 'Check-in successful.');
                }

                return ApiResponse::error(null, 'You have already checked in for today.');

            case 'break_in':
                $existingSession = EmployeeAttendance::where('employee_id', $userId)
                    ->where('date', $today)
                    ->where('session_type', $session_type)
                    ->where('status', 'active')
                    ->first();

                if (!$existingSession) {
                    EmployeeAttendance::create([
                        'company_id' => $CompanyId,
                        'employee_id' => $userId,
                        'date' => $today,
                        'check_in_time' => $currentTime,
                        'session_type' => $session_type,
                        'status' => 'active',
                        'note' => $validated['note'] ?? null,
                    ]);

                    return ApiResponse::success(null, ($validated['action'] === 'check_in') ? 'Check-in successful.' : 'Break-in successful.');
                }

                return ApiResponse::error(null, 'Already checked in for today.');

            case 'check_out':
            case 'break_out':
                $session = EmployeeAttendance::where('employee_id', $userId)
                    ->where('date', $today)
                    ->where('session_type', $session_type)
                    ->where('status', 'active')
                    ->first();

                if ($session) {
                    $session->update([
                        'check_out_time' => $currentTime,
                        'check_out_date' => $today,
                        'status' => 'complete',
                    ]);


                    return ApiResponse::success(null, ($validated['action'] === 'check_out') ? 'Check-out successful.' : 'Break-out successful.');
                }

                return ApiResponse::error(null, ($validated['action'] === 'check_out') ? 'No active session to check out.' : 'No active break session to check out.');

            default:
                return ApiResponse::error(null, 'Invalid action.');
        }
    }
    public function listAttendances(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        $month = $request->query('filterMonth');
        $year = $request->query('filterYear');

        $attendanceRecords = EmployeeAttendance::where('employee_id', $userId)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date', 'desc')
            ->get();

        $attendanceData = $attendanceRecords->groupBy('date');

        $attendanceData = $attendanceData->map(function ($records, $date) {
            $totalBreakTime = 0;
            $firstCheckIn = null;
            $lastCheckOut = null;

            foreach ($records as $attendance) {
                if (!$attendance) {
                    continue;
                }

                if ($attendance->session_type === 'regular') {
                    $currentCheckIn = Carbon::parse($attendance->date . ' ' . $attendance->check_in_time);
                    $currentCheckOut = $attendance->check_out_time ? Carbon::parse($attendance->check_out_date . ' ' . $attendance->check_out_time) : null;


                    if (!$firstCheckIn || $currentCheckIn->lt($firstCheckIn)) {
                        $firstCheckIn = $currentCheckIn;
                    }
                    if (!$lastCheckOut || ($currentCheckOut && $currentCheckOut->gt($lastCheckOut))) {
                        $lastCheckOut = $currentCheckOut;
                    }

                    $productionCheckOut = ($attendance->check_out_date && $attendance->check_out_time) ? Carbon::parse($attendance->check_out_date . ' ' . $attendance->check_out_time)
                        : null;

                    $production = $productionCheckOut ? $currentCheckIn->diffInMinutes($productionCheckOut) : 0;
                }
                if ($attendance->session_type === 'break' && $attendance->check_out_time) {
                    $checkIn = Carbon::parse($attendance->date . ' ' . $attendance->check_in_time);
                    $checkOut = Carbon::parse($attendance->check_out_date . ' ' . $attendance->check_out_time);
                    $totalBreakTime += $checkIn->diffInMinutes($checkOut);
                }
            }

            $finalProduction = $production - $totalBreakTime;

            $productionFormatted = $this->formatBreakTimeFromMinutes($finalProduction);

            $breakTimeFormatted = $this->formatBreakTimeFromMinutes($totalBreakTime);

            return [
                'date' => Carbon::parse($date)->format('d/m/Y'),
                'firstCheckIn' => $firstCheckIn ? $firstCheckIn->format('h:i A') : '',
                'lastCheckOut' => $lastCheckOut ? $lastCheckOut->format('h:i A') : 'In Progress',
                'production' => $production <= 0 ?  'In Progress' : $productionFormatted,
                'break' => $totalBreakTime > 0 ? $breakTimeFormatted : 'No Break',
            ];
        });

        return ApiResponse::success($attendanceData->values(), ResMessages::RETRIEVED_SUCCESS);
    }
    private function formatBreakTimeFromMinutes($totalMinutes)
    {
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return "{$hours} hr" . ($hours !== 1 ? 's' : '') . " {$minutes} min" . ($minutes !== 1 ? 's' : '');
    }
    public function requestAttendance(AttendanceRequestStore $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $attendanceStatus = $request->input('attendance_status');
        $attendanceDate = $request->input('attendance_date');
        $id = $request->input('userId');

        if ($id == 0) {
            // Creating a new record
            if ($attendanceStatus === 'check_in') {
                $existingRecord = DB::table('employee_attendances')
                    ->where('employee_id', $userId)
                    ->where('date', $attendanceDate)
                    ->where('session_type', 'regular')
                    ->first();

                if ($existingRecord) {
                    return ApiResponse::error(null, 'You have already checked in for the day. Please try another.');
                }

                // Create new attendance request
                $data = AttendanceRequest::create([
                    'company_id' => $CompanyId,
                    'employee_id' => $userId,
                    'note' => $request->note,
                    'attendance_date' => $request->attendance_date,
                    'attendance_time' => $request->attendance_time,
                    'attendance_status' => $request->attendance_status,
                    'status' => "Pending",
                    'created_by' => $userId,
                    'created_at' => now(),
                    'updated_at' => null
                ]);

                $notify = NotificationHelper::canSendNotification('attendance_request');

                if ($notify['email']) {

                    $adminEmail = env('MAIL_USERNAME');
                    try {
                        Mail::to($adminEmail)->send(new SendAttendanceRequestToAdmin($data));
                    } catch (\Exception $e) {
                        return ApiResponse::error(null, 'Your request has been sent successfully, but failed to send email notifications. Please contact support.');
                    }
                }

                if ($notify['browser']) {

                    $template = NotificationTemplate::where('template_name', 'attendance_request_created')->first();

                    $title = str_replace(
                        ['{employee_name}'],
                        [$currentUser->first_name],
                        $template->title
                    );

                    $message = str_replace(
                        ['{employee_name}', '{attendance_status}'],
                        [$currentUser->first_name, "Check In"],
                        $template->message
                    );

                    $userIds = Role::where('code', $this->AdminRoleCode)
                        ->where('roles.company_id', $CompanyId)
                        ->whereNull('roles.deleted_at')
                        ->where('roles.is_active', true)
                        ->leftJoin('users', 'roles.id', '=', 'users.role_id')
                        ->whereNull('users.deleted_at')
                        ->pluck('users.id');

                    foreach ($userIds as $userId) {
                        Notification::create([
                            'company_id' => $CompanyId,
                            'user_id' => $userId,
                            'title' => $title,
                            'message' => $message,
                            'has_view_button' => false,
                            'created_at' => now(),
                            'created_by' => $currentUser->id,
                        ]);
                    }
                }

                return ApiResponse::success($data, 'Attendance request submitted successfully.');
            } elseif ($attendanceStatus === 'check_out') {
                $existingCheckInRecord = DB::table('employee_attendances')
                    ->where('employee_id', $userId)
                    ->where('date', $attendanceDate)
                    ->where('session_type', 'regular')
                    ->first();

                $existingAttendanceRequest = DB::table('attendance_requests')
                    ->where('employee_id', $userId)
                    ->where('attendance_date', $attendanceDate)
                    ->where('attendance_status', 'check_in')
                    ->whereIn('status', ['Approved', 'Pending'])
                    ->first();

                // If neither exists, return an error
                if ($existingCheckInRecord === null && $existingAttendanceRequest === null) {
                    return ApiResponse::error(null, 'You have not checked in on the given date. Check-in record is missing.');
                }

                // Create new attendance request
                $data = AttendanceRequest::create([
                    'company_id' => $CompanyId,
                    'employee_id' => $userId,
                    'note' => $request->note,
                    'attendance_date' => $request->attendance_date,
                    'attendance_time' => $request->attendance_time,
                    'attendance_status' => $request->attendance_status,
                    'status' => "Pending",
                    'created_by' => $userId,
                    'created_at' => now(),
                    'updated_at' => null
                ]);

                $notify = NotificationHelper::canSendNotification('attendance_request');

                if ($notify['email']) {

                    $adminEmail = env('MAIL_USERNAME');
                    try {
                        Mail::to($adminEmail)->send(new SendAttendanceRequestToAdmin($data));
                    } catch (\Exception $e) {
                        return ApiResponse::error(null, 'Your request has been sent successfully, but failed to send email notifications. Please contact support.');
                    }
                }

                if ($notify['browser']) {

                    $template = NotificationTemplate::where('template_name', 'attendance_request_created')->first();

                    $title = str_replace(
                        ['{employee_name}'],
                        [$currentUser->first_name],
                        $template->title
                    );

                    $message = str_replace(
                        ['{employee_name}', '{attendance_status}'],
                        [$currentUser->first_name, "Check Out"],
                        $template->message
                    );

                    $userIds = Role::where('code', $this->AdminRoleCode)
                        ->where('roles.company_id', $CompanyId)
                        ->whereNull('roles.deleted_at')
                        ->where('roles.is_active', true)
                        ->leftJoin('users', 'roles.id', '=', 'users.role_id')
                        ->whereNull('users.deleted_at')
                        ->pluck('users.id');

                    foreach ($userIds as $userId) {
                        Notification::create([
                            'company_id' => $CompanyId,
                            'user_id' => $userId,
                            'title' => $title,
                            'message' => $message,
                            'has_view_button' => false,
                            'created_at' => now(),
                            'created_by' => $currentUser->id,
                        ]);
                    }
                }

                return ApiResponse::success($data, 'Attendance request submitted successfully.');
            }
        } else {
            // Updating an existing record
            $existingRecord = AttendanceRequest::where('id', $id)
                ->first();

            if (!$existingRecord) {
                return ApiResponse::error(null, 'No existing attendance record found to update.');
            }

            // Update the existing record
            $existingRecord->update([
                'attendance_time' => $request->attendance_time,
                'attendance_date' => $request->attendance_date,
                'attendance_status' => $request->attendance_status,
                'note' => $request->note,
                'status' => 'Pending',
                'updated_by' => $userId,
                'updated_at' => now()
            ]);

            return ApiResponse::success($existingRecord, 'Attendance record updated successfully.');
        }
    }
    public function fetchTodayActivities(Request $request)
    {

        // Fetch the `userID` and `day` parameters from the request
        $userId = $request->input('userID');
        $day = $request->input('day');

        // If `userID` is not present, use the current logged-in user ID
        if (!$userId) {
            $currentUser = JWTUtils::getCurrentUserByUuid();
            $userId = $currentUser->id;
            $day = now()->format('Y-m-d');
        }
        // Retrieve the employee activities for the specific user and day
        $employeeActivities = EmployeeAttendance::where('employee_id', $userId)
            ->where('date', $day)
            ->select('check_in_time', 'check_out_time', 'session_type')
            ->get();

        // Process the activities to build the activity data
        $activityData = [];
        foreach ($employeeActivities as $activity) {
            if ($activity->session_type === 'regular') {
                $activityData[$activity->check_in_time] = 'check_in';
                if ($activity->check_out_time) {
                    $activityData[$activity->check_out_time] = 'check_out';
                }
            } elseif ($activity->session_type === 'break') {
                $activityData[$activity->check_in_time] = 'break_in';
                if ($activity->check_out_time) {
                    $activityData[$activity->check_out_time] = 'break_out';
                }
            }
        }

        // Sort the activity data by time
        ksort($activityData);



        // Return the response using the `ApiResponse::success` method
        return ApiResponse::success($activityData, ResMessages::RETRIEVED_SUCCESS);
    }
    public function fetchProgressData(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        $today = now()->toDateString();
        $startOfWeek = now()->startOfWeek()->toDateString();
        $endOfWeek = now()->endOfWeek()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $attendanceData = EmployeeAttendance::where('employee_id', $userId)
            ->where('session_type', 'regular')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $getTodayDateData = $attendanceData->filter(function ($attendance) use ($today) {
            return $attendance->date == $today;
        })->first();

        if ($getTodayDateData) {
            $checkInTime = $getTodayDateData->check_in_time;
            $parsedTimeString = Carbon::createFromTimeString($checkInTime, 'Asia/Kolkata');

            if ($getTodayDateData->check_out_time) {
                $checkOut = $getTodayDateData->check_out_time;
                $checkOutTime = Carbon::createFromTimeString($checkOut, 'Asia/Kolkata');
            } else {
                $checkOutTime = now()->setTimezone('Asia/Kolkata');
            }
            if ($checkInTime) {
                $todayTotalHours = $parsedTimeString->diffInHours($checkOutTime);
            } else {
                $todayTotalHours = 0;
            }
        } else {
            $todayTotalHours = 0;
        }
        $weekTotalHours = 0;
        $monthTotalHours = 0;

        foreach ($attendanceData as $attendance) {
            if ($attendance->check_in_time && $attendance->check_out_time) {
                $hoursWorked = Carbon::parse($attendance->check_in_time)->diffInHours(Carbon::parse($attendance->check_out_time));

                if ($attendance->date >= $startOfWeek && $attendance->date <= $endOfWeek) {
                    $weekTotalHours += $hoursWorked;
                }
                $monthTotalHours += $hoursWorked;
            }
        }

        $dailyWorkHours = 8.5;
        $weeklyWorkHours = $dailyWorkHours * 5;
        $monthlyWorkHours = $dailyWorkHours * 20;

        $todayPercentage = ($todayTotalHours / $dailyWorkHours) * 100;
        $weekPercentage = ($weekTotalHours / $weeklyWorkHours) * 100;
        $monthPercentage = ($monthTotalHours / $monthlyWorkHours) * 100;

        $ProgressData = [
            'today' => [
                'hours' => $todayTotalHours,
                'percentage' => $todayPercentage,
                'target' => $dailyWorkHours
            ],
            'week' => [
                'hours' => $weekTotalHours,
                'percentage' => $weekPercentage,
                'target' => $weeklyWorkHours
            ],
            'month' => [
                'hours' => $monthTotalHours,
                'percentage' => $monthPercentage,
                'target' => $monthlyWorkHours
            ],
            'remaining' => [
                'hours' => $monthTotalHours,
                'percentage' => $monthPercentage,
                'target' => $monthlyWorkHours
            ]
        ];

        return ApiResponse::success($ProgressData, ResMessages::RETRIEVED_SUCCESS);
    }
    public function fetchWorkingHoursData(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;
        $startOfWeek = now()->startOfWeek()->subDay()->addDay(); // Start of the week (Tuesday)
        $endOfWeek = now()->endOfWeek()->subDay()->addDay(); // End of the week (Monday)
        $weeklyHoursByDay = [];
        $daysOfWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $currentDay = $startOfWeek->copy()->addDays($i);
            $dayName = $currentDay->format('l');

            if ($currentDay->isWeekday()) {
                $daysOfWeek[] = $dayName;
                $weeklyHoursByDay[$dayName] = 0;
            }
        }

        $attendanceData = EmployeeAttendance::where('employee_id', $userId)
            ->where('session_type', 'regular')
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();


        foreach ($attendanceData as $attendance) {
            if ($attendance->check_in_time && $attendance->check_out_time) {
                $checkIn = Carbon::parse($attendance->date . ' ' . $attendance->check_in_time);

                $checkOutDate = $attendance->check_out_date ?? $attendance->date;
                $checkOut = Carbon::parse($checkOutDate . ' ' . $attendance->check_out_time);

                if ($checkOut > $checkIn) {
                    $hoursWorked = $checkIn->diffInHours($checkOut);
                } else {
                    $hoursWorked = 0;
                }
                $attendanceDate = Carbon::parse($attendance->date);
                if ($attendanceDate >= $startOfWeek && $attendanceDate <= $endOfWeek && !$attendanceDate->isFuture()) {

                    $dayKey = $attendanceDate->format('l');

                    if (isset($weeklyHoursByDay[$dayKey])) {
                        $weeklyHoursByDay[$dayKey] += $hoursWorked;
                    }
                }
            }
        }
        $workHoursData = array_values($weeklyHoursByDay);
        $datasets = [
            [
                'label' => 'Work Time',
                'backgroundColor' => "#56ca00",
                'data' => $workHoursData,
            ],
            [
                'label' => 'Remaining',
                'backgroundColor' => "#ff4c51",
                'data' => array_fill(0, count($workHoursData), 8.5),
            ],
        ];
        return ApiResponse::success(['datasets' => $datasets, 'labels' => $daysOfWeek], ResMessages::RETRIEVED_SUCCESS);
    }
    public function fetchLeavesData(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;
        $currentYear = $request->year;
        $nextYear = $request->year + 1;

        $companiesId = GetCompanyId::GetCompanyId();
        $financialYears = GetYear::getYear();

        if ($companiesId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        // Fetch leave types
        $leaveTypes = LeaveType::select('id', 'leave_type_name', 'max_days')
            ->where('is_currentYear', true)
            ->where('company_id', $companiesId)
            ->where('financialYear_id', $financialYears->id)
            ->get();

        // Fetch all pending leave requests
        $allPendingLeaveRequests = LeaveRequest::select('leave_type_id', 'employee_id')
            ->selectRaw('SUM(total_days) as total_days')
            ->where('employee_id', $userId)
            ->where('status', "Pending")
            ->where(function ($query) use ($currentYear, $nextYear) {
                $query->whereYear('start_date', $currentYear)
                    ->orWhereYear('end_date', $nextYear);
            })
            ->groupBy('leave_type_id', 'employee_id')
            ->get();

        // Fetch all approved leave requests
        $allApprovedLeaveRequests = LeaveRequest::select('leave_type_id', 'employee_id')
            ->selectRaw('SUM(total_days) as total_days')
            ->where('employee_id', $userId)
            ->where('status', "Approved")
            ->where(function ($query) use ($currentYear, $nextYear) {
                $query->whereYear('start_date', $currentYear)
                    ->orWhereYear('end_date', $nextYear);
            })
            ->groupBy('leave_type_id', 'employee_id')
            ->get();

        // Fetch remaining balance
        $remainingBalances = LeaveBalance::select(
            'leave_type_id',
            DB::raw('balance + carry_forwarded as total_balance')
        )
            ->where('employee_id', $userId)
            ->where('company_id', $companiesId)
            ->where('financialYear_id', $financialYears->id)
            ->get()
            ->keyBy('leave_type_id'); // Group by leave_type_id for easier lookup

        // Prepare the leave summary
        $leaveSummary = [];

        foreach ($leaveTypes as $leaveType) {
            $leaveTypeId = $leaveType->id;

            // Find matching pending and approved leave requests for this leave type
            $pendingLeave = $allPendingLeaveRequests->firstWhere('leave_type_id', $leaveTypeId);
            $approvedLeave = $allApprovedLeaveRequests->firstWhere('leave_type_id', $leaveTypeId);

            // Get the remaining balance for this leave type
            $remainingBalance = $remainingBalances->get($leaveTypeId);

            // Get the total days for pending and approved requests, default to 0 if not found
            $pendingDays = $pendingLeave ? $pendingLeave->total_days : 0;
            $approvedDays = $approvedLeave ? $approvedLeave->total_days : 0;
            $remainingDays = $remainingBalance ? $remainingBalance->total_balance : 0;

            // Store the summarized data in the leaveSummary array
            $leaveSummary[] = [
                'leave_type_name' => $leaveType->leave_type_name,
                'max_days' => $leaveType->max_days,
                'pending_days' => $pendingDays,
                'approved_days' => $approvedDays,
                'remaining_balance' => $remainingDays,
            ];
        }

        // Prepare response data
        $data = [
            'leaveTypes' => $leaveSummary,
        ];

        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function fetchLastCheckoutRecord(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        $initialRecodeData = EmployeeAttendance::where('employee_id', $userId)->first();

        if (is_null($initialRecodeData)) {

            $data = [
                'lastCheckoutRecord' => 1,
                'attendanceRequestRecord' => 1,
            ];

            return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
        }

        $hasAttendance = EmployeeAttendance::where('employee_id', $userId)->exists();

        if ($hasAttendance) {
            $getLastCheckoutRecord = EmployeeAttendance::where('employee_id', $userId)
                ->orderBy('check_out_time', 'desc')
                ->value('check_out_time');

            $getAttendanceRequestRecord = AttendanceRequest::where('employee_id', $userId)
                ->whereDate('created_at', Carbon::today())
                ->where('attendance_status', 'check_out')
                ->value('attendance_time');

            $data = [
                'lastCheckoutRecord' => $getLastCheckoutRecord,
                'attendanceRequestRecord' => $getAttendanceRequestRecord,
            ];
        }
        return ApiResponse::success($data, ResMessages::RETRIEVED_SUCCESS);
    }
    public function fetchUpcomingHolidaysData(Request $request)
    {
        $financialYears = GetYear::getYear();
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $currentDate = now();
        $endDate = $currentDate->copy()->addDays(15);

        $holidayQuery = DB::table('holidays')
            ->selectRaw("DATE_FORMAT(holiday_date, '%d/%m/%Y') as holiday_date, holiday_name")
            ->whereNull('deleted_at')
            ->whereBetween('holiday_date', [$currentDate, $endDate]);

        if ($CompanyId) {
            $holidayQuery->where('holidays.company_id', $CompanyId);
        }

        if ($financialYears && isset($financialYears->id)) {
            $holidayQuery->where('holidays.financialYear_id', $financialYears->id);
        }

        $holidays = $holidayQuery->orderBy('holidays.holiday_date', 'asc')->get();

        return ApiResponse::success($holidays, ResMessages::RETRIEVED_SUCCESS);
    }
}
