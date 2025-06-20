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
        Schema::table("log_emails", function (Blueprint $table) {
            $table
                ->integer("buys_id")
                ->nullable()
                ->comment("Se existir venda, então vincularemos ela a esse disparo de email.")
                ->after("users_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
