<?php

namespace App\Events\Contracts;

use App\Tenancy;

abstract class TenancyEvent
{
    public Tenancy $tenancy;

    public function __construct(Tenancy $tenancy)
    {
        $this->tenancy = $tenancy;
    }
}
