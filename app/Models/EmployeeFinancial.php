<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFinancial extends Model
{
    use HasFactory;

    protected $table = 'employee_financials';


    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'bank_name',
        'account_name',
        'account_number',
        'ifsc_code',
        'swift_code',
        'base_salary',
        'bonus_eligibility',
        'pay_grade',
        'currency',
        'payment_mode',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
