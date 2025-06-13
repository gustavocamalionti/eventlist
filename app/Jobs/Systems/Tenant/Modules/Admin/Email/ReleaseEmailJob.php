<?php

namespace App\Jobs\Email\Release;

use App\Mail\ReleaseMail;
use App\Jobs\Email\BaseEmailJob;

class ReleaseEmailJob extends BaseEmailJob
{
    public function emailObject($dataForEmail)
    {
        return new ReleaseMail($dataForEmail);
    }
}
