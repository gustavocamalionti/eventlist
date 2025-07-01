<?php

namespace App\Repositories\Systems\Master;

use App\Repositories\BaseRepository;
use App\Models\Systems\Master\MasterRole;

class RoleRepository extends BaseRepository
{
    public function entity()
    {
        return MasterRole::class;
    }
}
