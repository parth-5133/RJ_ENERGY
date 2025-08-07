<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanBankDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'solar_detail_id',
        'bank_name',
        'bank_branch',
        'account_number',
        'ifsc_code',
        'branch_manager_phone',
        'loan_manager_phone',
        'loan_status',
        'loan_sanction_date',
        'loan_disbursed_date',
        'managed_by',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
