<?php

namespace App\Repositories\Systems\Tenant;

use App\Repositories\BaseRepository;
use App\Models\Systems\Tenant\TenantParameter;

class ParameterRepository extends BaseRepository
{
    public function entity()
    {
        return TenantParameter::class;
    }
}
