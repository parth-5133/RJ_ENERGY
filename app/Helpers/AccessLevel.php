<?php

namespace App\Helpers;

use App\Helpers\JWTUtils;
use Illuminate\Support\Facades\DB;

class AccessLevel
{
    public static function getAccessLevel()
    {

        $currentUser = JWTUtils::getCurrentUserByUuid();
        $userId = $currentUser->id;

        $currentAccessLevel = DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->select('roles.access_level')
            ->where('users.id', $userId)
            ->first();

        return $currentAccessLevel;
    }
}
