<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\MidtransController;

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
| - Dashboard (boleh tanpa subscription)
| - Subscription (plans)
| - Profile
| - Protected features (Reports + Transactions)
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard user (ringkasan, upsell subscribe)
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

    // ==========================
    // SUBSCRIPTION
    // ==========================
    Route::get('/subscribe', [SubscriptionController::class, 'plans'])
        ->name('subscriptions.plans');

    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])
        ->name('subscriptions.subscribe');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================
    // PROTECTED FEATURES (SUB AKTIF)
    // ==========================
    Route::middleware(['sub.active'])->group(function () {

        /*
        |--------------------------------------------------------------------------
        | REPORTING (BASIC vs PRO)
        |--------------------------------------------------------------------------
        */
        Route::prefix('reports')->name('reports.')->group(function () {

            // BASIC & PRO → laporan harian
            Route::get('/daily', [ReportController::class, 'daily'])
                ->name('daily');

            // PRO ONLY
            Route::middleware(['sub.pro'])->group(function () {
                Route::get('/profit-loss', [ReportController::class, 'profitLoss'])
                    ->name('profit_loss');

                Route::get('/yearly', [ReportController::class, 'yearly'])
                    ->name('yearly');

                Route::get('/export/csv', [ReportController::class, 'exportCsv'])
                    ->name('export_csv');

                Route::get('/export/pdf', [ReportController::class, 'exportPdf'])
                    ->name('export_pdf');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI KEUANGAN
        |--------------------------------------------------------------------------
        | - Basic: limit 50/bulan (controller)
        | - Pro  : unlimited
        */
        Route::resource('transactions', TransactionController::class)
            ->except(['show']);
    });

    // ==========================
    // MIDTRANS (PRO ONLY)
    // ==========================
    Route::middleware(['sub.active', 'sub.pro'])->group(function () {
        Route::post('/midtrans/pay', [MidtransController::class, 'pay'])
            ->name('midtrans.pay');

        Route::post('/midtrans/callback', [MidtransController::class, 'callback'])
            ->name('midtrans.callback');
    });
});


/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
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
*/
require __DIR__ . '/auth.php';
