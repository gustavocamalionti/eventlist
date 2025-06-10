<?php

namespace App\Repositories\Common;

use App\Models\Common\Citie;
use App\Repositories\BaseRepository;

class CitieRepository extends BaseRepository
{
    public function entity()
    {
        return Citie::class;
    }
}
