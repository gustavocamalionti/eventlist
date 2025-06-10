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
        Schema::create("permissions", function (Blueprint $table) {
            $table->id();
            $table
                ->string("name", 50)
                ->nullable()
                ->comment("Nome do arquivo (view) onde atribuiremos acessos a determinados níveis");
            $table->string("label", 200)->nullable()->comment("Descrição da tela");
            $table->tinyInteger("order")->default(1)->comment("Indica ordem de prioridade");
            $table->string("flag", 255)->default("outras")->comment("Identificar o grupo de segmento");
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data de atualização do registro");
        });

        DB::statement(
            "ALTER TABLE permissions COMMENT = 'Repositório das permissões. Nele especificamos todas as view onde aplicaremos a lógica de regras para níveis de acessos.'"
        );

        DB::table("permissions")->insert([
            [
                "name" => "create_users",
                "label" => "Permissão para criar um novo usuário no sistema",
                "order" => 1,
                "flag" => "users",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_users",
                "label" => "Permissão para visualizar a lista e detalhes dos usuários",
                "order" => 2,
                "flag" => "users",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "update_users",
                "label" => "Permissão para editar informações dos usuários",
                "order" => 3,
                "flag" => "users",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "delete_users",
                "label" => "Permissão para excluir usuários",
                "order" => 4,
                "flag" => "users",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_users_audit",
                "label" => "Permissão para visualizar histórico de auditoria para usuários",
                "order" => 5,
                "flag" => "users",
                "created_at" => now(),
                "updated_at" => now(),
            ],

            [
                "name" => "create_banners",
                "label" => "Permissão para criar banners de publicidade",
                "order" => 1,
                "flag" => "banners",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_banners",
                "label" => "Permissão para visualizar banners cadastrados",
                "order" => 2,
                "flag" => "banners",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "update_banners",
                "label" => "Permissão para editar banners existentes",
                "order" => 3,
                "flag" => "banners",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "delete_banners",
                "label" => "Permissão para remover banners do sistema",
                "order" => 4,
                "flag" => "banners",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_banners_audit",
                "label" => "Permissão para visualizar histórico de auditoria para banners",
                "order" => 5,
                "flag" => "banners",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "create_links",
                "label" => "Permissão para criar links de cardápio digital",
                "order" => 1,
                "flag" => "links",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_links",
                "label" => "Permissão para visualizar os links de cardápio digital",
                "order" => 2,
                "flag" => "links",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "update_links",
                "label" => "Permissão para editar links de cardápio digital",
                "order" => 3,
                "flag" => "links",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "delete_links",
                "label" => "Permissão para excluir links de cardápio digital",
                "order" => 4,
                "flag" => "links",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_links_audit",
                "label" => "Permissão para visualizar histórico de auditoria para links do cardápio digital",
                "order" => 5,
                "flag" => "links",
                "created_at" => now(),
                "updated_at" => now(),
            ],

            [
                "name" => "read_form_contents_contact",
                "label" => "Permissão para visualizar mensagens do formulário de contato",
                "order" => 1,
                "flag" => "forms",
                "created_at" => now(),
                "updated_at" => now(),
            ],

            [
                "name" => "create_form_configs",
                "label" => "Permissão para criar configurações do formulário",
                "order" => 1,
                "flag" => "forms",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_form_configs",
                "label" => "Permissão para visualizar configurações do formulário",
                "order" => 2,
                "flag" => "forms",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "update_form_configs",
                "label" => "Permissão para editar configurações do formulário",
                "order" => 3,
                "flag" => "forms",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "delete_form_configs",
                "label" => "Permissão para excluir configurações do formulário",
                "order" => 4,
                "flag" => "forms",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_form_configs_audit",
                "label" => "Permissão para visualizar histórico de auditoria para configurações do formulário",
                "order" => 5,
                "flag" => "forms",
                "created_at" => now(),
                "updated_at" => now(),
            ],

            [
                "name" => "create_stores",
                "label" => "Permissão para criar lojas",
                "order" => 1,
                "flag" => "stores",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_stores",
                "label" => "Permissão para visualizar lojas",
                "order" => 2,
                "flag" => "stores",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "update_stores",
                "label" => "Permissão para editar informações das lojas",
                "order" => 3,
                "flag" => "stores",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "delete_stores",
                "label" => "Permissão para excluir lojas",
                "order" => 4,
                "flag" => "stores",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_stores_audit",
                "label" => "Permissão para visualizar histórico de auditoria para lojas",
                "order" => 5,
                "flag" => "stores",
                "created_at" => now(),
                "updated_at" => now(),
            ],

            [
                "name" => "read_log_audits",
                "label" => "Permissão para visualizar auditoria de logs",
                "order" => 1,
                "flag" => "logs",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_log_emails",
                "label" => "Permissão para visualizar logs de e-mails enviados",
                "order" => 2,
                "flag" => "logs",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_log_errors",
                "label" => "Permissão para visualizar logs de erros do sistema",
                "order" => 3,
                "flag" => "logs",
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
    //     Schema::dropIfExists('permissions');
    // }
};
