<?php

namespace App\Jobs\Systems\Master\Modules\Auth\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Master\Modules\Auth\SuccessResetPasswordMail;

class SuccessResetPasswordJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SuccessResetPasswordMail($dataForEmail);
    }
}
