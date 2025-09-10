<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeResignation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee_resignations';

    protected $fillable = [
        'company_id',
        'employee_id',
        'resignation_date',
        'reason',
        'status',
        'last_working_date',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
