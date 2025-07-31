<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_name',
        'age',
        'mobile',
        'alternate_mobile',
        'aadhar',
        'pan',
        'address',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
