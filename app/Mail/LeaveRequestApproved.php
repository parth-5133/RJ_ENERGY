<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\LeaveType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Enums\LeaveSession;
use App\Enums\LeaveStatus;

class LeaveRequestApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;
    public $employeeName;
    public $leaveTypeName;
    public $leaveSessionName;
    public $leaveStatus;
    public $appUrl;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
        $this->employeeName = User::where('id', $leaveRequest->employee_id)->value('first_name');
        $this->leaveTypeName = LeaveType::where('id', $leaveRequest->leave_type_id)->value('leave_type_name');
        $this->leaveSessionName = LeaveSession::from($leaveRequest->leave_session_id)->label();
        $this->leaveStatus = LeaveStatus::from($leaveRequest->status)->label();
        $this->appUrl = config('app.url');
    }

    public function build()
    {
        return $this->subject('Your Leave Request Status Update')
            ->markdown('emails.leaves.approved')
            ->with([
                'leaveRequest' => $this->leaveRequest,
                'employeeName' => $this->employeeName,
                'leaveTypeName' => $this->leaveTypeName,
                'leaveSessionName' => $this->leaveSessionName,
                'leaveStatus' => $this->leaveStatus,
                'appUrl' => $this->appUrl
            ]);
    }
}
