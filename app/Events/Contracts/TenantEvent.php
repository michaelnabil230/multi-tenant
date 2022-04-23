<?php

namespace App\Events\Contracts;

use Illuminate\Queue\SerializesModels;
use App\Models\Tenant;

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
