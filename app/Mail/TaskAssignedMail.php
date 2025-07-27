<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $projectName;
    public $createdBy;
    public $appUrl;
    public $assignedUser;

    public function __construct($task, $projectName, $createdBy, $assignedUser)
    {
        $this->task = $task;
        $this->projectName = $projectName;
        $this->createdBy = $createdBy;
        $this->assignedUser = $assignedUser;
        $this->appUrl = config('app.url');
    }

    public function build()
    {
        return $this->subject('New Task Assigned: ' . $this->task->title)
            ->markdown('emails.task_assigned')
            ->with([
                'task' => $this->task,
                'projectName' => $this->projectName,
                'createdBy' => $this->createdBy,
                'assignedUser' => $this->assignedUser,
                'appUrl' => $this->appUrl,
            ]);
    }
}
