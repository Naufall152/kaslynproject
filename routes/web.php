<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\AdminPlanController;
use App\Http\Controllers\Admin\AdminAnalyticsController;


Route::get('/', function () {
    if (auth()->check())
        return redirect()->route('dashboard');
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard boleh walau belum subscribe
    Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

    // ==========================
    // SUBSCRIPTION
    // ==========================
    Route::get('/subscribe', [SubscriptionController::class, 'plans'])
        ->name('subscriptions.plans');

    // ✅ BASIC langsung aktif tanpa bayar
    Route::post('/subscribe/basic', [SubscriptionController::class, 'subscribeBasic'])
        ->name('subscriptions.basic');

    // (Opsional) Flow lama kalau masih dipakai (misal: aktivasi langsung)
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])
        ->name('subscriptions.subscribe');

    // ==========================
    // MIDTRANS (untuk beli PRO) - HARUS bisa sebelum jadi PRO
    // ==========================
    Route::post('/midtrans/pay', [MidtransController::class, 'pay'])
        ->name('midtrans.pay');

    Route::post('/midtrans/check', [MidtransController::class, 'checkStatus'])
        ->name('midtrans.check');

    Route::post('/midtrans/manual-activate', [MidtransController::class, 'manualActivate'])
        ->name('midtrans.manual_activate');

    // ==========================
    // PROFILE
    // ==========================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================
    // PROTECTED FEATURES (butuh sub aktif)
    // ==========================
    Route::middleware(['sub.active'])->group(function () {

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/daily', [ReportController::class, 'daily'])->name('daily');

            // PRO ONLY
            Route::middleware(['sub.pro'])->group(function () {
                Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit_loss');
                Route::get('/yearly', [ReportController::class, 'yearly'])->name('yearly');
                Route::get('/export/csv', [ReportController::class, 'exportCsv'])->name('export_csv');
                Route::get('/export/pdf', [ReportController::class, 'exportPdf'])->name('export_pdf');
            });
        });

        Route::resource('transactions', TransactionController::class)->except(['show']);
    });
});

// ✅ Webhook Midtrans harus di luar auth
Route::post('/midtrans/callback', [MidtransController::class, 'callback'])
    ->name('midtrans.callback')
    ->middleware('throttle:webhook'); // Rate limiting untuk webhook

// Health check untuk Azure
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'environment' => app()->environment(),
    ]);
})->name('health');

// ==========================
// ADMIN
// ==========================

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // // Users + subscription status
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');

        // // Semua transaksi (lintas UKM)
        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');

        // // Manajemen paket langganan
        Route::resource('/plans', AdminPlanController::class)->except(['show'])->names('plans');

        // Analytics
        Route::get('/analytics/visitor-stats', [AdminAnalyticsController::class, 'getVisitorStats'])
            ->name('analytics.visitor-stats');
        Route::get('/analytics/detailed', [AdminAnalyticsController::class, 'getDetailedAnalytics'])
            ->name('analytics.detailed');
    });

Route::middleware('auth')->get('/redirect-after-login', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('dashboard');
})->name('redirect.after.login');

require __DIR__ . '/auth.php';
