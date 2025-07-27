<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'from_date',
        'to_date',
        'display_year',
        'is_currentYear',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
