<?php

namespace App\Jobs\Systems\Tenant\Modules\Site\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Tenant\Modules\Site\TenantMailSuccessVouchers;

class TenantJobSuccessVouchers extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new TenantMailSuccessVouchers($dataForEmail);
    }
}
