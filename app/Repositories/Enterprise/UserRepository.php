<?php

namespace App\Repositories\Master;

use App\Models\Master\User;

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
