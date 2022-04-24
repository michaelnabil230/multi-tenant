<?php


namespace App\Features;

use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use App\Tenancy;

class TelescopeTags implements Feature
{
    public function bootstrap(Tenancy $tenancy): void
    {
        if (!class_exists(Telescope::class)) {
            return;
        }

        Telescope::tag(function (IncomingEntry $entry) {
            $tags = [];

            if (!request()->route()) {
                return $tags;
            }

            if (tenancy()->initialized) {
                $tags = [
                    'tenant:' . tenant('id'),
                ];
            }

            return $tags;
        });
    }
}
