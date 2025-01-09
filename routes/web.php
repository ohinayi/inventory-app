<?php

use App\Http\Controllers\ConsumptionRequestController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\ConsumptionRequest;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Livewire\Volt\Volt;


Route::view('/', 'welcome');

Route::name('keeper.')->group(function () {
    Volt::route('/keeper/', 'keeper.dashboard')->name('dashboard')->middleware('auth');
    Volt::route('/keeper/items', 'keeper.items')->name('items')->middleware('auth');
    Volt::route('/keeper/users', 'keeper.users')->name('users')->middleware('auth');
    Volt::route('/keeper/consumption-requests', 'keeper.consumption-requests')->name('consumption-requests')->middleware('auth');
    Volt::route('/keeper/consumptions', 'keeper.consumptions')->name('consumptions')->middleware('auth');
    Volt::route('/keeper/procurements', 'keeper.procurements')->name('procurements')->middleware('auth');
});


Route::apiResource('consumption-requests',ConsumptionRequestController::class)->except(['index', 'show']);

Route::get('/dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');



// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route::view('profile', 'profile')
//     ->middleware(['auth'])
//     ->name('profile');

require __DIR__.'/auth.php';
