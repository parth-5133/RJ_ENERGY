<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolarLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'bank_name',
        'bank_branch',
        'account_number',
        'ifsc_code',
        'loan_mode'
    ];
}
