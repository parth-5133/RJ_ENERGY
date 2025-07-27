<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'shift_name',
        'from_time',
        'to_time',
        'is_active',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
    protected $table = 'employees_shift';
}
