<?php

namespace App\Helpers;

use App\Helpers\JWTUtils;

class GetCompanyId
{
    public static function getCompanyId()
    {
        $data = request()->cookie('selected_companies');

        if ($data == null) {
            $cookieData = json_decode(request()->cookie('user_data'), true);
            $data = $cookieData['company_id'] ?? null;

            return $data;
        }

        if ($data == 0) {
            $currentUser = JWTUtils::getCurrentUserByUuid() ?? null;

            return $data = $currentUser->company_id;
        }

        return $data;
    }
}
