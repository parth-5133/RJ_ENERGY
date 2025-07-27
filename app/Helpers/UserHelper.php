<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;


class UserHelper
{
    public static function getUserByUuid($uuid)
    {
        $cacheKey = "users";
        $user = Cache::remember($cacheKey, 600, function () {
            return User::all();
        });

        return $user->where('uuid', $uuid)->first();
    }

    public static function getUserIdByUuid($uuid)
    {
        $user = self::getUserByUuid($uuid);
        return $user ? $user->id : null;
    }
    public static function getUserRoleByUuid($uuid)
    {
        $user = self::getUserByUuid($uuid);
        return $user ? $user->role_id : null;
    }
}
