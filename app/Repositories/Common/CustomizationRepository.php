<?php

namespace App\Repositories\Common;

use App\Models\Common\Customization;
use App\Repositories\BaseRepository;

class CustomizationRepository extends BaseRepository
{
    public function entity()
    {
        return Customization::class;
    }
}
