<?php

namespace App\Repositories\Common;

use App\Models\State;

class StateRepository extends BaseRepository
{
    public function entity()
    {
        return State::class;
    }
}
