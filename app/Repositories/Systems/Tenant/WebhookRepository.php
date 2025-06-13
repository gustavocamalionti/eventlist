<?php

namespace App\Repositories\Systems\Tenant;

use App\Models\Tenant\Webhook;
use App\Repositories\BaseRepository;

class WebhookRepository extends BaseRepository
{
    public function entity()
    {
        return Webhook::class;
    }
}
