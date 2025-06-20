<?php

namespace App\Repositories\Systems\Tenant;

use App\Models\Systems\Master\MasterUser;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository
{
    public function entity()
    {
        return User::class;
    }

    public function saveExceptionPassword($user, $request)
    {
        return $user->fill($request->except(["password"]))->save();
    }
}
