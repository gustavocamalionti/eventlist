<?php

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
        Schema::create("roles", function (Blueprint $table) {
            $table->id();
            $table->string("name", 50)->nullable()->comment("Nome do nível de acesso");
            $table->string("label", 200)->nullable()->comment("Descrição do nível");
            $table
                ->tinyInteger("dashboard_rel")
                ->default(0)
                ->comment("Relatórios Gerais do Dashboard (0 - Não, 1 - Sim)");
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data de atualização do registro");
        });

        DB::statement(
            "ALTER TABLE roles COMMENT = 'Repositório de Níveis de Acesso. Aqui criamos os perfis e camadas de permissões.'"
        );

        DB::table("roles")->insert([
            [
                "name" => "Administrador",
                "label" => "Usuário com acesso total ao sistema.",
                "dashboard_rel" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "Gestor",
                "label" =>
                    "Usuário responsável com todos os acessos do analista e com CRUD de usuários e histórico de auditoria.",
                "dashboard_rel" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "Analista",
                "label" => "Usuário com acesso analítico, relatórios e CRUD.",
                "dashboard_rel" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('roles');
    // }
};
