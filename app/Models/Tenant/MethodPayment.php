<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class MethodPayment extends Model
{
    protected $table = "method_payments";

    protected $fillable = ["name", "asaas", "active"];
}
