<?php

namespace App\Providers;

use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Contracts\Tenant;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use App\Utilitaries\SingleDomainTenant;
use Illuminate\Support\ServiceProvider;
use Stancl\Tenancy\Events\TenancyEnded;
use App\Utilitaries\RootUrlBootstrapper;
use App\Models\Systems\Master\MasterUser;
use App\Models\Systems\Tenant\TenantUser;
use Stancl\Tenancy\Events\TenancyBootstrapped;
use App\Jobs\Systems\Master\General\TenantCreated\CreateUsersJob;

class TenancyServiceProvider extends ServiceProvider
{
    // By default, no namespace is used to support the callable array syntax.
    public static string $controllerNamespace = "";

    public function events()
    {
        return [
            // Tenant events
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [
                JobPipeline::make([
                    Jobs\CreateDatabase::class,
                    Jobs\MigrateDatabase::class,
                    // Jobs\SeedDatabase::class,
                    CreateUsersJob::class,

                    // Your own jobs to prepare the tenant.
                    // Provision API keys, create S3 buckets, anything you want!
                ])
                    ->send(function (Events\TenantCreated $event) {
                        return $event->tenant;
                    })
                    ->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],
            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class => [
                JobPipeline::make([Jobs\DeleteDatabase::class])
                    ->send(function (Events\TenantDeleted $event) {
                        return $event->tenant;
                    })
                    ->shouldBeQueued(false), // `false` by default, but you probably want to make this `true` for production.
            ],

            // Domain events
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class => [],
            Events\SavingDomain::class => [],
            Events\DomainSaved::class => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class => [],

            // Database events
            Events\DatabaseCreated::class => [],
            Events\DatabaseMigrated::class => [],
            Events\DatabaseSeeded::class => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class => [],

            // Tenancy events
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class => [Listeners\BootstrapTenancy::class],

            Events\EndingTenancy::class => [],
            Events\TenancyEnded::class => [Listeners\RevertToCentralContext::class],

            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class => [],

            // Resource syncing
            Events\SyncedResourceSaved::class => [Listeners\UpdateSyncedResource::class],

            // Fired only when a synced resource is changed in a different DB than the origin DB (to avoid infinite loops)
            Events\SyncedResourceChangedInForeignDatabase::class => [],
        ];
    }

    public function register() {}

    // NOTA: AGUARDAR V4 de stancl/tenancy
    protected function overrideUrlInTenantContext(): void
    {
        RootUrlBootstrapper::$rootUrlOverride = function (Tenant $tenant, string $originalRootUrl) {
            $tenantDomain = $tenant instanceof SingleDomainTenant
                ? $tenant->domain
                : $tenant->domains->first()->domain;

            // Parse a URL usando parse_url
            $parsedUrl = parse_url($originalRootUrl);
            $scheme = $parsedUrl['scheme'] ?? 'http';
            $host = $parsedUrl['host'] ?? '';
            $port = isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '';
            $path = $parsedUrl['path'] ?? '/';

            if (str_contains($tenantDomain, '.')) {
                // Domínio direto (ex: empresa.com)
                return "{$scheme}://{$tenantDomain}{$port}/";
            } else {
                // Subdomínio (ex: empresa.localhost)
                return "{$scheme}://{$tenantDomain}.{$host}{$port}/";
            }
        };
    }

    public function boot()
    {
        $this->bootEvents();
        $this->mapRoutes();
        $this->makeTenancyMiddlewareHighestPriority();

        // NOTA: AGUARDAR V4 de stancl/tenancy
        $this->overrideUrlInTenantContext();

        Event::listen(TenancyBootstrapped::class, function () {
            Config::set("auth.providers.users.model", TenantUser::class);
        });

        Event::listen(TenancyEnded::class, function () {
            Config::set("auth.providers.users.model", MasterUser::class);
        });
    }

    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }

                Event::listen($event, $listener);
            }
        }
    }

    protected function mapRoutes()
    {
        $this->app->booted(function () {
            if (file_exists(base_path("routes/systems/tenant/general/routes-tenant.php"))) {
                Route::namespace(static::$controllerNamespace)->group(
                    base_path("routes/systems/tenant/general/routes-tenant.php")
                );
            }
        });
    }

    protected function makeTenancyMiddlewareHighestPriority()
    {
        $tenancyMiddleware = [
            // Even higher priority than the initialization middleware
            Middleware\PreventAccessFromCentralDomains::class,

            Middleware\InitializeTenancyByDomain::class,
            Middleware\InitializeTenancyBySubdomain::class,
            Middleware\InitializeTenancyByDomainOrSubdomain::class,
            Middleware\InitializeTenancyByPath::class,
            Middleware\InitializeTenancyByRequestData::class,
        ];

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]->prependToMiddlewarePriority($middleware);
        }
    }
}
