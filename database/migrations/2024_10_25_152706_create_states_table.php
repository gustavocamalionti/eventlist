<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("states", function (Blueprint $table) {
            $table->unsignedInteger("id")->primary()->comment("Identificador único do estado");
            $table->string("name", 50)->comment("Nome do estado");
            $table->char("initials", 2)->comment("Iniciais do estado");
        });

        $states = [
            ["id" => 11, "name" => "Rondônia", "initials" => "RO"],
            ["id" => 12, "name" => "Acre", "initials" => "AC"],
            ["id" => 13, "name" => "Amazonas", "initials" => "AM"],
            ["id" => 14, "name" => "Roraima", "initials" => "RR"],
            ["id" => 15, "name" => "Pará", "initials" => "PA"],
            ["id" => 16, "name" => "Amapá", "initials" => "AP"],
            ["id" => 17, "name" => "Tocantins", "initials" => "TO"],
            ["id" => 21, "name" => "Maranhão", "initials" => "MA"],
            ["id" => 22, "name" => "Piauí", "initials" => "PI"],
            ["id" => 23, "name" => "Ceará", "initials" => "CE"],
            ["id" => 24, "name" => "Rio Grande do Norte", "initials" => "RN"],
            ["id" => 25, "name" => "Paraíba", "initials" => "PB"],
            ["id" => 26, "name" => "Pernambuco", "initials" => "PE"],
            ["id" => 27, "name" => "Alagoas", "initials" => "AL"],
            ["id" => 28, "name" => "Sergipe", "initials" => "SE"],
            ["id" => 29, "name" => "Bahia", "initials" => "BA"],
            ["id" => 31, "name" => "Minas Gerais", "initials" => "MG"],
            ["id" => 32, "name" => "Espírito Santo", "initials" => "ES"],
            ["id" => 33, "name" => "Rio de Janeiro", "initials" => "RJ"],
            ["id" => 35, "name" => "São Paulo", "initials" => "SP"],
            ["id" => 41, "name" => "Paraná", "initials" => "PR"],
            ["id" => 42, "name" => "Santa Catarina", "initials" => "SC"],
            ["id" => 43, "name" => "Rio Grande do Sul", "initials" => "RS"],
            ["id" => 50, "name" => "Mato Grosso do Sul", "initials" => "MS"],
            ["id" => 51, "name" => "Mato Grosso", "initials" => "MT"],
            ["id" => 52, "name" => "Goiás", "initials" => "GO"],
            ["id" => 53, "name" => "Distrito Federal", "initials" => "DF"],
        ];

        DB::statement("ALTER TABLE states COMMENT = 'Tabela para armazenar estados'");

        DB::table("states")->insert($states);
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('states');
    // }
}
