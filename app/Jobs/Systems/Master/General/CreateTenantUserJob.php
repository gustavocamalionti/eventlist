<?php

namespace App\Jobs\Systems\Master\General;

use App\Jobs\TemplateJob;
use Illuminate\Support\Str;
use App\Models\Systems\Master\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CreateTenantUserJob extends TemplateJob
{
    /**
     * Método chamado pelo TemplateJob para executar a lógica
     */
    public function object($tenant)
    {


        $password = Str::uuid(Str::random(12));
        Log::info($password);
        $user = User::create([
            'name' => $tenant->name,
            'email' => $tenant->email,
            'password' => Hash::make($password),
            'tenants_id' => $tenant->id,
        ]);

        // Se quiser enviar e-mail com a senha para o usuário
        // Mail::to($user->email)->send(new TenantUserCreated($user, $password));
    }
}
