<?php


namespace App\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;

class TenantCouldNotBeIdentifiedById extends \Exception implements ProvidesSolution
{
    public function __construct($tenant_id)
    {
        parent::__construct("Tenant could not be identified with tenant_id: $tenant_id");
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('Tenant could not be identified with that ID')
            ->setSolutionDescription('Are you sure the ID is correct and the tenant exists?');
    }
}
