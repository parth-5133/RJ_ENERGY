<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeVehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee_vehicles';

    protected $fillable = [
        'user_id',
        'vehicle_make',
        'vehicle_model',
        'vehicle_type',
        'vehicle_number',
        'driving_license_no',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
