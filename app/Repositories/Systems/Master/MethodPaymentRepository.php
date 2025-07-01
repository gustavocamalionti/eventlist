<?php

namespace App\Repositories\Systems\Master;

use App\Repositories\BaseRepository;
use App\Models\Systems\Tenant\MasterMethodPayment;

class MethodPaymentRepository extends BaseRepository
{
    public function entity()
    {
        return MasterMethodPayment::class;
    }
}
