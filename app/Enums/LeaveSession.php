<?php

namespace App\Enums;

enum LeaveSession: int
{
    case FULL_DAY = 1;
    case FIRST_HALF = 2;
    case SECOND_HALF = 3;

    public function label(): string
    {
        return match($this) {
            LeaveSession::FULL_DAY => 'Full Day',
            LeaveSession::FIRST_HALF => 'First Half',
            LeaveSession::SECOND_HALF => 'Second Half',
        };
    }
}
