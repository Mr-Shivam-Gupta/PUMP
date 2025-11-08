<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\TenantLoginController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByRequestData;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/


// Route::middleware([
//     'web',
//     InitializeTenancyByRequestData::class,
// ])->group(function () {

//     Route::get('/', function () {
//         return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
//     });
// });

Route::prefix('tenant')->group(function () {
    Route::post('/login', [TenantLoginController::class, 'login'])->name('tenant.login.submit');

    Route::middleware('auth:tenant')->group(function () {
        Route::get('/dashboard', [TenantLoginController::class, 'dashboard'])->name('tenant.dashboard');
    });
});
