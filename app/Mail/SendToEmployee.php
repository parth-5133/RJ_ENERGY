<?php

namespace App\Mail;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\LeaveType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Enums\LeaveSession;

class SendToEmployee extends Mailable
{
    use Queueable, SerializesModels;

    public $leaveRequest;
    public $employeeName;
    public $leaveTypeName;

    public $leaveSessionName;
    public $appUrl;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
        $employeeData = User::where('id', $leaveRequest->employee_id)
            ->select('employee_id')
            ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name")
            ->first();
        $this->employeeName = $employeeData->full_name;
        $this->leaveTypeName = LeaveType::where('id', $leaveRequest->leave_type_id)->value('leave_type_name');
        $this->leaveSessionName = LeaveSession::from($leaveRequest->leave_session_id)->label();
        $this->appUrl = config('app.url');
    }

    public function build()
    {
        return $this->subject('New Leave Request Submitted')
            ->markdown('emails.leaves.employee')
            ->with([
                'leaveRequest' => $this->leaveRequest,
                'employeeName' => $this->employeeName,
                'leaveTypeName' => $this->leaveTypeName,
                'leaveSessionName' => $this->leaveSessionName,
                'appUrl' => $this->appUrl
            ]);
    }
}
