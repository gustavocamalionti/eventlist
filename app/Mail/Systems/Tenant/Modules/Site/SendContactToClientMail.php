<?php

namespace App\Mail\Systems\Tenant\Modules\Site;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendContactToClientMail extends Mailable
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
        return $this->subject(env("APP_NAME") . " | Fale conosco enviado com sucesso!")
            ->from(env("MAIL_FROM_ADDRESS", null))
            ->view("emails.content.forms.contact_client", [
                "data" => $this->data,
            ]);
    }
}
