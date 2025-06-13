<?php

namespace App\Repositories\Systems\Master;

use App\Models\Tenant\LogEmail;
use App\Repositories\BaseRepository;

class LogEmailRepository extends BaseRepository
{
    public function entity()
    {
        return LogEmail::class;
    }
}
