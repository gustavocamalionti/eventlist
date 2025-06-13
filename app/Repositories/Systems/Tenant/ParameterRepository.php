<?php

namespace App\Repositories\Systems\Tenant;

use App\Repositories\BaseRepository;
use App\Models\Systems\Master\Parameter;

class ParameterRepository extends BaseRepository
{
    public function entity()
    {
        return Parameter::class;
    }
}
