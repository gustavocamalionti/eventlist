<?php

namespace App\Models\Common;

use App\Models\Tenant\Buy;
use Illuminate\Database\Eloquent\Model;
use App\Models\Systems\Master\MasterBuy;
use App\Models\Systems\Tenant\TenantBuy;

class Webhook extends Model
{
    protected $table = "webhooks";
    protected $fillable = ["buys_id", "events_id", "event_type", "payload", "status", "should_treat"];

    protected $casts = [
        "payload" => "array",
    ];

    /**
     * Relacionamento com a tabela stores (loja vinculada ao Stripe Event)
     */
    public function buys()
    {
        $isTenant = tenancy()->initialized;
        if ($isTenant) {
            return $this->belongsTo(TenantBuy::class, "buys_id");
        }

        return $this->belongsTo(MasterBuy::class, "buys_id");
    }
}
