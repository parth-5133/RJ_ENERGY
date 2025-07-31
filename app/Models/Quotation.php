<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory , SoftDeletes;

    public $timestamps = false; // only using created_at

    protected $fillable = [
        'customer_id',
        'required',
        'amount',
        'date',
        'by',
        'status',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
}
