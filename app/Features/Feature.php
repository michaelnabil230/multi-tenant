<?php

namespace App\Features;

use App\Tenancy;

/** Additional features, like Telescope tags and tenant redirects. */
interface Feature
{
    public function bootstrap(Tenancy $tenancy): void;
}
