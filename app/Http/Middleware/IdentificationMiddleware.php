<?php

namespace App\Http\Middleware;

use Stancl\Tenancy\Contracts\TenantResolver;
use App\Tenancy;

abstract class IdentificationMiddleware
{
    /** @var callable */
    public static $onFail;

    /** @var Tenancy */
    protected $tenancy;

    /** @var TenantResolver */
    protected $resolver;

    public function initializeTenancy($request, $next, ...$resolverArguments)
    {
        try {
            $this->tenancy->initialize(
                $this->resolver->resolve(...$resolverArguments)
            );
        } catch (\Exception $e) {
            $onFail = static::$onFail ?? function ($e) {
                throw $e;
            };

            return $onFail($e, $request, $next);
        }

        return $next($request);
    }
}
