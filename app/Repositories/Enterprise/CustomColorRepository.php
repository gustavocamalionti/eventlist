<?php

namespace App\Repositories\Master;

use App\Models\CustomColor;

class CustomColorRepository extends BaseRepository
{
    public function entity()
    {
        return CustomColor::class;
    }
}
