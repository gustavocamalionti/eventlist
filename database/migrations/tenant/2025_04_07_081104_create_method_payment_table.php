<?php

use App\Libs\Enums\EnumStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\Systems\Tenant\MethodPayment;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("method_payments", function (Blueprint $table) {
            $table->id();
            $table->string("name", 20)->comment("Nome do método de pagamento");
            $table->string("asaas", 20)->comment("Nome do método deve ser passado para o gateway asaas");
            $table->tinyInteger("active")->default(1)->comment("Define o status do método de pagamento");
            $table->timestamps();

            $table->unique("name")->index("uq_method_payments_name");
        });

        DB::statement(
            "ALTER TABLE method_payments COMMENT 'Tabela que armazena os métodos de pagamento para realizar cobranças.' "
        );

        MethodPayment::create([
            "id" => 1,
            "name" => "PIX",
            "asaas" => "PIX",
            "active" => EnumStatus::ACTIVE,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
        MethodPayment::create([
            "id" => 2,
            "name" => "Cartão de Crédito",
            "asaas" => "CREDIT_CARD",
            "active" => EnumStatus::ACTIVE,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
        MethodPayment::create([
            "id" => 3,
            "name" => "Boleto",
            "asaas" => "BOLETO",
            "active" => EnumStatus::INACTIVE,
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists('method_payment');
    // }
};
