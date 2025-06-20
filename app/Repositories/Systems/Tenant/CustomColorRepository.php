<?php

namespace App\Repositories\Systems\Tenant;

use App\Models\Common\CustomColor;
use App\Repositories\BaseRepository;

class CustomColorRepository extends BaseRepository
{
    public function entity()
    {
        return CustomColor::class;
    }
}
