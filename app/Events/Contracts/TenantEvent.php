<?php

namespace App\Events\Contracts;

use App\Models\Tenant;
use Illuminate\Queue\SerializesModels;

abstract class TenantEvent
{
    use SerializesModels;

    /** @var Tenant */
    public $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }
}
