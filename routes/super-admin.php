<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\SuperAdmin\SuperAdminOwnerController;
use App\Http\Controllers\SuperAdmin\SuperAdminPlanController;
use App\Http\Controllers\SuperAdmin\SuperAdminTenantController;

Route::prefix('super-admin')->group(function () {
    Route::get('/login', [SuperAdminLoginController::class, 'showLoginForm'])->name('super_admin.login');
    Route::post('/login', [SuperAdminLoginController::class, 'login'])->name('super_admin.login.submit');

    Route::middleware('auth:super-admin')->name('super-admin.')->group(function () {
        Route::get('/dashboard', [SuperAdminLoginController::class, 'dashboard'])->name('dashboard');

        Route::resource('tenants', SuperAdminTenantController::class)->except(['show']);
        Route::get('tenants/status/{id}', [SuperAdminTenantController::class, 'toggleStatus'])->name('tenant.status');
        Route::get('tenants/list', [SuperAdminTenantController::class, 'listTenants'])->name('tenants.list');

        Route::resource('owners', SuperAdminOwnerController::class)->except(['show']);
        Route::get('owners/status/{id}', [SuperAdminOwnerController::class, 'toggleStatus'])->name('owner.status');
    });
});
Route::middleware('auth:super-admin')->name('super-admin.')->group(function () {
    Route::get('plan', [SuperAdminPlanController::class, 'index'])->name('plan.index');
    Route::resource('plans', SuperAdminPlanController::class)->except(['create', 'show']);
});
