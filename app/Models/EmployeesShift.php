<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeesShift extends Model
{
    use HasFactory;

    protected $table = 'employees_shift';

    protected $fillable = [
        'company_id',
        'shift_name',
        'from_time',
        'to_time',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
