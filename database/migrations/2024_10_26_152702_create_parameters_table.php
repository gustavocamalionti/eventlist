<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Systems\Master\MasterParameter;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("parameters", function (Blueprint $table) {
            $table->id()->autoIncrement()->comment("Identificador único dos parâmetros (sempre 1)");
            $table->string("official_site", 200)->nullable()->comment("URL do site oficial");
            $table->string("facebook_link", 200)->nullable()->comment("URL da página do Facebook");
            $table->string("instagram_link", 200)->nullable()->comment("URL da página do Instagram");
            $table->string("page_title", 50)->comment("Título da página");
            $table->decimal("vouchers_price", 7, 2)->comment("Preço dos vouchers");
            $table->unsignedInteger("vouchers_limit")->default(999)->comment("Quantidade de vouchers por lojas");
            $table->unsignedInteger("gateway_id")->default(1)->comment("Gateway de pagamento ativo");
            $table->integer("apicep")->default(0)->comment("Código de API do CEP, padrão 0");
            $table->string("email_contact")->nullable()->comment("Email para contato");
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data da última atualização do registro");
        });

        MasterParameter::create([
            "id" => 1,
            "official_site" => "https://www.example.com",
            "facebook_link" => "https://www.facebook.com/example",
            "instagram_link" => "https://www.instagram.com/example",
            "page_title" => "Conexão com o Futuro",
            "vouchers_price" => 300,
            "vouchers_limit" => 999,
            "email_contact" => "suporte@amongtech.com.br",
            "gateway_id" => 2,
            "apicep" => 0,
        ]);

        DB::statement("ALTER TABLE parameters COMMENT = 'Tabela para armazenar parâmetros de configuração do sistema'");
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('parameters');
    // }
}
