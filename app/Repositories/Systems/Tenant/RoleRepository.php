<?php

namespace App\Repositories\Systems\Tenant;

use App\Repositories\BaseRepository;
use App\Models\Systems\Tenant\TenantRole;

class RoleRepository extends BaseRepository
{
    public function entity()
    {
        return TenantRole::class;
    }
}
