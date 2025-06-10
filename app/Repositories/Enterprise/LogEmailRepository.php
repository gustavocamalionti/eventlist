<?php

namespace App\Repositories\Enterprise;

use App\Models\Tenant\LogEmail;
use App\Repositories\BaseRepository;

class LogEmailRepository extends BaseRepository
{
    public function entity()
    {
        return LogEmail::class;
    }
}
