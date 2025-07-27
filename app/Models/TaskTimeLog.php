<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTimeLog extends Model
{
    use HasFactory;

    protected $table = 'task_time_logs';

    protected $fillable = [
        'task_id',
        'from_column_id',
        'to_column_id',
        'moved_by',
        'entered_start_time',
        'entered_end_time',
        'duration_seconds',
        'is_manual',
        'moved_at'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
