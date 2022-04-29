<?php

namespace App;

use Illuminate\Cache\CacheManager as BaseCacheManager;

class CacheManager extends BaseCacheManager
{
    /**
     * Add tags and forward the call to the inner cache store.
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $prefix = config('cache.prefix') . '_' . tenant()->getTenantKey();
        dd($prefix);
        config([
            'cache.prefix' => $prefix,
        ]);

        // dd($prefix);
        // if ($method === 'tags') {
        //     $count = count($parameters);

        //     if ($count !== 1) {
        //         throw new \Exception("Method tags() takes exactly 1 argument. $count passed.");
        //     }

        //     $names = $parameters[0];
        //     $names = (array) $names; // cache()->tags('foo') https://laravel.com/docs/5.7/cache#removing-tagged-cache-items

        //     // dd(array_merge($tags, $names));
        //     return $this->store()->tags(array_merge($tags, $names));
        // }

        return $this->store()->$method(...$parameters);
    }
}
