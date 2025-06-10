<?php

namespace App\Repositories\Enterprise;

use App\Models\Enterprise\LogError;
use App\Repositories\BaseRepository;

class LogErrorRepository extends BaseRepository
{
    public function entity()
    {
        return LogError::class;
    }
}
