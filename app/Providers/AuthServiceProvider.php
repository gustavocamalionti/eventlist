<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use App\Models\Systems\Master\MasterUser;
use App\Models\Systems\Master\MasterPermission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        if (Schema::hasTable("users")) {
            $this->registerPolicies();
            $tenantPermissions = MasterPermission::with("roles")->get();

            foreach ($tenantPermissions as $permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    if (!$user instanceof MasterUser) {
                        return false; // ou lançar uma exceção
                    }
                    return $user->hasPermission($permission->name);
                });
            }
        }
    }
}
