<?php


namespace App\Exceptions;

use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;


class TenantCouldNotBeIdentifiedByRequestDataException extends \Exception implements ProvidesSolution
{
    public function __construct($tenant_id)
    {
        parent::__construct("Tenant could not be identified by request data with payload: $tenant_id");
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('Tenant could not be identified with this request data')
            ->setSolutionDescription('Did you forget to create a tenant with this id?');
    }
}
