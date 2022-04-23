<?php

return [
    /**
     * The list of domains hosting your central app.
     *
     * Only relevant if you're using the domain or subdomain identification middleware.
     */
    'central_domains' => [
        '127.0.0.1',
        'tenant.local',
        // 127.0.0.1 tenant.local
        // 127.0.0.1 shop1.tenant.local
    ],

    /**
     * Tenancy bootstrappers are executed when tenancy is initialized.
     * Their responsibility is making Laravel features tenant-aware.
     *
     * To configure their behavior, see the config keys below.
     */
    'bootstrappers' => [
        App\Bootstrappers\CacheTenancyBootstrapper::class,
    ],

    /**
     * Cache tenancy config. Used by CacheTenancyBootstrapper.
     *
     * This works for all Cache facade calls, cache() helper
     * calls and direct calls to injected cache stores.
     *
     * Each key in cache will have a tag applied on it. This tag is used to
     * scope the cache both when writing to it and when reading from it.
     *
     * You can clear cache selectively by specifying the tag.
     */
    'cache' => [
        'tag_base' => 'tenant', // This tag_base, followed by the tenant_id, will form a tag that will be applied on each cache call.
    ],
];
