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
        DB::table("permissions")->insert([
            [
                "name" => "read_log_webhooks",
                "label" => "Permissão para visualizar logs de webhooks do sistema",
                "order" => 4,
                "flag" => "logs",
                "created_at" => now(),
                "updated_at" => now(),
            ],
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
