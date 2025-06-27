<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Models\Systems\Master\MasterUser;
use App\Models\Systems\Tenant\TenantUser;
use App\Observers\Systems\Master\MasterUserObserver;
use App\Observers\Systems\Tenant\TenantUserObserver;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [SendEmailVerificationNotification::class],
        \Stancl\Tenancy\Events\TenancyBootstrapped::class => [\App\Listeners\RegisterTenantPermissions::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        TenantUser::observe(TenantUserObserver::class);
        MasterUser::observe(MasterUserObserver::class);
        // Parameter::observe(ParameterObserver::class);
        // Banner::observe(BannerObserver::class);
        // Store::observe(StoreObserver::class);
        // Link::observe(LinkObserver::class);
        // FormConfig::observe(FormConfigObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
