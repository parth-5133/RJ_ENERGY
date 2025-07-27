<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public $appUrl;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
        $this->appUrl = config('app.url');
    }


    public function build()
    {
        $link = url('password/reset', ['token' => $this->token, 'email' => $this->email]);

        return $this->view('emails.reset_password')
            ->subject('Password reset notification')
            ->with([
                'token' => $this->token,
                'email' => $this->email,
                'link' => $link,
                'appUrl' => $this->appUrl
            ]);
    }
}
