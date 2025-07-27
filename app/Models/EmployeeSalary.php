<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeSalary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'financialYear_id',
        'employee_id',
        'department_id',
        'basic_salary',
        'total_allowances',
        'total_deductions',
        'total_salary',
        'salary_month',
        'salary_year',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
