<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table("permissions_x_roles")->insert([
            // Administrador
            ["permissions_id" => 32, "roles_id" => 1, "created_at" => now(), "updated_at" => now()], // read_log_webhooks
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
