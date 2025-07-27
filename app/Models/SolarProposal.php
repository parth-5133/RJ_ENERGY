<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'application_id',
        'solar_capacity',
        'roof_type',
        'roof_area',
        'usage_pattern',
        'net_metering',
        'subsidy_claimed',
        'purchase_mode',
        'loan_required'
    ];
}
