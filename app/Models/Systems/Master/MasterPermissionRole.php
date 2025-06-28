<?php

namespace App\Models\Systems\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPermissionRole extends Model
{
    use HasFactory;

    protected $table = "permissions_x_roles";
    protected $fillable = ["permissions_id", "roles_id"];
}
