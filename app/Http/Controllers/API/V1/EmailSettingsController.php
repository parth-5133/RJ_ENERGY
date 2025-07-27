<?php

namespace App\Http\Controllers\API\V1;

use App\Models\EmailSetting;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Constants\ResMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmailSettingRequest;
use App\Helpers\JWTUtils;
use App\Helpers\GetCompanyId;
use App\Helpers\FinancialYearService;
use Illuminate\Support\Facades\DB;

class EmailSettingsController extends Controller
{
    public function index()
    {
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        $settings = EmailSetting::where('email_settings.company_id', $CompanyId)
            ->leftJoin('users', 'email_settings.updated_by', '=', 'users.id')
            ->select(
                'email_settings.id',
                'email_settings.mail_driver',
                'email_settings.mail_host',
                'email_settings.mail_port',
                'email_settings.mail_username',
                'email_settings.cc_mail_username',
                'email_settings.mail_password',
                'email_settings.mail_encryption',
                'email_settings.mail_from_address',
                'email_settings.mail_from_name',
                'email_settings.is_active',
                'email_settings.updated_by',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as updated_name"),
                DB::raw("DATE_FORMAT(email_settings.updated_at, '%d/%m/%Y') as updated_at_formatted")
            )
            ->orderBy('email_settings.created_at', 'asc')
            ->get();

        return ApiResponse::success($settings, ResMessages::RETRIEVED_SUCCESS);
    }

    public function store(EmailSettingRequest $request)
    {
        $currentUser = JWTUtils::getCurrentUserByUuid();
        $CompanyId = GetCompanyId::GetCompanyId();

        if ($CompanyId == null) {
            return ApiResponse::error(ResMessages::COMPANY_NOT_FOUND, 404);
        }

        if ($request->is_active) {
            EmailSetting::where('is_active', true)
                ->where('company_id', $CompanyId)
                ->update(['is_active' => false]);
        }

        $setting = EmailSetting::create([
            'mail_driver' => $request->mail_driver,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'cc_mail_username' => $request->cc_mail_username,
            'mail_password' => $request->mail_password,
            'mail_encryption' => $request->mail_encryption,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
            'is_active' => $request->is_active ?? false,
            'company_id' => $CompanyId,
            'created_by' => $currentUser->id,
            'created_at' => now(),
            'updated_at' => null,
        ]);

        return ApiResponse::success($setting, ResMessages::CREATED_SUCCESS);
    }

    public function view(Request $request)
    {
        $id = $request->id;
        $setting = EmailSetting::findOrFail($id);
        return ApiResponse::success($setting, ResMessages::RETRIEVED_SUCCESS);
    }

    public function update(EmailSettingRequest $request)
    {
        $id = $request->id;

        $setting = EmailSetting::find($id);

        // If this is set as active, deactivate all other settings
        if ($request->is_active) {
            EmailSetting::where('id', '!=', $id)
                ->where('company_id', $setting->company_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $setting->update([
            'mail_driver' => $request->mail_driver,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'cc_mail_username' => $request->cc_mail_username,
            'mail_password' => $request->mail_password,
            'mail_encryption' => $request->mail_encryption,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
            'is_active' => $request->is_active ?? false,
            'updated_by' => JWTUtils::getCurrentUserByUuid()->id,
            'updated_at' => now(),
        ]);

        return ApiResponse::success($setting, ResMessages::UPDATED_SUCCESS);
    }

    public function delete($id)
    {
        $setting = EmailSetting::find($id);

        if ($setting && $setting->is_active) {
            return ApiResponse::error(null, 'Cannot delete active email setting');
        }

        if ($setting) {
            $setting->delete();
            return ApiResponse::success($setting, ResMessages::DELETED_SUCCESS);
        } else {
            return ApiResponse::error(null, ResMessages::NOT_FOUND);
        }
    }

    public function testConnection(Request $request)
    {
        $setting = EmailSetting::find($request->id);

        if (!$setting) {
            return ApiResponse::error('Email setting not found', 404);
        }

        // Get the email settings from the request
        $config = [
            'driver' => $setting->mail_driver,
            'host' => $setting->mail_host,
            'port' => $setting->mail_port,
            'username' => $setting->mail_username,
            'password' => $setting->mail_password,
            'encryption' => $setting->mail_encryption,
            'from' => [
                'address' => $setting->mail_from_address,
                'name' => $setting->mail_from_name,
            ],
        ];

        try {
            // Set temporary config
            config([
                'mail.mailers.smtp.host' => $config['host'],
                'mail.mailers.smtp.port' => $config['port'],
                'mail.mailers.smtp.username' => $config['username'],
                'mail.mailers.smtp.password' => $config['password'],
                'mail.mailers.smtp.encryption' => $config['encryption'],
                'mail.from.address' => $config['from']['address'],
                'mail.from.name' => $config['from']['name'],
            ]);

            // Send test email
            \Illuminate\Support\Facades\Mail::raw('Test email from your application', function ($message) use ($setting) {
                $message->to($setting->mail_from_address)
                    ->subject('Test Email');
            });

            return ApiResponse::success(null, 'Email connection test successful');
        } catch (\Exception $e) {
            return ApiResponse::error(null, $e->getMessage());
        }
    }
}
