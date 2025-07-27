<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeExperience extends Model
{
    use HasFactory;

    protected $table = 'employee_experiences';


    // Specify the attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'organization_name',
        'from_date',
        'to_date',
        'designation',
        'country',
        'state',
        'city',
        'experience_letter',
        'file_display_name',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    
}
