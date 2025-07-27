<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class BroadcastAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Get token from cookie, Authorization header, or request parameter
        $token = $request->cookie('access_token') ?? 
                 $request->bearerToken() ?? 
                 $request->input('auth.headers.Authorization');
        
        // Remove 'Bearer ' prefix if present
        if ($token && str_starts_with($token, 'Bearer ')) {
            $token = substr($token, 7);
        }
        
        if (!$token) {
            return response()->json(['error' => 'Unauthorized - No token provided'], 401);
        }

        try {
            // Set the token and authenticate the user
            JWTAuth::setToken($token);
            $user = JWTAuth::authenticate();
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 401);
            }
            
            // Set the authenticated user for Laravel's Auth system
            Auth::login($user);
            
            return $next($request);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid token: ' . $e->getMessage()], 401);
        }
    }
}
