<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChannelPartner extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'channel_partners';

    protected $fillable = [
        'legal_name',
        'logo_url',
        'commission',
        'phone',
        'email',
        'gst_number',
        'pan_number',
        'city',
        'pin_code',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
