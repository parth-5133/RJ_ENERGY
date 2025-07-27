<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Helpers\UserHelper;
use App\Models\User;

class ProfileController extends Controller
{
    public function profileHeader($id)
    {
        $userId = UserHelper::getUserIdByUuid($id);

        $cacheKey = 'profile_header_' . $userId;

        $data = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($userId) {
            return User::select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.employee_id',
                'roles.name as role_name',
                'employee_infos.profile_image',
                'employee_jobs.job_title',
                'employee_jobs.date_of_joining'
            )
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->leftJoin('employee_infos', 'users.id', '=', 'employee_infos.user_id')
                ->leftJoin('employee_jobs', 'users.id', '=', 'employee_jobs.user_id')
                ->where('users.is_active', 1)
                ->where('users.id', $userId)
                ->first();
        });

        if (!$data) {
            return view('profile.profile-header')->with('error', 'User not found');
        }

        $name = $data->first_name . ' ' . $data->last_name;
        $employee_id = $data->employee_id;
        $profile_img = $data->profile_image;
        $job_title = $data->job_title;
        $date_of_joining = $data->date_of_joining
            ? Carbon::parse($data->date_of_joining)->format('d/m/Y')
            : null;

        return view('profile.profile-header', compact('name', 'employee_id', 'profile_img', 'job_title', 'date_of_joining'));
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
