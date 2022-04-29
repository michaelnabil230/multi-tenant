<?php


namespace App\Exceptions;

use Exception;
use App\Resolvers\PathTenantResolver;

class RouteIsMissingTenantParameterException extends Exception
{
    public function __construct()
    {
        $parameter = PathTenantResolver::$tenantParameterName;

        parent::__construct("The route's first argument is not the tenant id (configured parameter name: $parameter).");
    }
}
