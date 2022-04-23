<?php

use App\Http\Controllers\PostController;
use App\Http\Middleware\InitializeTenancyByDomain;
use App\Http\Middleware\PreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;

Route::middleware([InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class])->group(function () {
    Route::get('/', function () {
        return tenancy();

        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    Route::resource('posts', PostController::class);
});
