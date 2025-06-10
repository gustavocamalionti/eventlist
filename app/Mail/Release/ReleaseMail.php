<?php

namespace App\Mail\Release;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ReleaseMail extends Mailable
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
        return $this->subject(env("APP_NAME") . " - Notas de Atualização " . Config::get("app.version"))
            ->from("suporte@halipar.com.br", "Halipar | Desenvolvimento")
            ->view("emails.intranet.releases-" . $this->data["version"], [
                "data" => $this->data,
            ]);
    }
}
