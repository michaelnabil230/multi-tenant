<?php


namespace App\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class TenantCouldNotBeIdentifiedByPathException extends \Exception implements ProvidesSolution
{
    public function __construct($tenant_id)
    {
        parent::__construct("Tenant could not be identified on path with tenant_id: $tenant_id");
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('Tenant could not be identified on this path')
            ->setSolutionDescription('Did you forget to create a tenant for this path?');
    }
}
