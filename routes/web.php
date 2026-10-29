<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VinylController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VinylController::class, 'index'])->name('home');

//VOEG MIDDLEWARE (met ->group()) VOOR AUTH TOE
Route::resource('/vinyls', VinylController::class);
Route::post('/vinyls/create', [VinylController::class, 'discogSearch'])->name('vinyls.discogSearch');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
