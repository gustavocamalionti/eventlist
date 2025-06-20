<?php

namespace App\Models\Systems\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = "roles";

    public function permissions()
    {
        return $this->belongsToMany("App\Models\Permission", "permissions_x_roles", "roles_id", "permissions_id")
            ->orderBy("roles_id")
            ->orderBy("permissions_id");
    }

    public function users()
    {
        return $this->hasMany(User::class, "roles_id");
    }
}
