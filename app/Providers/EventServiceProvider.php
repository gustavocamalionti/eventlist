<?php

namespace App\Providers;

use App\Models\Link;
use App\Models\Master\User;
use App\Models\Store;
use App\Models\Banner;
use App\Models\Parameter;
use App\Models\FormConfig;
use App\Observers\LinkObserver;
use App\Observers\UserObserver;
use App\Observers\StoreObserver;
use App\Observers\BannerObserver;
use App\Observers\ParameterObserver;
use App\Observers\FormConfigObserver;
use Illuminate\Auth\Events\Registered;
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
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // User::observe(UserObserver::class);
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
