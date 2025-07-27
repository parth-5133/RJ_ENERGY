<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'employee_id',
        'date',
        'note',
        'check_in_time',
        'check_out_time',
        'check_out_date',
        'session_type',
        'status',
        'latitude',
        'longitude',
        'accuracy',
    ];
}
