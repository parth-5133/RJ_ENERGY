<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'aadhaar_card',
        'pan_card',
        'electricity_bill',
        'bank_proof',
        'passport_photo',
        'ownership_proof',
        'site_photo',
        'self_declaration'
    ];
}
