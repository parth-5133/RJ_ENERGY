<?php

namespace App\Helpers;

use App\Models\NotificationSetting;

class NotificationHelper
{
    public static function canSendNotification($type): array
    {
        $setting = NotificationSetting::where('type', $type)
            ->where('company_id', GetCompanyId::GetCompanyId())
            ->first();

        return [
            'email' => $setting ? (bool) $setting->email_enabled : false,
            'browser' => $setting ? (bool) $setting->browser_enabled : false,
        ];
    }
}
