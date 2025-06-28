<?php

namespace App\Jobs\Systems\Master\Modules\Auth\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Master\Modules\Auth\MasterMailSendResetPassword;

class MasterJobSendResetPassword extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new MasterMailSendResetPassword($dataForEmail);
    }
}
