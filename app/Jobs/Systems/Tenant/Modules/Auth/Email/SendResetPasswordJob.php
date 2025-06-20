<?php

namespace App\Jobs\Systems\Tenant\Modules\Auth\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Tenant\Modules\Auth\SendResetPasswordMail;

class SendResetPasswordJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SendResetPasswordMail($dataForEmail);
    }
}
