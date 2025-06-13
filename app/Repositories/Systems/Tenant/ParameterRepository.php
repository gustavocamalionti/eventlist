<?php

namespace App\Repositories\Systems\Tenant;

use App\Models\Tenant\Parameter;
use App\Repositories\BaseRepository;

class ParameterRepository extends BaseRepository
{
    public function entity()
    {
        return Parameter::class;
    }
}
