<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("log_emails", function (Blueprint $table) {
            $table->string("uuid", 150)->nullable()->comment("UUID único associado ao log de email");
            $table->string("job_title", 100)->nullable()->comment("Título do trabalho associado ao envio de email");
            $table->string("email", 150)->nullable()->comment("Endereço de email do destinatário");
            $table->integer("users_id")->nullable()->comment("ID do usuário que recebeu o email");
            $table
                ->integer("buys_id")
                ->nullable()
                ->comment("Se existir venda, então vincularemos ela a esse disparo de email.");
            $table->integer("customers_id")->nullable()->comment("ID do cliente que recebeu o email");
            $table->string("status", 50)->nullable()->comment("Status do envio do email");
            $table->string("details", 500)->nullable()->comment("Detalhes adicionais sobre o envio do email");
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data da última atualização do registro");
        });

        DB::statement("ALTER TABLE log_emails COMMENT = 'Tabela para registrar logs de emails enviados'");
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('log_emails');
    // }
}
