<?php

namespace App\Jobs\Email\Payment;

use App\Jobs\Email\BaseEmailJob;
use App\Mail\Payment\NewConfirmedMail;
use App\Mail\Payment\SuccessVouchersMail;
use App\Mail\Payment\SendToFranchiseeMail;

class NewConfirmedJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new NewConfirmedMail($dataForEmail);
    }
}
