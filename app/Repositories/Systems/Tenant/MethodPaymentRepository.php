<?php

namespace App\Repositories\Systems\Tenant;

use App\Models\MethodPayment;

class MethodPaymentRepository extends BaseRepository
{
    public function entity()
    {
        return MethodPayment::class;
    }
}
