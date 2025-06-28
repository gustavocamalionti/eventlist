<?php

namespace App\Models\Systems\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\Systems\Tenant\TenantUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantRole extends Model
{
    use HasFactory;

    protected $table = "roles";

    public function permissions()
    {
        return $this->belongsToMany(
            "App\Models\Systems\Tenant\TenantPermission",
            "permissions_x_roles",
            "roles_id",
            "permissions_id"
        )
            ->orderBy("roles_id")
            ->orderBy("permissions_id");
    }

    public function users()
    {
        return $this->hasMany(TenantUser::class, "roles_id");
    }
}
