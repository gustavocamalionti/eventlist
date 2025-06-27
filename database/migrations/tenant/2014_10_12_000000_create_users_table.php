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
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("name")->comment("Nome completo do usuário");
            $table->string("cpf", 11)->nullable()->unique("users_cpf")->comment("CPF do usuário");
            $table->string("email")->unique("users_email_unique")->comment("Email único do usuário");
            $table->string("phone_cell", 20)->nullable()->comment("Telefone do usuário");
            $table->string("gender", 1)->nullable()->comment("Sexo do usuário");
            $table->date("date_birth")->nullable()->comment("Data de nascimento do usuário");
            $table->string("zipcode")->nullable()->comment("CEP do endereço do usuário");
            $table->string("address")->nullable()->comment("Endereço do usuário");
            $table->string("number")->nullable()->comment("Número do endereço");
            $table->string("district")->nullable()->comment("Bairro do usuário");
            $table->string("complement")->nullable()->comment("Complemento do endereço");
            $table
                ->unsignedBigInteger("cities_id")
                ->nullable()
                ->comment("ID da cidade, relacionado à tabela de cidades");
            $table->unsignedInteger("roles_id")->index("users_roles_id_foreign")->comment("Chave estrangeira");
            $table->tinyInteger("permission_accept")->default(1)->comment("Permissão para usar seus dados e armazenar");
            $table
                ->tinyInteger("news_accept")
                ->default(0)
                ->comment("Permissão para enviar comunicados, noticias e propagandas");
            $table->tinyInteger("active")->default(1)->comment("Indica se o usuário está ativo ou inativo");
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->rememberToken()->comment("Token para lembrar a sessão do usuário");
            $table->timestamps();
        });
    }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists("users");
    // }
};
