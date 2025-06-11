<?php

namespace App\Repositories\Enterprise;

use App\Models\CustomColor;

class CustomColorRepository extends BaseRepository
{
    public function entity()
    {
        return CustomColor::class;
    }
}
