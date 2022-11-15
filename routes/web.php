<?php

use Illuminate\Support\Facades\Route;
use Openwod\ServiceAccounts\Http\Controllers\ServiceAccountController;

Route::prefix('service_accounts')->controller(ServiceAccountController::class)->group(function () {
    Route::post('/', 'store');
    Route::get('/{name}', 'show');
});
