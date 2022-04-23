<?php

namespace App\Listeners;

use App\Events\BootstrappingTenancy;
use App\Events\TenancyBootstrapped;
use App\Events\TenancyInitialized;

class BootstrapTenancy
{
    public function handle(TenancyInitialized $event)
    {
        event(new BootstrappingTenancy($event->tenancy));

        foreach ($event->tenancy->getBootstrappers() as $bootstrapper) {
            $bootstrapper->bootstrap($event->tenancy->tenant);
        }

        event(new TenancyBootstrapped($event->tenancy));
    }
}
