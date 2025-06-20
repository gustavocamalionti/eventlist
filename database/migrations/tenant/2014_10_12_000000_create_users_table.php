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
        Schema::create("users", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->unsignedInteger("roles_id")->index("users_roles_id_foreign")->comment("Chave estrangeira");
            $table->tinyInteger("active")->default(1)->comment("Indica se o usuário está ativo ou inativo");
            $table->timestamp("email_verified_at")->nullable();
            $table->string("password");
            $table->rememberToken();
            $table->timestamps();
        });
    }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists("users");
    // }
};
