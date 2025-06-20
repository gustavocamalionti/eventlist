<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Libs\Enums\Systems\Master\EnumMasterRoles;

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

        $passwordAdmin = "12345678";
        $passwordManager = "123456789";

        $users = [
            [
                "name" => "Gustavo Gomes",
                "email" => "gustavogomes@eventlist.com.br",
                "roles_id" => EnumMasterRoles::ADMIN,
                "password" => Hash::make($passwordAdmin),
            ],
            [
                "name" => "Mariana Gomes",
                "email" => "marianagomes@eventlist.com.br",
                "roles_id" => EnumMasterRoles::ADMIN,
                "password" => Hash::make($passwordAdmin)
            ],
            [
                "name" => "Flávia Nunes",
                "email" => "flavianunes@eventlist.com.br",
                "roles_id" => EnumMasterRoles::MANAGER,
                "password" => Hash::make($passwordManager),
            ],
        ];
        DB::table("users")->insert($users);
    }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists("users");
    // }
};
