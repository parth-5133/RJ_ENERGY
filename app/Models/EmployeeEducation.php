<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    use HasFactory;

    protected $table = 'employee_educations';


    protected $fillable = [
        'user_id',
        'highest_degree',
        'institution_name',
        'field_of_study',
        'year_of_graduation',
        'certifications',
        'skills',
        'is_active',
        'created_by',
        'updated_by',
    ];
}
