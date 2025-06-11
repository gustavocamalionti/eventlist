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
        DB::table("permissions_x_roles")->insert([
            // Administrador (Acesso total)
            ["permissions_id" => 30, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_event_buys
            ["permissions_id" => 31, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_event_vouchers

            // Gestor (sem acesso a logs)
            ["permissions_id" => 30, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_event_buys
            ["permissions_id" => 31, "roles_id" => 2, "created_at" => now(), "updated_at" => now()], // read_event_vouchers

            // Analista (sem acesso ao CRUD de usuários e aos audits de cada CRUD)
            ["permissions_id" => 31, "roles_id" => 3, "created_at" => now(), "updated_at" => now()], // read_event_vouchers
        ]);
    }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     //
    // }
};
