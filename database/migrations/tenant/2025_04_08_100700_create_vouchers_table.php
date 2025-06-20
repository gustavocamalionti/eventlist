<?php

use App\Libs\Enums\EnumStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("vouchers", function (Blueprint $table) {
            $table->id()->comment("Identificador único do voucher");
            $table->unsignedBigInteger("buys_id")->nullable()->comment("Chave estrangeira para a compra / carrinho");
            $table->unsignedBigInteger("value")->default(0)->comment("Valor do Voucher em centavos");
            $table->tinyInteger("active")->default(0)->comment("Indica se o pagamento foi efetivado");
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data da última atualização do registro");

            // Foreign Keys
            $table->foreign("buys_id")->references("id")->on("buys")->onDelete("restrict")->onUpdate("restrict");

            // Indexes
            $table->index("buys_id", "idx_vouchers_buys_id");
        });

        DB::statement(
            "ALTER TABLE vouchers COMMENT = 'Tabela que armazena os vouchers gerados para participantes de campanhas ou eventos'"
        );
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('vouchers');
    // }
};
