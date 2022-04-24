<?php

use App\Http\Controllers\PostController;
use App\Http\Middleware\InitializeTenancyByDomain;
use App\Http\Middleware\PreventAccessFromCentralDomains;
use App\Models\Post;

Route::middleware([InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class])
    ->group(function () {

        Route::get('/', function () {
            return [
                'tenant' => tenant(),
                'tenancy' => tenancy()
            ];
            return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
        });

        Route::resource('posts', PostController::class);
    });
