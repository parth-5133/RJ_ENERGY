<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function formatTimestamp($timestamp, $timezone = null)
    {
        $timezone = $timezone ?? env('APP_TIMEZONE', 'Asia/Kolkata');

        $formattedDate = Carbon::createFromTimestamp($timestamp, $timezone);

        return $formattedDate;
    }


    public static function createFromTimeStringWithTimezone($timeString, $timezone = null)
    {
        $timezone = $timezone ?? env('APP_TIMEZONE', 'Asia/Kolkata');

        $carbonTime = Carbon::createFromTimeString($timeString, $timezone);

        return $carbonTime;
    }
}
