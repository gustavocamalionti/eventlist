<?php

namespace App\Mail\Systems\Master\Modules\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MasterMailSendResetPassword extends Mailable
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
        return $this->subject(env("APP_NAME") . " | Recuperação de Senha")
            ->from(env("MAIL_FROM_ADDRESS", null))
            ->view("legacy.systems.master.modules.auth.pages.email.reset_password", [
                "data" => $this->data,
                "token" => $this->data["token"],
            ]);
    }
}
