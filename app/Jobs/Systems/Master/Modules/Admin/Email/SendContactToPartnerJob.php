<?php

namespace App\Jobs\Email\Forms;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Forms\SendContactToPartnerMail;

class SendContactToPartnerJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SendContactToPartnerMail($dataForEmail);
    }
}
