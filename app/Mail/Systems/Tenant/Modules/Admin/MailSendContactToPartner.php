<?php

namespace App\Mail\Systems\Tenant\Modules\Admin;

use App\Services\Crud\CrudFormSubjectService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSendContactToPartner extends Mailable
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
        return $this->subject(
            env("APP_NAME") .
                " | " .
                app(CrudFormSubjectService::class)->findById($this->data["form_subjects_id"])->form->name .
                " - " .
                app(CrudFormSubjectService::class)->findById($this->data["form_subjects_id"])->name
        )
            ->from(env("MAIL_FROM_ADDRESS", null))
            ->view("emails.content.forms.contact_partner", [
                "data" => $this->data,
            ]);
    }
}
