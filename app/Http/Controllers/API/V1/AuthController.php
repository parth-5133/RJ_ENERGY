<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\JWTUtils;
use Illuminate\Support\Facades\Artisan;
use App\Constants\ResStatusCode;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return ApiResponse::error('credentials error', ['email' => 'Email not found']);
        }
        if ($user->is_active !== 1) {
            return ApiResponse::error('account error', ['status' => 'Your account is inactive. Please contact support.'], ResStatusCode::INACTIVE_ACCOUNT);
        }

        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);

        if (!$token) {
            return ApiResponse::error('credentials error', ['password' => 'Password is incorrect']);
        }

        $user = Auth::user();
        Auth::login($user);

        if ($user instanceof \App\Models\User) {
            $user->last_logged_in_at = now();
            $user->ip_address = $request->ip();
            $user->save();
        }
        $role = DB::table('roles')
            ->where('id', $user->role_id)
            ->select('code', 'name')
            ->first();

        $profile_image = DB::table('employee_infos')
            ->where('user_id', $user->id)
            ->select('profile_image')
            ->first();

        $userData = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'employee_id' => $user->employee_id ?? null,
            'role_code' => $role->code ?? null,
            'role_name' => $role->name ?? null,
            'profile_img' => $profile_image->profile_image ?? null,
            'company_name' => $user->company->legal_name ?? null,
            'company_id' => $user->company_id ?? null,
        ];

        $this->storeJwtTokenForTesting($token);

        return ApiResponse::success($userData, 'Login successfully.', $token);
    }
    public function logout()
    {
        Artisan::call('cache:clear');

        Auth::logout();

        return ApiResponse::success(null, 'Logout successfully.');
    }
    protected function storeJwtTokenForTesting($token)
    {
        if (!defined('JWT_TOKEN')) {
            define('JWT_TOKEN', $token);
        }
        session(['jwt_token' => $token]);
    }
    public function refresh()
    {
        try {
            // Ensure the token is being retrieved
            $token = JWTAuth::getToken();
            if (!$token) {
                return ApiResponse::error(null, 'Token not provided');
            }
            $newToken = JWTAuth::refresh($token);
            $user = Auth::user();

            return ApiResponse::success($user, 'Token refresh successfully.', $newToken);
        } catch (\PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException $e) {
            return ApiResponse::error($e, 'Could not refresh token');
        }
    }
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $validatedData = $request->validated();
        $email = $validatedData['email'];

        $lastRequest = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastRequest && Carbon::parse($lastRequest->created_at)->addMinutes(5)->isFuture()) {
            return ApiResponse::error(null, 'Please wait 5 minutes between reset requests.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return ApiResponse::error(null, 'Email address not found.');
        }

        $token = Password::createToken($user);

        try {
            Mail::to($email)->send(new ResetPasswordMail($token, $email));
        } catch (\Exception $e) {
            return ApiResponse::error(null, 'Your request has been sent successfully, but failed to send email notifications. Please contact support.');
        }

        DB::table('password_reset_tokens')
            ->updateOrInsert(
                ['email' => $email],
                ['token' => $token, 'created_at' => now()]
            );

        return ApiResponse::success(['email' => $email], 'Reset password email sent successfully.', $token);
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (Carbon::parse($passwordReset->created_at)->addMinutes(30)->isPast()) {
            return back();
        }

        $user = User::where('email', $request->email)->first();

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return ApiResponse::success(null, 'Password was changed successfully.');
    }
    public function changePassword(ChangePasswordRequest $request)
    {

        if ($request->has('userId') && $request->userId != 0) {
            $user = User::find($request->userId);

            if (!$user) {
                return ApiResponse::error('User not found.', 404);
            }
        } else {
            // Get the currently authenticated user if 'userId' is not provided or is 0
            $user = JWTUtils::getCurrentUserByUuid();
        }

        $newPassword = Hash::make($request->password);

        $user->password = $newPassword;
        $user->save();

        return ApiResponse::success(null, 'Password changed successfully.');
    }
    public function setYearFilter(Request $request)
    {
        $year = $request->input('year');

        $response = response()->json(['success' => true], 200);

        $response->withoutCookie('selected_year');

        $response->cookie('selected_year', $year, 60); // 60 minutes = 1 hour

        return $response;
    }
    public function setCompaniesFilter(Request $request)
    {
        $companiesId = $request->input('companiesId');

        $response = response()->json(['success' => true], 200);

        $response->withoutCookie('selected_companies');

        $response->cookie('selected_companies', $companiesId, 60); // 60 minutes = 1 hour

        return $response;
    }
}
