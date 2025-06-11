<?php

namespace App\Jobs\Email\Auth;

use App\Jobs\Email\BaseEmailJob;
use App\Mail\Auth\SuccessVerifyEmailMail;

class SuccessVerifyEmailJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SuccessVerifyEmailMail($dataForEmail);
    }
}
