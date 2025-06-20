<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantPermissionRole extends Model
{
    use HasFactory;

    protected $table = "permissions_x_roles";
    protected $fillable = ["permissions_id", "roles_id"];
}
