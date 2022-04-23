<?php

namespace App\Events\Contracts;

use Illuminate\Queue\SerializesModels;
use App\Models\Domain;

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
