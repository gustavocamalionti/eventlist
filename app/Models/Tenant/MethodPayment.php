<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MethodPayment extends Model
{
    protected $table = "method_payments";

    protected $fillable = ["name", "asaas", "active"];
}
