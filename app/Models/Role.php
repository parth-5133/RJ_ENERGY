<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'company_id',
        'code',
        'access_level',
        'is_active',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($role) {
            if (isset($role->code)) {
                $role->code = strtoupper($role->code);
            }
        });
    }
}
