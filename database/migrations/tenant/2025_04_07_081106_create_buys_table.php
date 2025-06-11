<?php

use App\Libs\Enums\EnumStatus;
use Illuminate\Support\Facades\DB;
use App\Libs\Enums\EnumStatusBuies;
use Illuminate\Support\Facades\Schema;
use App\Libs\Enums\EnumPermissionsLevel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("buys", function (Blueprint $table) {
            $table->id()->comment("Identificador único da compra");
            $table
                ->unsignedBigInteger("users_id")
                ->nullable()
                ->comment("Identificador único do cliente em nossa base de dados.");
            $table->integer("qtd_vouchers")->nullable()->comment("Quantidade de vouchers vendidos");
            $table->decimal("value", 7, 2)->nullable()->comment("Valor total da cobrança");
            $table->decimal("net_value", 7, 2)->nullable()->comment("Valor total da cobrança");
            $table
                ->unsignedBigInteger("method_payments_id")
                ->nullable()
                ->comment("Chave estrangeira para método de pagamento");
            $table->json("payment_details")->nullable()->comment("Todas as informações do pagamento");
            $table->string("invoice_url")->nullable()->comment("URL pública para realizar o pagamento");
            $table->string("receipt_url")->nullable()->comment("URL pública para resgatar o comprovante de pagamento");
            $table->string("gateway_payment_id")->nullable()->comment("ID da compra no gateway de pagamento");
            $table->string("stripe_session_id")->nullable()->comment("ID da sessão do Stripe associada à compra");
            $table
                ->integer("status")
                ->default(EnumStatusBuies::INTERNAL_PAYMENT_CREATED)
                ->comment("Id do status da compra"); // paid, failed, etc.
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data da última atualização do registro");

            $table
                ->foreign("method_payments_id", "fk_buys_method_payments_id")
                ->references("id")
                ->on("method_payments")
                ->onDelete("restrict")
                ->onUpdate("restrict");
            $table
                ->foreign("users_id", "fk_buys_users_id")
                ->references("id")
                ->on("users")
                ->onDelete("restrict")
                ->onUpdate("restrict");

            $table->index("method_payments_id", "idx_buys_method_payments_id");
            $table->index("users_id", "idx_buys_users_id");
        });

        DB::statement("ALTER TABLE buys COMMENT = 'Tabela que armazena informações sobre a compra/carrinho'");
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('stores');
    // }
};
