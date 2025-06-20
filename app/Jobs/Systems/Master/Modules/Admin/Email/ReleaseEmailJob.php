<?php

namespace App\Jobs\Systems\Master\Modules\Admin\Email;

use App\Jobs\Common\BaseEmailJob;
use App\Mail\Systems\Master\General\ReleaseMail;

class ReleaseEmailJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new ReleaseMail($dataForEmail);
    }
}
