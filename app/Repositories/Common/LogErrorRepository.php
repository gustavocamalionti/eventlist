<?php

namespace App\Repositories\Common;

use App\Models\Common\LogError;
use App\Repositories\BaseRepository;

class LogErrorRepository extends BaseRepository
{
    public function entity()
    {
        return LogError::class;
    }
}
