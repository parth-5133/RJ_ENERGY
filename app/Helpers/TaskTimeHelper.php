<?php

namespace App\Helpers;

use App\Models\TaskTimeLog;

class TaskTimeHelper
{
    public static function calculateTotalTaskTime($taskId)
    {
        $latestManualLog = TaskTimeLog::where('task_id', $taskId)
            ->where('is_manual', 1)
            ->orderByDesc('id')
            ->first();

        if ($latestManualLog) {
            $totalSeconds = $latestManualLog->duration_seconds ?? 0;
        } else {
            $totalSeconds = TaskTimeLog::where('task_id', $taskId)
                ->sum('duration_seconds');
        }

        if ($totalSeconds === 0) {
            return '00:00:00';
        }

        return gmdate("H:i:s", $totalSeconds ?? 0);
    }
}
