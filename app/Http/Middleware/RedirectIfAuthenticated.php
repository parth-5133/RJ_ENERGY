<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {

        $cookieValue = $request->cookie('access_token');

        if (!$cookieValue) {
            return $next($request);
        }

        JWTAuth::setToken($cookieValue);

        if (JWTAuth::check()) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
