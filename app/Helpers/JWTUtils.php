<?php

namespace App\Helpers;

use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Cache;


class JWTUtils
{
    public static function getCurrentUserByUuid($token = NULL)
    {
        $token = ($token === NULL) ? JWTAuth::getToken() : new \PHPOpenSourceSaver\JWTAuth\Token($token);

        $payload = JWTAuth::decode($token);

        $uuid = $payload->get('uuid');

        $user = User::where('uuid', $uuid)->first();

        return $user;
    }
}
