<?php

namespace App\Models\Systems\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = "permissions";
    protected $fillable = ["name", "label", "order", "flag"];

    public function roles()
    {
        return $this->belongsToMany("App\Models\Role", "users", "id", "roles_id");
    }
}
