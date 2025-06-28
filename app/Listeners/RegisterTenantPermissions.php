<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Models\Systems\Tenant\TenantUser;
use Stancl\Tenancy\Events\TenancyBootstrapped;
use App\Models\Systems\Tenant\TenantPermission;

class RegisterTenantPermissions
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TenancyBootstrapped $event): void
    {
        if (Schema::hasTable("users")) {
            // Registra as permissões do tenant após tenancy inicializada
            $permissions = TenantPermission::with("roles")->get();

            foreach ($permissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    if (!$user instanceof TenantUser) {
                        return false; // ou lançar uma exceção
                    }
                    return $user->hasPermission($permission->name);
                });
            }
        }
    }
}
