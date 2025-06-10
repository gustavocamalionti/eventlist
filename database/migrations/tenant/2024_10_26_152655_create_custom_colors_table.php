<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("custom_colors", function (Blueprint $table) {
            $table->id()->autoIncrement()->comment("Identificador único para cores personalizadas");
            $table->string("key")->comment("Chave da configuração");
            $table->string("value")->default("#000000")->comment("Valor da configuração");
            $table->enum("type", ["style", "content"])->comment("Tipo da configuração");
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data da última atualização do registro");
        });

        DB::statement("ALTER TABLE custom_colors COMMENT = 'Tabela para armazenar cores personalizadas'");

        DB::table("custom_colors")->insert([
            ["key" => "black_color", "value" => "#000000", "type" => "style"],
            ["key" => "white_color", "value" => "#ffffff", "type" => "style"],
            ["key" => "primary_color", "value" => "#11202b", "type" => "style"],
            ["key" => "secondary_color", "value" => "#20B5DE", "type" => "style"],
            ["key" => "tertiary_color", "value" => "#F6AE00", "type" => "style"],
        ]);
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('custom_colors');
    // }
}
