<?php

namespace App\Models\Systems\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantPermission extends Model
{
    use HasFactory;

    protected $table = "permissions";
    protected $fillable = ["name", "label", "order", "flag"];

    public function roles()
    {
        return $this->belongsToMany("App\Models\Systems\Tenant\TenantRole", "users", "id", "roles_id");
    }
}
