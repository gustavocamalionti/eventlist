<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class LogEmail extends Model
{
    protected $table = "log_emails";
    protected $fillable = ["uuid", "job_title", "email", "users_id", "customers_id", "buys_id", "status", "details"];
}
