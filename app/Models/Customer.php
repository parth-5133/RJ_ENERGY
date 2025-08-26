<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_number',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'pan_number',
        'aadhar_number',
        'age',
        'gender',
        'marital_status',
        'mobile',
        'alternate_mobile',
        'PerAdd_pin_code',
        'PerAdd_city',
        'district',
        'PerAdd_state',
        'customer_address',
        'customer_residential_address',
        'assign_to',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
