<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subsidy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'subsidy_amount',
        'subsidy_status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
