<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Helpers\UserHelper;
use App\Models\User;

class ProfileController extends Controller
{
    public function profileHeader($id)
    {
        $userId = UserHelper::getUserIdByUuid($id);

        $data = User::select(
            'users.id',
            'users.first_name',
            'users.last_name',
            'roles.name as role_name',
            'employee_infos.profile_image',
        )
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('employee_infos', 'users.id', '=', 'employee_infos.user_id')
            ->where('users.is_active', 1)
            ->where('users.id', $userId)
            ->first();

        if (!$data) {
            return view('profile.profile-header')->with('error', 'User not found');
        }

        $name = $data->first_name . ' ' . $data->last_name;
        $profile_img = $data->profile_image;

        return view('profile.profile-header', compact('name', 'profile_img'));
    }

    public function profile()
    {
        return view('profile.personalInfo_index');
    }
    public function address()
    {
        return view('profile.addressInfo_index');
    }
    public function education()
    {
        return view('profile.education_index');
    }
    public function documents()
    {
        return view('profile.documents_index');
    }
    public function financial()
    {
        $cookieData = json_decode(request()->cookie('user_data'), true);
        $role_code = $cookieData['role_code'] ?? null;

        return view('profile.financial_index', compact('role_code'));
    }
    public function experience()
    {
        return view('profile.experience_index');
    }
    public function vehicle()
    {
        return view('profile.vehicle_index');
    }
    public function job()
    {
        return view('profile.job_index');
    }
}
