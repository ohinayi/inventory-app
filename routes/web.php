<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Livewire\Volt\Volt;


Route::view('/', 'welcome');

Route::name('keeper.')->group(function () {
    Volt::route('/keeper/', 'keeper.dashboard')->name('dashboard')->middleware('auth');
    Volt::route('/keeper/items', 'keeper.items')->name('items')->middleware('auth');
    Volt::route('/keeper/employees', 'keeper.employees')->name('employees')->middleware('auth');
    Volt::route('/keeper/consumptions', 'keeper.consumptions')->name('consumptions')->middleware('auth');
    Volt::route('/keeper/procurements', 'keeper.procurements')->name('procurements')->middleware('auth');
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



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
