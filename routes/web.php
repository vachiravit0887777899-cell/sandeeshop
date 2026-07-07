<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\BoxOpeningController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BoxController;
use App\Http\Controllers\Admin\BoxItemController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard ผู้ใช้ทั่วไป
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ร้านค้า (public)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{box:slug}', [ShopController::class, 'show'])->name('shop.show');

// Wallet, เปิดกล่อง, คลังไอเทม (ต้อง login)
Route::middleware('auth')->group(function () {
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/topup', [WalletController::class, 'topupForm'])->name('wallet.topup.form');
    Route::post('/wallet/topup', [WalletController::class, 'topup'])->name('wallet.topup');

    Route::post('/shop/{box}/open', [BoxOpeningController::class, 'open'])->name('box.open');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/{inventory}/sell', [InventoryController::class, 'sell'])->name('inventory.sell');
});

// ส่วนแอดมิน (ต้อง login + เป็น admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('boxes', BoxController::class);
    Route::resource('boxes.items', BoxItemController::class)->shallow();
});

require __DIR__.'/auth.php';