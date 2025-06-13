<?php

namespace App\Repositories\Systems\Tenant;

use App\Models\CustomColor;

class CustomColorRepository extends BaseRepository
{
    public function entity()
    {
        return CustomColor::class;
    }
}
