<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SuperAdminLoginController;




Route::prefix('super-admin')->group(function () {
    Route::get('/login', [SuperAdminLoginController::class, 'showLoginForm'])->name('super_admin.login');
    Route::post('/login', [SuperAdminLoginController::class, 'login'])->name('super_admin.login.submit');

    Route::middleware('auth:super-admin')->group(function () {
        Route::get('/dashboard', [SuperAdminLoginController::class, 'dashboard'])->name('super_admin.dashboard');
    });
});
