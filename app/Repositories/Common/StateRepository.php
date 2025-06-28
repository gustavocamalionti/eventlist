<?php

namespace App\Repositories\Common;

use App\Models\Common\State;
use App\Repositories\BaseRepository;

class StateRepository extends BaseRepository
{
    public function entity()
    {
        return State::class;
    }
}
