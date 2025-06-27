<?php

namespace App\Repositories\Systems\Tenant;

use App\Repositories\BaseRepository;
use App\Models\Systems\Tenant\TenantUser;

class UserRepository extends BaseRepository
{
    public function entity()
    {
        return TenantUser::class;
    }

    public function saveExceptionPassword($user, $request)
    {
        return $user->fill($request->except(["password"]))->save();
    }
}
