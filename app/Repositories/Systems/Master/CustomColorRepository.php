<?php

namespace App\Repositories\Systems\Master;

use App\Models\Common\CustomColor;

class CustomColorRepository extends BaseRepository
{
    public function entity()
    {
        return CustomColor::class;
    }
}
