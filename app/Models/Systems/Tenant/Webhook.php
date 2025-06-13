<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Buy;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Buy::class, "buys_id");
    }
}
