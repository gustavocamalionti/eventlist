<?php

namespace App\Mail\Systems\Tenant\Modules\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TenantMailSuccessVerifyEmail extends Mailable
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
        return $this->subject(env("APP_NAME") . " | Email Verificado com Sucesso!")
            ->from(env("MAIL_FROM_ADDRESS", null))
            ->view("legacy.systems.tenant.modules.auth.pages.email.success_verify_email", [
                "data" => $this->data,
            ]);
    }
}
