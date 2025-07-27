<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'title',
        'message',
        'read',
        'has_view_button',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
