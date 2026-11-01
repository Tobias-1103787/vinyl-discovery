<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VinylController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VinylController::class, 'index'])->name('home');
Route::get('/vinyls/overview', [VinylController::class, 'overview'])
    ->name('vinyls.overview')
    ->middleware('auth');
Route::resource('/vinyls', VinylController::class)
    ->middleware('auth');
Route::post('/vinyls/create', [VinylController::class, 'discogSearch'])
    ->name('vinyls.discogSearch')
    ->middleware('auth');
Route::post('/vinyls/{vinyl}/favorite', [VinylController::class, 'favorite'])
    ->name('vinyls.favorite')
    ->middleware('auth');
Route::patch('/vinyls/{vinyl}/change-status', [VinylController::class, 'changeStatus'])
    ->name('vinyls.change-status')
    ->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
