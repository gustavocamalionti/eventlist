<?php

namespace App\Repositories\Systems\Master;

use App\Models\Master\LogError;
use App\Repositories\BaseRepository;

class LogErrorRepository extends BaseRepository
{
    public function entity()
    {
        return LogError::class;
    }
}
