<?php

use App\Http\Controllers\CommandController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DeviceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DeviceController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::prefix('devices')->name('devices.')->group(function () {
    Route::get('/create', [DeviceController::class, 'create'])->name('create');
    Route::post('/', [DeviceController::class, 'store'])->name('store');
    Route::get('/{device}', [DeviceController::class, 'show'])->name('show');
    Route::post('/device/shutdown', [DeviceController::class, 'shutdown'])->name('shutdown');
})->middleware(['auth', 'verified']);

/* Route::post('/{device}/command', [CommandController::class, 'store'])->name('command.store'); */


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
