<?php

namespace App\Jobs\Systems\Tenant\Modules\Site\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Tenant\Modules\Site\MailSendContactToClient;

class JobSendContactToClient extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new MailSendContactToClient($dataForEmail);
    }
}
