<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerBankDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'bank_id',
        'bank_branch',
        'account_number',
        'ifsc_code',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
