<?php

namespace App\Repositories\Systems\Tenant;

use App\Repositories\BaseRepository;
use App\Models\Systems\Tenant\TenantWebhook;

class WebhookRepository extends BaseRepository
{
    public function entity()
    {
        return TenantWebhook::class;
    }
}
