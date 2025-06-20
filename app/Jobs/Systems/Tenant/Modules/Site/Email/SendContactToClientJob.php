<?php

namespace App\Jobs\Email\Forms;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Forms\SendContactToClientMail;

class SendContactToClientJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SendContactToClientMail($dataForEmail);
    }
}
