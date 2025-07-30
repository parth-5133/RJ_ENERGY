<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use HasFactory , SoftDeletes;

    protected $table = 'banks';

    protected $fillable = [
        'bank_name',
        'branch_name',
        'branch_manager_phone',
        'loan_manager_phone',
        'ifsc_code',
        'address',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
