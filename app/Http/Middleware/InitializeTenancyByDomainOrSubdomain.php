<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class InitializeTenancyByDomainOrSubdomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->isSubdomain($request->getHost())) {
            return app(InitializeTenancyBySubdomain::class)->handle($request, $next);
        } else {
            return app(InitializeTenancyByDomain::class)->handle($request, $next);
        }
    }

    protected function isSubdomain(string $hostname): bool
    {
        return Str::endsWith($hostname, config('tenancy.central_domains'));
    }
}
