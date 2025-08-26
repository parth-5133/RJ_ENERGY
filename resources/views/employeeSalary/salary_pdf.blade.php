<style>
    .salary-container {
        font-family: Arial, sans-serif;
        font-size: 14px;
    }

    .section-table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 10px;
    }

    .section-table th,
    .section-table td {
        padding: 6px;
        border: 1px solid #666565;
    }

    .border-none {
        padding: 6px;
        border: none !important;
    }

    .header {
        background-color:#29a8df;
        color: white;
        text-align: center;
        font-weight: bold;
    }

    .sub-header {
        background-color: #ccc;
        font-weight: bold;
        text-align: center;
    }

    .net-salary {
        background-color: #cfe2f3;
        font-weight: bold;
    }

    .bold {
        font-weight: bold;
    }

    .tables-row {
        width: 100%;
        display: table;
        table-layout: fixed;
        /* Add gap between table rows */
    }

    .table-box {
        width: 49%;
        display: table-cell;
        vertical-align: top;
    }

    .subsequent-table {
        border: none !important;
    }

    .no-border-left {
        border-left: none !important;
    }

    .section-table th:first-child,
    .section-table td:first-child {
        width: 60%;
    }

    .section-table th:last-child,
    .section-table td:last-child {
        width: 40%;
    }

    .section-table.equal-columns th,
    .section-table.equal-columns td {
        width: 50%;
    }
</style>

<div class="salary-container">
    <table class="section-table equal-columns border-none">
        <tr class="border-none">
            <td colspan="4" class="header border-none">
                SALARY SLIP - {{ $employeeSalary->month_name }} - {{ $employeeSalary->salary_year }}
            </td>
        </tr>
        <tr class="border-none">
            <td class="bold border-none">Emp. Name</td>
            <td class="border-none">
                {{ $employee->first_name }} {{ $employee->last_name }}
            </td>
            <td class="bold border-none">Designation</td>
            <td class="border-none">Employee</td>
        </tr>
        <tr class="border-none">
            <td class="bold border-none">Month</td>
            <td class="border-none">{{ $employeeSalary->month_name }}</td>
            <td class="bold border-none">Year</td>
            <td class="border-none">{{ $employeeSalary->salary_year }}</td>
        </tr>
        <tr class="border-none">
            <td class="bold border-none">Per Month CTC</td>
            <td class="border-none">RS. {{ $PerMonthCTC }}</td>
            <td class="bold border-none">Date</td>
            <td class="border-none">
                {{ $employeeSalary->created_at->format('d-M-Y') }}
            </td>
        </tr>
    </table>

    <!-- Tables Side-by-Side -->
    <div class="tables-row">
        <!-- Earnings Table -->
        <div class="table-box">
            <table class="section-table equal-columns">
                <tr>
                    <th colspan="2" class="sub-header">Earnings</th>
                </tr>
                @foreach ($allowances as $allowance)
                    <tr>
                        <td>{{ $allowance->allowances_name }}</td>
                        <td style="text-align: right">
                            RS. {{ $allowance->amount }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>Salary</td>
                    <td style="text-align: right">
                        RS. {{ $employeeSalary->basic_salary }}
                    </td>
                </tr>
                <tr>
                    <td>Working Days</td>
                    <td style="text-align: right">{{ $WorkingDays }}</td>
                </tr>
                <tr>
                    <td>Total Leave</td>
                    <td style="text-align: right">{{ $TotalLeave }}</td>
                </tr>
                <tr>
                    <td>Total Attendance</td>
                    <td style="text-align: right">{{ $TotalAttendance }}</td>
                </tr>
                <tr>
                    <td>Final Payable Days</td>
                    <td style="text-align: right">{{ $FinalPayableDays }}</td>
                </tr>
                @for ($i = 0; $i < $emptyAllowanceRows; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </table>
        </div>

        <!-- Deductions Table -->
        <div class="table-box">
            <table class="section-table equal-columns">
                <tr>
                    <th colspan="2" class="sub-header">Deductions</th>
                </tr>
                @foreach ($deductions as $deduction)
                    <tr>
                        <td>{{ $deduction->deduction_name }}</td>
                        <td style="text-align: right">
                            RS. {{ $deduction->amount }}
                        </td>
                    </tr>
                    @endforeach @for ($i = 0; $i < $emptyDeductionRows; $i++)
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
            </table>
        </div>
    </div>

    <div class="tables-row">
        <div class="table-box">
            <!-- Net Salary Summary -->
            <table class="section-table subsequent-table equal-columns">
                <tr>
                    <td class="bold">Total Payable Amount</td>
                    <td style="text-align: right">
                        RS. {{ $employeeSalary->basic_salary }}
                    </td>
                </tr>
                <tr>
                    <td class="bold">Total Addition</td>
                    <td style="text-align: right">RS. {{ $TotalAddition }}</td>
                </tr>
            </table>
        </div>

        <div class="table-box">
            <table class="section-table equal-columns">
                <tr>
                    <td class="bold no-border-left">Total Deduction</td>
                    <td style="text-align: right">RS. {{ $TotalDeduction }}</td>
                </tr>
                <tr>
                    <td class="bold no-border-left net-salary">Net Salary</td>
                    <td style="text-align: right" class="net-salary">RS. {{ $NetSalary }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Net Salary Summary -->
    <table class="section-table equal-columns" style="margin-top: -1px">
        <tr>
            <td colspan="4" style="height: 1px"></td>
        </tr>
        <tr>
            <td colspan="2" style="height: 60px"></td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center">
                Signature of Employee
            </td>
            <td colspan="2" style="text-align: center">
                Signature of Employer
            </td>
        </tr>
    </table>

    <div style="font-size: 12px;margin-top: 0px;position: absolute;bottom: 0px;left: 20px;">
        This is system generated salary slip
    </div>
    <div style="font-size: 12px;margin-top: 0px;position: absolute;bottom: 0px;right: 20px;">
        Printed Date: {{ date('d-m-Y') }}
    </div>
</div>
