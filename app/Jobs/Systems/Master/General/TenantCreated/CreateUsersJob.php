<?php

namespace App\Jobs\Systems\Master\General\TenantCreated;

use App\Jobs\TemplateJob;
use Illuminate\Support\Str;
use App\Models\Systems\Master\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CreateUsersJob extends TemplateJob
{
    /**
     * Método chamado pelo TemplateJob para executar a lógica
     */
    public function object($tenant)
    {
        $password = Str::uuid(Str::random(12));

        $user = User::create([
            "name" => $tenant->name,
            "email" => $tenant->email,
            "password" => Hash::make($password),
            "tenants_id" => $tenant->id,
        ]);

        // Se quiser enviar e-mail com a senha para o usuário
        // Mail::to($user->email)->send(new TenantUserCreated($user, $password));
    }
}
