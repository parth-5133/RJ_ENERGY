<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolarDetail extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'customer_id',
        'roof_type',
        'roof_area',
        'usage_pattern',
        'capacity',
        'solar_company',
        'inverter_company',
        'subsidy_claimed',
        'jan_samarth_id',
        'acknowledge_no',
        'loan_required',
        'payment_mode',
        'cancel_cheque',
        'light_bill',
        'consumer_no',
        'application_ref_no',
        'channel_partner_id',
        'registration_date',
        'solar_total_amount',
        'subsidy_amount',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
