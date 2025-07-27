<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'leave_type_id',
        'financialYear_id',
        'balance',
        'carry_forwarded',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
