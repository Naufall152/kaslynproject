<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes for Kaslyn Web Application
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| LANDING PAGE (MAIN WEB)
|--------------------------------------------------------------------------
| - Belum login  -> welcome (main web)
| - Sudah login  -> dashboard
*/
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| USER AREA (UKM)
|--------------------------------------------------------------------------
| - Dashboard
| - Profile
| - Transaksi Keuangan (CRUD)
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard user
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile management (Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================
    // TRANSAKSI KEUANGAN (CRUD)
    // ==========================
    Route::resource('transactions', TransactionController::class)
        ->except(['show']);
});


/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
| - Admin Dashboard
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
| Login, Register, Logout, Forgot Password, dll
*/
require __DIR__ . '/auth.php';
