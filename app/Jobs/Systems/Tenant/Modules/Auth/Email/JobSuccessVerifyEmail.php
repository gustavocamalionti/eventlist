<?php

namespace App\Jobs\Systems\Tenant\Modules\Auth\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Tenant\Modules\Auth\MailSuccessVerifyEmail;

class JobSuccessVerifyEmail extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new MailSuccessVerifyEmail($dataForEmail);
    }
}
