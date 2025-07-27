<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeInfo extends Model
{
    use HasFactory;

    protected $table = 'employee_infos';

    protected $fillable = [
        'user_id',
        'profile_image',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'marital_status_id',
        'nationality_id',
        'citizenship',
        'disability_status',
        'personal_email',
        'phone_number',
        'alternate_phone_number',
        'emergency_phone_number',
        'religion',
        'blood_group',
        'aadhaar_number',
        'pan_number',
    ];
}
