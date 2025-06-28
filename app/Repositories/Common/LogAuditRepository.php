<?php

namespace App\Repositories\Common;

use App\Models\Common\LogAudits;
use App\Repositories\BaseRepository;

class LogAuditRepository extends BaseRepository
{
    public function entity()
    {
        return LogAudits::class;
    }
}
