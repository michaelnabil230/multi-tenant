<?php

namespace App\Http\Middleware;

use App\Tenancy;

abstract class IdentificationMiddleware
{
    /** @var callable */
    public static $onFail;

    protected Tenancy $tenancy;

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
