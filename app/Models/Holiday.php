<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Holiday extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'holiday_name',
        'holiday_date',
        'day',
        'financialYear_id',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
