<?php

namespace App\Repositories\Systems\Tenant;

use App\Models\LogAction;
use App\Models\LogAudits;

class LogAuditRepository extends BaseRepository
{
    public function entity()
    {
        return LogAudits::class;
    }
}
