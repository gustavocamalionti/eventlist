<?php

namespace App\Mail\Systems\Master\Modules\Auth;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;

class MailSendVerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $verificationUrl = URL::temporarySignedRoute("tenant.auth.verification.verify", Carbon::now()->addMinutes(60), [
            "id" => $this->data["user_id"],
            "hash" => sha1($this->data["email"]),
        ]);

        $this->data["verificationUrl"] = $verificationUrl;

        return $this->subject(env("APP_NAME") . " | Confirme seu e-mail")
            ->from(env("MAIL_FROM_ADDRESS"))
            ->view("legacy.systems.tenant.modules.auth.pages.email.verify_email", [
                "data" => $this->data,
            ]);
    }
}
