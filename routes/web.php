<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\klantController;
use App\Http\Controllers\magazijnmedewerkerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [adminController::class, 'index'])
    ->name('admin.index')
    ->middleware(['auth', 'role:admin']);

Route::get('/magazijnmedewerker', [magazijnmedewerkerController::class, 'index'])
    ->name('magazijnmedewerker.index')
    ->middleware(['auth', 'role:magazijnmedewerker']);

Route::get('/klant', [klantController::class, 'index'])
    ->name('klant.index')
    ->middleware(['auth', 'role:klant']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
