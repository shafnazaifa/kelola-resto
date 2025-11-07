<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthViewController;
use App\Http\Controllers\System\MejaController;
use App\Http\Controllers\System\MenuController;
use App\Http\Controllers\System\PelangganController;
use App\Http\Controllers\System\Transactions\OrderController;
use App\Http\Controllers\System\Transactions\ReportController;
use App\Http\Controllers\System\Transactions\TransaksiController;
use App\Http\Controllers\View\ViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home.page');

Route::get('/login', [AuthViewController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

Route::middleware('auth')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('/dashboard')->group(function(){
        Route::get('/', [ViewController::class, 'dashboard'])->name('dashboard.page');

        Route::middleware(['auth', 'role:admin'])->group(function(){
            Route::resource('meja', MejaController::class);
        });

        Route::middleware(['auth', 'role:waiter,admin'])->group(function(){
            Route::resource('menu', MenuController::class);
            Route::get('/menus-data', [MenuController::class, 'getMenusData'])->name('menu.data');
        });

        Route::middleware(['auth', 'role:waiter'])->group(function(){
            Route::get('/order', [ViewController::class, 'order'])->name('order.index');
            Route::post('/order', [OrderController::class, 'order'])->name('order.store');
            Route::post('/order/add-to-existing', [OrderController::class, 'addToExisting'])->name('order.add-to-existing');
            Route::get('/order-list', [ViewController::class, 'order_list'])->name('order.list');
            Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
            Route::get('/pelanggan/{id}', [PelangganController::class, 'show'])->name('pelanggan.show');
            Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
            Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
        });

        Route::middleware(['auth', 'role:kasir'])->group(function(){
            Route::get('/transaction', [ViewController::class, 'transaction'])->name('transaction.index');
            Route::post('/transaction', [TransaksiController::class, 'store'])->name('transaction.store');
            Route::get('/transaction/{id}', [TransaksiController::class, 'show'])->name('transaction.show');
            Route::get('/transaction/{id}/print', [TransaksiController::class, 'printReceipt'])->name('transaction.print');
            Route::get('/transaction/{id}/receipt', [TransaksiController::class, 'receipt'])->name('transaction.receipt');
            Route::get('/transaction/{id}/receipt/download', [TransaksiController::class, 'receiptDownload'])->name('transaction.receipt.download');
            Route::get('/transaction-list', [ViewController::class, 'transaction_list'])->name('transaction.list');
            Route::get('/transaction/unpaid-orders', [TransaksiController::class, 'getUnpaidOrders'])->name('transaction.unpaid-orders');
            Route::get('/transaction/available-tables', [TransaksiController::class, 'getAvailableTables'])->name('transaction.available-tables');
        });

        Route::middleware(['auth', 'role:owner,kasir,waiter'])->group(function(){
            Route::get('/report', [ViewController::class, 'report'])->name('report.index');
            Route::get('/report/{id}', [ViewController::class, 'report_show'])->name('report.show');
            Route::get('/report/download/all', [ReportController::class, 'downloadAllReports'])->name('report.download.all');
            Route::get('/report/{id}/download', [ReportController::class, 'downloadReport'])->name('report.download');
        });


    });
});
