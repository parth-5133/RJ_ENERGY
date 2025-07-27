<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'task_id',
        'user_id',
        'title',
        'due_date',
        'start_time',
        'end_time',
        'project_id',
        'status',
        'priority',
        'description',
        'deleted_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
