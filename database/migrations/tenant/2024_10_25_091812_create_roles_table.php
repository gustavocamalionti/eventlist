<?php

use App\Libs\Enums\Systems\Tenant\EnumTenantRoles;
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
                "id" => EnumTenantRoles::ADMIN,
                "name" => "Administrador",
                "label" => "Usuário com acesso total ao sistema.",
                "dashboard_rel" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => EnumTenantRoles::OWNER,
                "name" => "Proprietário",
                "label" =>
                "Acesso integral a todas funcionalidades da plataforma (Não está incluído analise de log)",
                "dashboard_rel" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => EnumTenantRoles::OPERATOR,
                "name" => "Operador",
                "label" => "Usuário com acesso a alguns CRUD",
                "dashboard_rel" => 1,
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "id" => EnumTenantRoles::PROMOTER,
                "name" => "Promoter",
                "label" => "Usuário com acesso limitado a funções específicas",
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
