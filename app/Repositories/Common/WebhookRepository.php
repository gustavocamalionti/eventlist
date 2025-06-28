<?php

namespace App\Repositories\Common;

use App\Models\Common\Webhook;
use App\Repositories\BaseRepository;

class WebhookRepository extends BaseRepository
{
    public function entity()
    {
        return Webhook::class;
    }
}
