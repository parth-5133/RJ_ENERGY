<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'legal_name',
        'phone',
        'email',
        'gst_number',
        'pan_number',
        'logo_url',
        'logo_display_name',
        'website',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'country',
        'state',
        'city',
        'pin_code',
        'alternate_mobile_no',
        'is_active',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
