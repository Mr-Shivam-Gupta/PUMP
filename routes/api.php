<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\SuperAdminAuthController;
use App\Http\Controllers\Api\EmployeeAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('super-admin/login', [SuperAdminAuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('super-admin/logout', [SuperAdminAuthController::class, 'logout']);
});


Route::post('employee/login', [EmployeeAuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('employee/logout', [EmployeeAuthController::class, 'logout']);
});
