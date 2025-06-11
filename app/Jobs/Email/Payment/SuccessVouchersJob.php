<?php

namespace App\Jobs\Email\Payment;

use App\Jobs\Email\BaseEmailJob;
use App\Mail\Payment\SuccessVouchersMail;
use App\Mail\Payment\SendToFranchiseeMail;

class SuccessVouchersJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new SuccessVouchersMail($dataForEmail);
    }
}
