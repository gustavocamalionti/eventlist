<?php

namespace App\Repositories\Systems\Tenant;

use App\Repositories\BaseRepository;
use App\Models\Systems\Tenant\TenantBuy;

class BuyRepository extends BaseRepository
{
    public function entity()
    {
        return TenantBuy::class;
    }
}
