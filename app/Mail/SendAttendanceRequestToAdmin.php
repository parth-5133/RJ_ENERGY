<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SendAttendanceRequestToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $employeeName;
    public $employeeId;

    public function __construct($data)
    {
        $this->data = $data;
        
        $employeeData = User::where('id', $data->employee_id)
            ->select('employee_id')
            ->selectRaw("CONCAT(first_name, ' ', last_name) as full_name")
            ->first();

        $this->employeeName = $employeeData->full_name;
        $this->employeeId = $employeeData->employee_id;
    }

    public function build()
    {
        $appUrl = config('app.url');

        return $this->subject('New Attendance Request Submitted')
            ->view('emails.attendance_request')
            ->with([
                'employeeName' => $this->employeeName,
                'employeeId' => $this->employeeId,
                'note' => $this->data->note,
                'appUrl' => $appUrl,
            ]);
    }
}
