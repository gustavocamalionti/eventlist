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
        Schema::create("permissions_x_roles", function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId("permissions_id")
                ->nullable()
                ->constrained("permissions")
                ->onDelete("cascade")
                ->onUpdate("restrict")
                ->comment("Chave estrangeira");
            $table
                ->foreignId("roles_id")
                ->nullable()
                ->constrained("roles")
                ->onDelete("cascade")
                ->onUpdate("restrict")
                ->comment("Chave estrangeira");
            $table->timestamp("created_at")->nullable()->comment("Data de criação do registro");
            $table->timestamp("updated_at")->nullable()->comment("Data de atualização do registro");
        });

        DB::statement("ALTER TABLE permissions_x_roles COMMENT = 'Tabela de associação entre permissões e funções'");
        DB::table("permissions_x_roles")->insert([
            // Administrador (Acesso total)
            ["permissions_id" => 1, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // create_users
            ["permissions_id" => 2, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_users
            ["permissions_id" => 3, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // update_users
            ["permissions_id" => 4, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // delete_users
            ["permissions_id" => 5, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_users_audit
            ["permissions_id" => 6, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // create_banners
            ["permissions_id" => 7, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_banners
            ["permissions_id" => 8, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // update_banners
            ["permissions_id" => 9, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // delete_banners
            ["permissions_id" => 10, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_banners_audit
            ["permissions_id" => 11, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // create_links
            ["permissions_id" => 12, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_links
            ["permissions_id" => 13, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // update_links
            ["permissions_id" => 14, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // delete_links
            ["permissions_id" => 15, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_links_audit
            ["permissions_id" => 16, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_form_contents_contact
            ["permissions_id" => 17, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // create_form_configs
            ["permissions_id" => 18, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_form_configs
            ["permissions_id" => 19, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // update_form_configs
            ["permissions_id" => 20, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // delete_form_configs
            ["permissions_id" => 21, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_form_configs_audit
            ["permissions_id" => 22, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // create_stores
            ["permissions_id" => 23, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_stores
            ["permissions_id" => 24, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // update_stores
            ["permissions_id" => 25, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // delete_stores
            ["permissions_id" => 26, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_stores_audit
            ["permissions_id" => 27, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_log_audits
            ["permissions_id" => 28, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_log_emails
            ["permissions_id" => 29, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_log_errors

            // Gestor (sem acesso a logs)
            ["permissions_id" => 1, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // create_users
            ["permissions_id" => 2, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_users
            ["permissions_id" => 3, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // update_users
            ["permissions_id" => 4, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // delete_users
            ["permissions_id" => 5, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_users_audit
            ["permissions_id" => 6, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // create_banners
            ["permissions_id" => 7, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_banners
            ["permissions_id" => 8, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // update_banners
            ["permissions_id" => 9, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // delete_banners
            ["permissions_id" => 10, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_banners_audit
            ["permissions_id" => 11, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // create_links
            ["permissions_id" => 12, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_links
            ["permissions_id" => 13, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // update_links
            ["permissions_id" => 14, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // delete_links
            ["permissions_id" => 15, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_links_audit
            ["permissions_id" => 16, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_form_contents_contact
            ["permissions_id" => 22, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // create_stores
            ["permissions_id" => 23, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_stores
            ["permissions_id" => 24, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // update_stores
            ["permissions_id" => 25, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // delete_stores
            ["permissions_id" => 26, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_stores_audit

            // Analista (sem acesso ao CRUD de usuários e aos audits de cada CRUD)
            ["permissions_id" => 6, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // create_banners
            ["permissions_id" => 7, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // read_banners
            ["permissions_id" => 8, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // update_banners
            ["permissions_id" => 9, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // delete_banners
            ["permissions_id" => 11, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // create_links
            ["permissions_id" => 12, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // read_links
            ["permissions_id" => 13, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // update_links
            ["permissions_id" => 14, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // delete_links
            ["permissions_id" => 16, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // read_form_contents_contact
            ["permissions_id" => 22, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // create_stores
            ["permissions_id" => 23, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // read_stores
            ["permissions_id" => 24, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // update_stores
            ["permissions_id" => 25, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // delete_stores
        ]);
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::dropIfExists('permissions_x_roles');
    // }
};
