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
                "name" => "read_event_buys",
                "label" => "Permissão para visualizar vendas",
                "order" => 1,
                "flag" => "event",
                "created_at" => now(),
                "updated_at" => now(),
            ],
            [
                "name" => "read_event_vouchers",
                "label" => "Permissão para visualizar os convidados",
                "order" => 2,
                "flag" => "event",
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
