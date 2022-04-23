<?php

namespace App\Events\Contracts;

use App\Models\Domain;
use Illuminate\Queue\SerializesModels;

abstract class DomainEvent
{
    use SerializesModels;

    /** @var Domain */
    public $domain;

    public function __construct(Domain $domain)
    {
        $this->domain = $domain;
    }
}
