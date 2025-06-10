<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("log_audits", function (Blueprint $table) {
            $table->id();
            $table->string("route", 255)->comment("Rota associada ao log");
            $table->string("title", 100)->comment("Título do log");
            $table->string("table_name", 100)->nullable()->comment("Nome da tabela relacionada");
            $table->string("action", 100)->comment("Ação realizada");
            $table->string("method", 50)->comment("Método HTTP");
            $table->integer("users_id")->comment("Chave estrangeira");
            $table->string("users_name", 150)->comment("Nome do usuário associado");
            $table->string("users_email", 100)->comment("Email do usuário associado");
            $table->string("description", 255)->comment("Descrição do log");
            $table
                ->bigInteger("table_item_id")
                ->nullable()
                ->comment("ID do item na tabela, não necessariamente é uma chave estrangeira");
            $table->longText("what_heppened")->comment("Descrição do que aconteceu");
            $table->string("ip", 15)->comment("Endereço IP");
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data da última atualização do registro");
        });

        DB::statement("ALTER TABLE log_audits COMMENT = 'Tabela para registrar ações do usuário'");
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('log_actions');
    // }
}
