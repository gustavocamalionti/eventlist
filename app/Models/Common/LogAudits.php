<?php

namespace App\Models\Common;

use Illuminate\Database\Eloquent\Model;

class LogAudits extends Model
{
    protected $table = "log_audits";
    protected $fillable = [
        "route",
        "route_name",
        "title",
        "action",
        "method",
        "users_id",
        "users_name",
        "users_email",
        "table_name",
        "description",
        "table_item_id",
        "what_heppened",
        "ip",
    ];

    protected $casts = [
        "what_heppened" => "json",
    ];
}
