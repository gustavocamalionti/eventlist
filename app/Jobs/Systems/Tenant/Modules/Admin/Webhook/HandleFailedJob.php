<?php

namespace App\Jobs\Webhooks;

use Illuminate\Support\Facades\Log;
use App\Jobs\Webhooks\BaseWebhookJob;

class HandleFailedJob extends BaseWebhookJob
{
    public function sendMail() {}
    public function extractInfoJson() {}
}
