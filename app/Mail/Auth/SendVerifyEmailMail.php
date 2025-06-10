<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendVerifyEmailMail extends Mailable
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
        $verificationUrl = route("verification.verify", [
            "id" => $this->data["user_id"],
            "hash" => sha1($this->data["email"]),
        ]);
        $this->data["verificationUrl"] = $verificationUrl;
        return $this->subject(env("APP_NAME") . " | Confirme seu e-mail")
            ->from(env("MAIL_FROM_ADDRESS", null))
            ->view("emails.content.auth.verify_email", [
                "data" => $this->data,
            ]);
    }
}
