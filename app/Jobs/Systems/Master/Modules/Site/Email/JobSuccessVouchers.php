<?php

namespace App\Jobs\Systems\Master\Modules\Site\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Tenant\Modules\Site\MailSuccessVouchers;

class JobSuccessVouchers extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new MailSuccessVouchers($dataForEmail);
    }
}
