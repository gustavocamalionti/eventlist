<?php

namespace App\Jobs\Systems\Master\Modules\Auth\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Master\Modules\Auth\SendResetPasswordMail;

class SendResetPasswordJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SendResetPasswordMail($dataForEmail);
    }
}
