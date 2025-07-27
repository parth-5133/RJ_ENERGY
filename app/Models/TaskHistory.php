<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    use HasFactory;

    protected $table = 'task_historys';

    protected $fillable = [
        'task_id',
        'changed_by',
        'field_changed',
        'old_value',
        'new_value',
        'change_date',
        'created_at',
        'updated_at'
    ];
}
