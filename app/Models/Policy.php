<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'policy_name',
        'policy_description',
        'effective_date',
        'expiration_date',
        'issued_by',
        'is_active',
        'display_to_employee',
        'display_to_client',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
