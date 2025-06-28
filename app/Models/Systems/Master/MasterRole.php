<?php

namespace App\Models\Systems\Master;

use Illuminate\Database\Eloquent\Model;
use App\Models\Systems\Master\MasterUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MasterRole extends Model
{
    use HasFactory;

    protected $table = "roles";

    public function permissions()
    {
        return $this->belongsToMany(
            "App\Models\Systems\Master\MasterPermission",
            "permissions_x_roles",
            "roles_id",
            "permissions_id"
        )
            ->orderBy("roles_id")
            ->orderBy("permissions_id");
    }

    public function users()
    {
        return $this->hasMany(MasterUser::class, "roles_id");
    }
}
