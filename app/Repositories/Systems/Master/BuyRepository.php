<?php

namespace App\Repositories\Systems\Master;

use App\Repositories\BaseRepository;
use App\Models\Systems\Master\MasterBuy;

class BuyRepository extends BaseRepository
{
    public function entity()
    {
        return MasterBuy::class;
    }
}
