<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use App\Helpers\DateHelper;

class CheckAuth
{

    public function handle(Request $request, Closure $next): Response
    {
        $cookieValue = $request->cookie('access_token');

        if (!$cookieValue) {
            return redirect()->route('login');
        }

        try {
            JWTAuth::setToken($cookieValue);

            $decoded = JWTAuth::getPayload();

            $exp = $decoded['exp'];

            $expirationTime = DateHelper::formatTimestamp($exp);

            $currentTime = Carbon::now('Asia/Kolkata');

            if ($currentTime >= $expirationTime) {
                return redirect()->route('login');
            }
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException $e) {
            // Handle invalid token
            return redirect()->route('login');
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException $e) {
            // Handle expired token
            return redirect()->route('login');
        } catch (\Exception $e) {
            // Handle other exceptions
            return redirect()->route('login');
        }

        return $next($request);
    }
}
