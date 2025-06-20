<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table("permissions_x_roles")
            ->whereIn("id", [60, 62, 63])
            ->delete();
    }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     //
    // }
};
