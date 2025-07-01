<?php

namespace App\Repositories\Systems\Master;

use App\Repositories\BaseRepository;
use App\Models\Systems\Master\MasterParameter;

class ParameterRepository extends BaseRepository
{
    public function entity()
    {
        return MasterParameter::class;
    }
}
