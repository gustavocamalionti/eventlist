<?php

namespace App\Repositories\Systems\Tenant;

use App\Repositories\BaseRepository;
use App\Models\Systems\Tenant\TenantMethodPayment;

class MethodPaymentRepository extends BaseRepository
{
    public function entity()
    {
        return TenantMethodPayment::class;
    }
}
