<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("webhooks", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("buys_id")->nullable()->comment("Chave estrangeira para a compra / carrinho");
            $table->string("events_id")->unique()->comment("ID do evento no gateway de pagamento (evt_xxx)");
            $table->string("event_type")->comment("Tipo do evento (ex: PAYMENT_RECEIVED)");
            $table->json("payload")->comment("Payload completo do evento");
            $table->tinyInteger("status")->default(0)->nullable()->comment("Status de processamento");
            $table
                ->tinyInteger("should_treat")
                ->default(0)
                ->nullable()
                ->comment("Campo que indica que o webhook deveria ter tratado.");
            $table->timestamps();

            $table->foreign("buys_id")->references("id")->on("buys")->onDelete("restrict")->onUpdate("restrict");
            $table->index("buys_id", "idx_webhooks_buys_id");
        });
    }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists("webhooks");
    // }
};
