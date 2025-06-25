<?php

namespace App\Jobs\Systems\Tenant\Modules\Site\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Tenant\Modules\Site\TenantMailSendContactToClient;

class TenantJobSendContactToClient extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new TenantMailSendContactToClient($dataForEmail);
    }
}
