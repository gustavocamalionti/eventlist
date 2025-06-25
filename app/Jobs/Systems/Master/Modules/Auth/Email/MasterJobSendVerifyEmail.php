<?php

namespace App\Jobs\Systems\Master\Modules\Auth\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Master\Modules\Auth\MasterMailSendVerifyEmail;

class MasterJobSendVerifyEmail extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new MasterMailSendVerifyEmail($dataForEmail);
    }
}
