<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Helpers\ApiResponse;
use App\Constants\ResStatusCode;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;

class JwtVerify
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return ApiResponse::verifyError('User not found', ResStatusCode::NOT_FOUND);
            }
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return ApiResponse::verifyError('Token is Invalid', ResStatusCode::UNAUTHORIZED);
            } elseif ($e instanceof TokenExpiredException) {
                return ApiResponse::verifyError('Token is Expired', ResStatusCode::FORBIDDEN);
            } else {
                return ApiResponse::verifyError('Authorization Token not found', ResStatusCode::UNAUTHORIZED);
            }
        }
        return $next($request);
    }
}
