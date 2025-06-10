<?php

namespace App\Jobs\Email\Auth;

use App\Jobs\Email\BaseEmailJob;
use App\Mail\Auth\SendVerifyEmailMail;

class SendVerifyEmailJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SendVerifyEmailMail($dataForEmail);
    }
}
