<?php

namespace App\Models\Systems\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPermission extends Model
{
    use HasFactory;

    protected $table = "permissions";
    protected $fillable = ["name", "label", "order", "flag"];

    public function roles()
    {
        return $this->belongsToMany("App\Models\Systems\Master\MasterRole", "users", "id", "roles_id");
    }
}
