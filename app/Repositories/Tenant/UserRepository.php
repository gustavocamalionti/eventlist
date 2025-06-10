<?php

namespace App\Repositories\Tenant;

use App\Models\User;

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
