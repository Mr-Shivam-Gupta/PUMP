<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('user')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/list', [UserController::class, 'list'])->name('list');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    Route::get('/status/{id}', [UserController::class, 'toggleStatus'])->name('status');
    Route::get('/export', [UserController::class, 'export'])->name('export');
});
