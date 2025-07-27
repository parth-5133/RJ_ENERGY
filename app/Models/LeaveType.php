<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'leave_type_name',
        'max_days',
        'carry_forward_max_balance',
        'expiry_date',
        'is_currentYear',
        'financialYear_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];
}
