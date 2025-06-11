<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("log_errors", function (Blueprint $table) {
            $table->id()->autoIncrement()->comment("Identificador único do log de erro");
            $table->integer("status")->comment("Código de status do erro");
            $table->string("action", 50)->nullable()->comment("Ação durante a qual o erro ocorreu");
            $table->string("route")->comment("Rota do aplicativo onde o erro ocorreu");
            $table->string("title", 100)->comment("Título descritivo do erro");
            $table->string("method", 50)->comment("Método HTTP utilizado durante a ação que causou o erro");
            $table->integer("users_id")->nullable()->comment("ID do usuário que estava ativo durante o erro");
            $table->string("users_name", 150)->nullable()->comment("Nome do usuário que estava ativo durante o erro");
            $table->string("users_email", 100)->nullable()->comment("Email do usuário que estava ativo durante o erro");
            $table->text("payload")->comment("Feedback completo do erro");
            $table->text("message")->comment("Mensagem de erro detalhada");
            $table->string("ip", 15)->comment("Endereço IP do usuário no momento do erro");
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data da última atualização do registro");
        });

        DB::statement("ALTER TABLE log_errors COMMENT = 'Tabela para registrar logs de erros'");
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('log_errors');
    // }
}
