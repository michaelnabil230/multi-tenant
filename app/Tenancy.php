<?php

namespace App;

use App\Exceptions\TenantCouldNotBeIdentifiedById;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Traits\Macroable;

class Tenancy
{
    use Macroable;

    /** @var Tenant|Model|null */
    public $tenant;

    /** @var bool */
    public $initialized = false;

    /**
     * Initializes the tenant.
     * @param Tenant|int|string $tenant
     * @return void
     */
    public function initialize($tenant): void
    {
        if (! is_object($tenant)) {
            $tenantId = $tenant;
            $tenant = $this->find($tenantId);

            if (! $tenant) {
                throw new TenantCouldNotBeIdentifiedById($tenantId);
            }
        }

        if ($this->initialized && $this->tenant->getTenantKey() === $tenant->getTenantKey()) {
            return;
        }

        if ($this->initialized) {
            $this->end();
        }

        $this->tenant = $tenant;

        event(new Events\InitializingTenancy($this));

        $this->initialized = true;

        event(new Events\TenancyInitialized($this));
    }

    public function end(): void
    {
        event(new Events\EndingTenancy($this));

        if (! $this->initialized) {
            return;
        }

        event(new Events\TenancyEnded($this));

        $this->initialized = false;

        $this->tenant = null;
    }

    /** @return TenancyBootstrapper[] */
    public function getBootstrappers(): array
    {
        // If no callback for getting bootstrappers is set, we just return all of them.
        $resolve = function (Tenant $tenant) {
            return config('tenancy.bootstrappers');
        };

        // Here We instantiate the bootstrappers and return them.
        return array_map('app', $resolve($this->tenant));
    }

    public function query(): Builder
    {
        return $this->model()->query();
    }

    /** @return Tenant|Model */
    public function model()
    {
        return new Tenant();
    }

    public function find($id): ?Tenant
    {
        return $this->model()->where($this->model()->getTenantKeyName(), $id)->first();
    }
}
