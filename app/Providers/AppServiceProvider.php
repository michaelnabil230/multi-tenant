<?php

namespace App\Providers;

use App\Events\TenancyBootstrapped;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blueprint::macro('tenant', function () {
            $this->foreignUuid('tenant_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });

        Event::listen(TenancyBootstrapped::class, function (TenancyBootstrapped $event) {
            // $prefix = config('cache.prefix') . '_' . tenant()->getTenantKey();
            // config([
            //     'cache.prefix' => $prefix,
            // ]);
            // app('cache')->forgetDriver(config('cache.default'));
            // PermissionRegistrar::$cacheKey = 'spatie.permission.cache.tenant.' . $event->tenancy->tenant->id;
            // DatabaseSettingStore::$cacheKey = 'setting.cache.tenant.' . $event->tenancy->tenant->id;
        });

        \App\Http\Middleware\InitializeTenancyByDomain::$onFail = function ($exception, $request, $next) {
            return redirect(config('app.url'));
        };
    }
}
