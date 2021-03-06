<?php

use App\Http\Middleware\InitializeTenancyByDomain;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([InitializeTenancyByDomain::class])->group(function () {
    Route::get('/tenant', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});