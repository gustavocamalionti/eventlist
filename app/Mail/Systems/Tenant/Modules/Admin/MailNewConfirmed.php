<?php

namespace App\Mail\Systems\Tenant\Modules\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailNewConfirmed extends Mailable
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
        return $this->subject(env("APP_NAME") . " | Novo Participante")
            ->from(env("MAIL_FROM_ADDRESS", null), "AmongTech")
            ->view("emails.content.payment.new_confirmed", ["data" => $this->data]);
    }
}
