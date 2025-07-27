<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NotificationSetting;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use App\Helpers\GetCompanyId;

class NotificationSettingController extends Controller
{
    public function index()
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $settings = NotificationSetting::where('company_id', $CompanyId)
            ->select('type', 'email_enabled', 'browser_enabled')
            ->get();

        return ApiResponse::success($settings, ResMessages::RETRIEVED_SUCCESS);
    }

    public function update(Request $request)
    {
        $request->validate([
            'notifications' => 'required|array',
            'notifications.*.type' => 'required|string',
            'notifications.*.email' => 'required|boolean',
            'notifications.*.browser' => 'required|boolean',
        ]);

        foreach ($request->notifications as $notif) {
            NotificationSetting::where('type', $notif['type'])
                ->where('company_id', GetCompanyId::GetCompanyId())
                ->update(
                    [
                        'email_enabled' => $notif['email'],
                        'browser_enabled' => $notif['browser'],
                    ]
                );
        }

        return ApiResponse::success(null, ResMessages::UPDATED_SUCCESS);
    }
}
