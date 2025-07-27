<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAddress extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'contact_name',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'country',
        'state',
        'city',
        'pin_code',
        'mobile_no',
        'alternate_mobile_no',
        'residing_from',
        'area',
        'landmark',
        'latitude',
        'longitude',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
