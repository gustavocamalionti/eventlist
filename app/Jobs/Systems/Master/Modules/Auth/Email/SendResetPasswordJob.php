<?php

namespace App\Jobs\Email\Auth;

use App\Jobs\Email\BaseEmailJob;
use App\Mail\Auth\SendResetPasswordMail;

class SendResetPasswordJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SendResetPasswordMail($dataForEmail);
    }
}
