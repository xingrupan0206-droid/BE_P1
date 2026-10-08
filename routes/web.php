<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AllergeenController;
use App\Http\Controllers\LeveringController;
use App\Http\Controllers\KlantController;
use App\Http\Controllers\MagazijnController;
use App\Http\Controllers\MagazijnmedewerkerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [AdminController::class, 'index'])
    ->name('admin.index')
    ->middleware(['auth', 'role:admin']);

Route::get('/magazijnmedewerker', [MagazijnmedewerkerController::class, 'index'])
    ->name('magazijnmedewerker.index')
    ->middleware(['auth', 'role:magazijnmedewerker,admin']);

Route::get('/magazijn', [MagazijnController::class, 'index'])
    ->name('magazijn.index')
    ->middleware(['auth', 'role:magazijnmedewerker,admin']);

Route::get('/klant', [KlantController::class, 'index'])
    ->name('klant.index')
    ->middleware(['auth', 'role:klant,admin']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/magazijn/{productId}/allergenen', [AllergeenController::class, 'show'])
    ->whereNumber('productId')
    ->middleware(['auth', 'role:magazijnmedewerker,admin'])
    ->name('magazijn.allergenen');

Route::get('/magazijn/{productId}/leveringen', [LeveringController::class, 'show'])
    ->whereNumber('productId')
    ->middleware(['auth', 'role:magazijnmedewerker,admin'])
    ->name('magazijn.leveringen');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
