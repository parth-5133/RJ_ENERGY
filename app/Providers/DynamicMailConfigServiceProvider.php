<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\EmailSetting;
use Illuminate\Support\Facades\Config;
use App\Helpers\GetCompanyId;

class DynamicMailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('email_settings')) {
            $settings = EmailSetting::where('is_active', true)
                ->where('company_id', GetCompanyId::getCompanyId()) // Replace with your company ID
                ->first();

            if ($settings) {
                Config::set('mail.mailers.smtp.transport', $settings->mail_driver);
                Config::set('mail.mailers.smtp.host', $settings->mail_host);
                Config::set('mail.mailers.smtp.port', $settings->mail_port);
                Config::set('mail.mailers.smtp.username', $settings->mail_username);
                Config::set('mail.mailers.smtp.password', $settings->mail_password);
                Config::set('mail.mailers.smtp.encryption', $settings->mail_encryption);

                Config::set('mail.from.address', $settings->mail_from_address);
                Config::set('mail.from.name', $settings->mail_from_name);
            }
        }
    }
}
