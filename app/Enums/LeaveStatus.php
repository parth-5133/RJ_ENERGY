<?php

namespace App\Enums;

enum LeaveStatus: int
{
    case APPROVED = 1;
    case REJECTED = 2;
    case CANCELLED = 3;
    case PENDING = 4;

    public function label(): string
    {
        return match($this) {
            LeaveStatus::APPROVED => 'Approved',
            LeaveStatus::REJECTED => 'Rejected',
            LeaveStatus::CANCELLED => 'Cancelled',
            LeaveStatus::PENDING => 'Pending',
        };
    }
}
