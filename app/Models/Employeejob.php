<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employeejob extends Model
{
    use HasFactory;

    protected $table = 'employee_jobs';


    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'job_title',
        'department',
        'location',
        'employee_type',
        'date_of_joining',
        'employee_status',
        'reporting_id',
        'designation',
        'work_schedule',
        'job_description',
        'created_by',
        'updated_by'
    ];
}
