<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\Auth\TenantLoginController;
use App\Http\Controllers\customerController;

// Route::get('/plan', [customerController::class, 'showPlans']);   
Route::post('/tenant/plan-submit', [customerController::class, 'submit'])->name('tenant.plan.submit');


Route::get('/', [customerController::class, 'showPlans']);   



Route::get('/login', [TenantLoginController::class, 'showLoginForm'])->name('login');
Route::any('/logout', [SuperAdminLoginController::class, 'logout'])->name('logout')->middleware('auth');




require_once __DIR__ . '/super-admin.php';
require_once __DIR__ . '/tenant.php';
