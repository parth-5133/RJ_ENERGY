<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\API\V1\AuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\LoginRequest;
use App\Constants\ResStatusCode;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WebAuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }
    public function register()
    {
        return view('auth.register');
    }
    public function loginPost(LoginRequest $request)
    {
        $apiController = new AuthController();
        $response = $apiController->login($request);

        $responseData = $response->getData(true);

        if ($responseData['status'] == 200) {

            $token = $responseData['returnValue'];
            $user_data = json_encode($responseData['data']);

            Cookie::queue('access_token', $token, 120, "/", null, true, false);
            Cookie::queue('user_data', $user_data, 120, "/", null, true, false);
            return redirect()->to('/dashboard');
        }

        if ($responseData['status'] == ResStatusCode::INACTIVE_ACCOUNT->value) {
            return redirect()->back()->with('error', 'Your account is inactive. Please contact Admin.');
        } else {
            return redirect()->back()->with('error', 'Invalid username or password.');
        }
    }

    public function registerPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:50|unique:users,email',
            'role' => 'required',
            'aadhaar' => 'nullable|digits:12',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->email = $request->email;
        $user->role_id = $request->role;
        $user->aadhaar = $request->aadhaar;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }
    public function showResetForm(Request $request, $token = null, $email = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }
    public function changePassword(Request $request)
    {
        $userId = $request->input('id');

        return view('auth.change_password', compact('userId'));
    }
}
