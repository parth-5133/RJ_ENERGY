<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\KanbanColumn;

class TaskStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $oldStatus;
    public $newStatus;
    public $appUrl;

    public function __construct(Task $task, $oldStatusId, $newStatusId)
    {
        $this->task = $task;

        $this->oldStatus = KanbanColumn::where('position', $oldStatusId)
            ->where('project_id', $task->project_id)
            ->value('column_name') ?? 'Unknown';

        $this->newStatus = KanbanColumn::where('position', $newStatusId)
            ->where('project_id', $task->project_id)
            ->value('column_name') ?? 'Unknown';

        $this->appUrl = config('app.url');
    }

    public function build()
    {
        return $this->subject('Task Status Changed')
            ->view('emails.task-status-changed')
            ->with([
                'task' => $this->task,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'appUrl' => $this->appUrl,
                'changedAt' => now()->format('d/m/Y'),
            ]);
    }
}
