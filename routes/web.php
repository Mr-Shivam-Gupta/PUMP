<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SuperAdminLoginController;
use App\Http\Controllers\Auth\TenantLoginController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [TenantLoginController::class, 'showLoginForm'])->name('login');
Route::any('/logout', [SuperAdminLoginController::class, 'logout'])->name('logout')->middleware('auth');




require_once __DIR__ . '/super-admin.php';
require_once __DIR__ . '/tenant.php';
