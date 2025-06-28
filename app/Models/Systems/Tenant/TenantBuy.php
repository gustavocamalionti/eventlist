<?php

namespace App\Models\Systems\Tenant;

use Illuminate\Database\Eloquent\Model;

class TenantBuy extends Model
{
    protected $table = "buys";

    protected $fillable = [
        "users_id",
        "method_payments_id",
        "status",
        "qtd_vouchers",
        "value",
        "net_value",
        "stripe_session_id",
        "gateway_payment_id",
        "payment_details",
        "receipt_url",
    ];

    public function users()
    {
        return $this->hasOne("App\Models\Tenant\User", "id", "users_id");
    }

    public function methodPayments()
    {
        return $this->hasOne("App\Models\Tenant\MethodPayment", "id", "method_payments_id");
    }

    protected $casts = [
        "payment_details" => "array",
    ];
}
