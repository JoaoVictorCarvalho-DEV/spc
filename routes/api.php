<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DeviceCommandController;
use App\Http\Controllers\Api\DeviceReadingController;

Route::prefix('devices/{esp_identifier}')
    ->middleware('device.auth')
    ->group(function () {

        // ESP envia leitura de energia
        Route::post('/readings', [DeviceReadingController::class, 'store']);

        // ESP consulta se há comando pendente (polling)
        Route::get('/commands', [DeviceCommandController::class, 'next']);
    });

Route::get(
    '/devices/{esp_identifier}/test-reading',
    [DeviceReadingController::class, 'test']
)->middleware('device.auth');

// ESP confirma que executou o comando
Route::post(
    '/commands/{command}/confirm',
    [DeviceCommandController::class, 'confirm']
)->middleware('device.auth');
