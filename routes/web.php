<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ("/") to dashboard (which shows the Watchlist)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// =========================
// Authenticated (Breeze)
// =========================
Route::middleware(['auth'])->group(function () {
    // Dashboard = Watchlist Page
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/cards/create', [CardController::class, 'create'])->name('cards.create');
    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');


    // Watchlist routes (if needed later)
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/cards/refresh', [App\Http\Controllers\CardController::class, 'refresh'])
        ->name('cards.refresh');

    // Card Tracker
    Route::get('/cards', [CardController::class, 'index'])->name('cards.index');
    Route::get('/cards/{card}', [CardController::class, 'show'])->name('cards.show');

    // Breeze Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
