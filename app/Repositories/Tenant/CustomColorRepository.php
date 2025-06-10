<?php

namespace App\Repositories\Tenant;

use App\Models\CustomColor;

class CustomColorRepository extends BaseRepository
{
    public function entity()
    {
        return CustomColor::class;
    }
}
