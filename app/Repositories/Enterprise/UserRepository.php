<?php

namespace App\Repositories\Enterprise;

use App\Models\Enterprise\User;

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
