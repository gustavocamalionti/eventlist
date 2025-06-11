<?php

namespace App\Repositories\Tenant;

use App\Models\Tenant\Buy;
use App\Repositories\BaseRepository;

class BuyRepository extends BaseRepository
{
    public function entity()
    {
        return Buy::class;
    }
}
