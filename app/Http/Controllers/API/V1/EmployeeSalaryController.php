<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateSalaryRequest;
use App\Models\User;
use App\Models\EmployeeSalary;
use App\Models\LeaveRequest;
use App\Helpers\ApiResponse;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use App\Helpers\FinancialYearService;
use App\Constants\ResMessages;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helpers\GetYear;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use App\Models\Company;

class EmployeeSalaryController extends Controller
{
    public function salaryIndex(Request $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $financialYears = GetYear::getYear();
        $companiesId = GetCompanyId::GetCompanyId();

        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        if ($role_code == $this->AdminRoleCode || $role_code == $this->clientRoleCode) {

            $employeeSalaries = DB::table('employee_salaries')
                ->leftJoin('users as updater', 'employee_salaries.updated_by', '=', 'updater.id')
                ->leftJoin('users as employee', 'employee_salaries.employee_id', '=', 'employee.id')
                ->leftJoin('users as sequence', 'employee_salaries.department_id', '=', 'sequence.id')
                ->leftJoin('departments', 'employee_salaries.department_id', '=', 'departments.id')
                ->whereNull('employee_salaries.deleted_at')
                ->where('employee_salaries.company_id', $companiesId)
                ->where('employee_salaries.financialYear_id', $financialYears->id)
                ->select(
                    'employee_salaries.*',
                    DB::raw("CONCAT(updater.first_name, ' ', updater.last_name) as updated_name"),
                    DB::raw("CONCAT(employee.first_name, ' ', employee.last_name) as employee_name"),
                    'employee.employee_id',
                    'departments.name as department_name',
                    DB::raw("CASE employee_salaries.salary_month
                    WHEN '01' THEN 'January'
                    WHEN '02' THEN 'February'
                    WHEN '03' THEN 'March'
                    WHEN '04' THEN 'April'
                    WHEN '05' THEN 'May'
                    WHEN '06' THEN 'June'
                    WHEN '07' THEN 'July'
                    WHEN '08' THEN 'August'
                    WHEN '09' THEN 'September'
                    WHEN '10' THEN 'October'
                    WHEN '11' THEN 'November'
                    WHEN '12' THEN 'December'
                    ELSE employee_salaries.salary_month
                END as month_name"),
                    DB::raw("DATE_FORMAT(employee_salaries.updated_at, '%d/%m/%Y') as updated_at_formatted")
                )
                ->orderByDesc('employee_salaries.id')
                ->get();
        } else {

            $employeeSalaries = DB::table('employee_salaries')
                ->leftJoin('users as updater', 'employee_salaries.updated_by', '=', 'updater.id')
                ->leftJoin('users as employee', 'employee_salaries.employee_id', '=', 'employee.id')
                ->leftJoin('departments', 'employee_salaries.department_id', '=', 'departments.id')
                ->leftJoin('users as sequence', 'employee_salaries.department_id', '=', 'sequence.id')
                ->whereNull('employee_salaries.deleted_at')
                ->where('employee_salaries.financialYear_id', $financialYears->id)
                ->where('employee_salaries.employee_id', $currentUser->id)
                ->select(
                    'employee_salaries.*',
                    DB::raw("CONCAT(updater.first_name, ' ', updater.last_name) as updated_name"),
                    DB::raw("CONCAT(employee.first_name, ' ', employee.last_name) as employee_name"),
                    'employee.employee_id',
                    'departments.name as department_name',
                    DB::raw("CASE employee_salaries.salary_month
                    WHEN '01' THEN 'January'
                    WHEN '02' THEN 'February'
                    WHEN '03' THEN 'March'
                    WHEN '04' THEN 'April'
                    WHEN '05' THEN 'May'
                    WHEN '06' THEN 'June'
                    WHEN '07' THEN 'July'
                    WHEN '08' THEN 'August'
                    WHEN '09' THEN 'September'
                    WHEN '10' THEN 'October'
                    WHEN '11' THEN 'November'
                    WHEN '12' THEN 'December'
                    ELSE employee_salaries.salary_month
                END as month_name"),
                    DB::raw("DATE_FORMAT(employee_salaries.updated_at, '%d/%m/%Y') as updated_at_formatted")
                )
                ->orderByDesc('employee_salaries.id')
                ->get();
        }

        return ApiResponse::success($employeeSalaries, ResMessages::RETRIEVED_SUCCESS);
    }
    public function salaryStore(StoreUpdateSalaryRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $financialYears = FinancialYearService::getCurrentFinancialYear();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        try {
            DB::beginTransaction();

            $data = $request->validated();

            $employeeSalary = EmployeeSalary::create([
                'employee_id' => $data['employee'],
                'department_id' => $data['department'],
                'basic_salary' => $data['basic_salary'],
                'total_allowances' => $data['total_allowances'],
                'total_deductions' => $data['total_deductions'],
                'total_salary' => $data['total_salary'],
                'salary_month' => $data['salary_month'],
                'salary_year' => $data['salary_year'],
                'created_by' => $currentUser->id,
                'financialYear_id' => $financialYears->id,
                'company_id' => $CompanyId,
                'created_at' => now(),
                'updated_at' => null,
            ]);

            // Insert into salary_allowance_mapping
            if (!empty($data['allowances'])) {
                foreach ($data['allowances'] as $allowance) {
                    $allowanceId = intval(str_replace('allowances_', '', $allowance['name']));
                    DB::table('salary_allowance_mapping')->insert([
                        'employee_salary_id' => $employeeSalary->id,
                        'allowance_id' => $allowanceId,
                        'amount' => $allowance['value'],
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                }
            }

            // Insert into salary_deduction_mapping
            if (!empty($data['deductions'])) {
                foreach ($data['deductions'] as $deduction) {
                    $deductionId = intval(str_replace('deductions_', '', $deduction['name']));
                    DB::table('salary_deduction_mapping')->insert([
                        'employee_salary_id' => $employeeSalary->id,
                        'deduction_id' => $deductionId,
                        'amount' => $deduction['value'],
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                }
            }

            DB::commit();

            return ApiResponse::success(null, ResMessages::CREATED_SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage());
        }
    }
    public function salaryView(Request $request)
    {
        $id = $request->id;

        $EmployeeSalary = EmployeeSalary::find($id);

        if ($EmployeeSalary) {

            $EmployeeSalary->allowances = DB::table('salary_allowance_mapping')
                ->where('employee_salary_id', $EmployeeSalary->id)
                ->select(
                    DB::raw("CONCAT('allowance_',allowance_id) as allowance_id"),
                    'amount'
                )
                ->get();
            $EmployeeSalary->deductions = DB::table('salary_deduction_mapping')
                ->where('employee_salary_id', $EmployeeSalary->id)
                ->select(
                    DB::raw("CONCAT('deduction_',deduction_id) as deduction_id"),
                    'amount'
                )
                ->get();

            return ApiResponse::success($EmployeeSalary, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($EmployeeSalary, ResMessages::NOT_FOUND);
        }

        if ($employeeSalaries) {
            return ApiResponse::success($employeeSalaries, ResMessages::RETRIEVED_SUCCESS);
        } else {
            return ApiResponse::error($employeeSalaries, ResMessages::NOT_FOUND);
        }
    }
    public function salaryUpdate(StoreUpdateSalaryRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $financialYears = FinancialYearService::getCurrentFinancialYear();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        try {
            DB::beginTransaction();
            $data = $request->validated();

            $employeeSalary = EmployeeSalary::find($data['EMployeeRoleId']);
            if (!$employeeSalary) {
                DB::rollBack();
                return ApiResponse::error(ResMessages::NOT_FOUND, 404);
            }

            $employeeSalary->update([
                'employee_id' => $data['employee'],
                'department_id' => $data['department'],
                'basic_salary' => $data['basic_salary'],
                'total_allowances' => $data['total_allowances'],
                'total_deductions' => $data['total_deductions'],
                'total_salary' => $data['total_salary'],
                'salary_month' => $data['salary_month'],
                'salary_year' => $data['salary_year'],
                'updated_by' => $currentUser->id,
                'financialYear_id' => $financialYears->id,
                'company_id' => $CompanyId,
                'updated_at' => now(),
            ]);

            DB::table('salary_allowance_mapping')->where('employee_salary_id', $data['EMployeeRoleId'])->delete();
            if (!empty($data['allowances'])) {
                foreach ($data['allowances'] as $allowance) {
                    $allowanceId = intval(str_replace('allowances_', '', $allowance['name']));
                    DB::table('salary_allowance_mapping')->insert([
                        'employee_salary_id' => $data['EMployeeRoleId'],
                        'allowance_id' => $allowanceId,
                        'amount' => $allowance['value'],
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                }
            }

            DB::table('salary_deduction_mapping')->where('employee_salary_id', $data['EMployeeRoleId'])->delete();
            if (!empty($data['deductions'])) {
                foreach ($data['deductions'] as $deduction) {
                    $deductionId = intval(str_replace('deductions_', '', $deduction['name']));
                    DB::table('salary_deduction_mapping')->insert([
                        'employee_salary_id' => $data['EMployeeRoleId'],
                        'deduction_id' => $deductionId,
                        'amount' => $deduction['value'],
                        'created_at' => now(),
                        'updated_at' => null,
                    ]);
                }
            }
            DB::commit();
            return ApiResponse::success($employeeSalary, ResMessages::UPDATED_SUCCESS);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), 500); // Return proper error code
        }
    }
    public function salaryDelete($id)
    {
        $holidays = EmployeeSalary::find($id);

        if ($holidays) {
            $holidays->delete();
            DB::table('salary_allowance_mapping')->where('employee_salary_id', $id)->delete();
            DB::table('salary_deduction_mapping')->where('employee_salary_id', $id)->delete();
            return ApiResponse::success($holidays, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error($holidays, ResMessages::NOT_FOUND);
        }
    }
    public function downloadSalarySlip(Request $request)
    {
        $id = $request->id;
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $company = Company::find($CompanyId);
        if (!$company) {
            return ApiResponse::error('Company not found', 404);
        }

        $employeeSalary = EmployeeSalary::where('employee_salaries.id', $id)
            ->leftJoin('departments', 'employee_salaries.department_id', '=', 'departments.id')
            ->select(
                'employee_salaries.*',
                'departments.name as department_name',
            )
            ->first();

        if (!$employeeSalary) {
            return ApiResponse::error('Salary not found', 404);
        }

        $employee = User::find($employeeSalary->employee_id);
        if (!$employee) {
            return ApiResponse::error('Employee not found', 404);
        }

        $monthNames = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];
        $employeeSalary->month_name = $monthNames[$employeeSalary->salary_month] ?? $employeeSalary->salary_month;

        $employeeSalaryMonth = $employeeSalary->salary_month;
        $employeeSalaryYear = $employeeSalary->salary_year;

        $monthStart = Carbon::create($employeeSalaryYear, $employeeSalaryMonth, 1)->startOfDay();
        $monthEnd = $monthStart->copy()->endOfMonth();

        $leaves = LeaveRequest::where('employee_id', $employeeSalary->employee_id)
            ->where('status', 'Approved')
            ->where(function ($query) use ($monthStart, $monthEnd) {
                $query->whereBetween('start_date', [$monthStart, $monthEnd])
                    ->orWhereBetween('end_date', [$monthStart, $monthEnd])
                    ->orWhere(function ($q) use ($monthStart, $monthEnd) {
                        $q->where('start_date', '<', $monthStart)
                            ->where('end_date', '>', $monthEnd);
                    });
            })
            ->get();

        $TotalLeave = 0;

        foreach ($leaves as $leave) {
            $leaveStart = Carbon::parse($leave->start_date)->startOfDay();
            $leaveEnd = Carbon::parse($leave->end_date)->endOfDay();

            // Get the overlapping period between leave and month
            $periodStart = $leaveStart->greaterThan($monthStart) ? $leaveStart : $monthStart;
            $periodEnd = $leaveEnd->lessThan($monthEnd) ? $leaveEnd : $monthEnd;

            // Count only weekdays
            $daysInMonth = 0;
            foreach (CarbonPeriod::create($periodStart, $periodEnd) as $date) {
                if (!$date->isWeekend()) { // skip Saturday and Sunday
                    $daysInMonth++;
                }
            }

            $TotalLeave += $daysInMonth; // Just add to the total
        }

        $WorkingDays =  CarbonPeriod::create($monthStart, $monthEnd)->filter(function ($date) {
            return !$date->isWeekend();
        })->count();

        $TotalAttendance = $WorkingDays - $TotalLeave;

        $FinalPayableDays = $WorkingDays - $TotalLeave;

        // $TotalPayableAmount = $employeeSalary->basic_salary / $WorkingDays * $FinalPayableDays;
        $TotalPayableAmount = $employeeSalary->total_salary;

        $TotalAddition = $employeeSalary->total_allowances;
        $TotalDeduction = $employeeSalary->total_deductions;
        $NetSalary = $employeeSalary->total_salary;
        $PerMonthCTC = $employeeSalary->basic_salary;

        $deductions = DB::table('salary_deduction_mapping')
            ->where('employee_salary_id', $id)
            ->join('employee_deductions', 'salary_deduction_mapping.deduction_id', '=', 'employee_deductions.id')
            ->select('employee_deductions.deduction_name', 'salary_deduction_mapping.amount')
            ->get();

        $allowances = DB::table('salary_allowance_mapping')
            ->where('employee_salary_id', $id)
            ->join('employee_allowances', 'salary_allowance_mapping.allowance_id', '=', 'employee_allowances.id')
            ->select('employee_allowances.allowances_name', 'salary_allowance_mapping.amount')
            ->get();

        $earningFixedRows = 5;

        $earningRows = count($allowances) + $earningFixedRows;
        $deductionRows = count($deductions);

        $maxRows = max($earningRows, $deductionRows);

        $emptyAllowanceRows = $maxRows - $earningRows;
        $emptyDeductionRows = $maxRows - $deductionRows;

        $data = [
            'company' => $company,
            'employee' => $employee,
            'employeeSalary' => $employeeSalary,
            'deductions' => $deductions,
            'TotalLeave' => $TotalLeave,
            'WorkingDays' => $WorkingDays,
            'TotalAttendance' => $TotalAttendance,
            'FinalPayableDays' => $FinalPayableDays,
            'TotalPayableAmount' => $TotalPayableAmount,
            'TotalAddition' => $TotalAddition,
            'TotalDeduction' => $TotalDeduction,
            'NetSalary' => $NetSalary,
            'PerMonthCTC' => $PerMonthCTC,
            'allowances' => $allowances,
            'emptyAllowanceRows' => $emptyAllowanceRows,
            'emptyDeductionRows' => $emptyDeductionRows,
        ];

        $pdf = PDF::loadView('employeeSalary.salary_pdf', $data);

        $directoryPath = storage_path('app/public/salary-slips');
        if (!File::exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true);
        }

        $filename = "salary-slip-{$employee->first_name}-{$employee->last_name}-{$employeeSalary->month_name}-{$employeeSalary->salary_year}.pdf";

        $filePath = $directoryPath . "/{$filename}";

        // Save PDF
        $pdf->save($filePath);

        // Return public URL
        $fileUrl = asset("storage/salary-slips/{$filename}");

        return ApiResponse::success($fileUrl, 'Salary slip generated successfully');
    }
}
