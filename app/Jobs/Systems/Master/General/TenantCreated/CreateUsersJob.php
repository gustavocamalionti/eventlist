<?php

namespace App\Jobs\Systems\Master\General\TenantCreated;

use App\Jobs\TemplateJob;
use Illuminate\Support\Str;

use App\Models\Systems\Tenant\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Libs\Enums\Systems\Tenant\EnumTenantRoles;

class CreateUsersJob extends TemplateJob
{
    /**
     * Método chamado pelo TemplateJob para executar a lógica
     */
    public function object($tenant)
    {
        $passwordAdmin = Str::uuid(Str::random(12));
        $passwordOwner = Str::uuid(Str::random(12));

        Log::info("Tenant: " . $tenant->name . " | Senha Admin: " . $passwordAdmin);
        Log::info("Tenant: " . $tenant->name . " | Senha Owner: " . $passwordOwner);
        $tenant->run(function ($tenant) use ($passwordAdmin, $passwordOwner) {
            $userAdmin = User::create([
                "name" => "Eventlist",
                "email" => "suporte@eventlist.com.br",
                "password" => Hash::make($passwordAdmin),
                "tenants_id" => $tenant->id,
                "roles_id" => EnumTenantRoles::ADMIN,
            ]);

            $userOwner = User::create([
                "name" => $tenant->name,
                "email" => $tenant->email,
                "password" => Hash::make($passwordOwner),
                "tenants_id" => $tenant->id,
                "roles_id" => EnumTenantRoles::OWNER,
            ]);
            // Log::info($userAdmin);
            // Log::info($userOwner);
        });
        // Se quiser enviar e-mail com a senha para o usuário
        // Mail::to($user->email)->send(new TenantUserCreated($user, $password));
    }
}
