<?php

namespace App\Jobs\Email\Auth;

use App\Jobs\Email\BaseEmailJob;
use App\Mail\Auth\SuccessResetPasswordMail;
use App\Mail\Auth\SuccessChangePasswordMail;

class SuccessResetPasswordJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SuccessResetPasswordMail($dataForEmail);
    }
}
