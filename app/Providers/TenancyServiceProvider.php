<?php

namespace App\Providers;

use App\Events;
use App\Http\Middleware\MiddlewareT\InitializeTenancyByDomain;
use App\Http\Middleware\MiddlewareT\InitializeTenancyByDomainOrSubdomain;
use App\Http\Middleware\MiddlewareT\InitializeTenancyByPath;
use App\Http\Middleware\MiddlewareT\InitializeTenancyByRequestData;
use App\Http\Middleware\MiddlewareT\InitializeTenancyBySubdomain;
use App\Http\Middleware\MiddlewareT\PreventAccessFromCentralDomains;
use App\Listeners;
use App\Models\Domain;
use App\Models\Tenant;
use App\Resolvers\DomainTenantResolver;
use App\Tenancy;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;

class TenancyServiceProvider extends ServiceProvider
{
    // By default, no namespace is used to support the callable array syntax.
    public static string $controllerNamespace = '';

    public function events()
    {
        return [
            // Tenant events
            Events\CreatingTenant::class => [],
            Events\TenantCreated::class => [],
            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class => [],

            // Domain events
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class => [],
            Events\SavingDomain::class => [],
            Events\DomainSaved::class => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class => [],

            // Tenancy events
            Events\InitializingTenancy::class => [],
            Events\TenancyInitialized::class => [
                Listeners\BootstrapTenancy::class,
            ],

            Events\EndingTenancy::class => [],
            Events\TenancyEnded::class => [],

            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [],
        ];
    }

    public function register()
    {
        // Make sure Tenancy is stateful.
        $this->app->singleton(Tenancy::class);

        // Make it possible to inject the current tenant by typehinting the Tenant contract.
        $this->app->bind(Tenant::class, function ($app) {
            return $app[Tenancy::class]->tenant;
        });

        $this->app->bind(Domain::class, function () {
            return DomainTenantResolver::$currentDomain;
        });

        // Make sure bootstrappers are stateful (singletons).
        foreach ($this->app['config']['tenancy.bootstrappers'] ?? [] as $bootstrapper) {
            if (method_exists($bootstrapper, '__constructStatic')) {
                $bootstrapper::__constructStatic($this->app);
            }

            $this->app->singleton($bootstrapper);
        }

        $this->app->bind('globalCache', function ($app) {
            return new CacheManager($app);
        });
    }

    public function boot()
    {
        $this->bootEvents();
        $this->mapRoutes();
        $this->telescope();

        $this->makeTenancyMiddlewareHighestPriority();
    }

    protected function bootEvents()
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }

    protected function telescope()
    {
        if (! class_exists(Telescope::class)) {
            return;
        }

        Telescope::tag(function (IncomingEntry $entry) {
            $tags = [];

            if (! request()->route()) {
                return $tags;
            }

            if (tenancy()->initialized) {
                $tags = [
                    'tenant:' . tenant('id'),
                ];
            }

            return $tags;
        });
    }

    protected function mapRoutes()
    {
        Route::middleware('web')->group(base_path('routes/tenant.php'));
        Route::middleware('api')->prefix('api')->group(base_path('routes/tenant-api.php'));
    }

    protected function makeTenancyMiddlewareHighestPriority()
    {
        $tenancyMiddleware = [
            // Even higher priority than the initialization middleware
            PreventAccessFromCentralDomains::class,

            InitializeTenancyByDomain::class,
            InitializeTenancyBySubdomain::class,
            InitializeTenancyByDomainOrSubdomain::class,
            InitializeTenancyByPath::class,
            InitializeTenancyByRequestData::class,
        ];

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $this->app[\Illuminate\Contracts\Http\Kernel::class]->prependToMiddlewarePriority($middleware);
        }
    }
}
