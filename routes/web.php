<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BreedingController;
use App\Http\Controllers\CageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GrowthController;
use App\Http\Controllers\LogisticsController;
use App\Http\Controllers\RabbitController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
 * Autentikasi: hanya bisa diakses sebelum login.
 */
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

// Autentikasi: logout membutuhkan status login.
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'active'])->group(function () {
    /*
     * Beranda dan dashboard: bisa dibuka Owner maupun Karyawan.
     */
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'owner'])->name('dashboard');

    /*
     * Modul khusus Owner: akun karyawan, kandang, dan tulis data perkawinan.
     */
    Route::middleware('role:Owner')->group(function () {
        // Akun karyawan
        Route::get('/akun-karyawan', [EmployeeController::class, 'index'])->name('employees.index');
        Route::post('/akun-karyawan', [EmployeeController::class, 'store'])->name('employees.store');
        Route::put('/akun-karyawan/{employee}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::patch('/akun-karyawan/{employee}/nonaktifkan', [EmployeeController::class, 'deactivate'])->name('employees.deactivate');
        Route::patch('/akun-karyawan/{employee}/aktifkan', [EmployeeController::class, 'activate'])->name('employees.activate');

        // Data kandang
        Route::get('/data-kandang', [CageController::class, 'index'])->name('cages.index');
        Route::post('/data-kandang', [CageController::class, 'store'])->name('cages.store');
        Route::put('/data-kandang/{cage}', [CageController::class, 'update'])->name('cages.update');
        Route::delete('/data-kandang/{cage}', [CageController::class, 'destroy'])->name('cages.destroy');

        // Perkawinan: tambah, ubah, dan hapus. Daftar (/perkawinan) sengaja di luar grup Owner.
        Route::post('/perkawinan', [BreedingController::class, 'store'])->name('breeding.store');
        Route::put('/perkawinan/{breeding}', [BreedingController::class, 'update'])->name('breeding.update');
        Route::delete('/perkawinan/{breeding}', [BreedingController::class, 'destroy'])->name('breeding.destroy');
    });

    /*
     * Data kelinci: Owner dan Karyawan dapat mengelola.
     */
    Route::get('/data-kelinci', [RabbitController::class, 'index'])->name('rabbits.index');
    Route::post('/data-kelinci', [RabbitController::class, 'store'])->name('rabbits.store');
    Route::put('/data-kelinci/{rabbit}', [RabbitController::class, 'update'])->name('rabbits.update');
    Route::patch('/data-kelinci/{rabbit}/status', [RabbitController::class, 'updateStatus'])->name('rabbits.status');
    Route::delete('/data-kelinci/{rabbit}', [RabbitController::class, 'destroy'])->name('rabbits.destroy');

    /*
     * Pertumbuhan kelinci: pencatatan dan riwayat.
     */
    Route::get('/pertumbuhan', [GrowthController::class, 'index'])->name('growth.index');
    Route::post('/pertumbuhan', [GrowthController::class, 'store'])->name('growth.store');
    Route::put('/pertumbuhan/{growth}', [GrowthController::class, 'update'])->name('growth.update');
    Route::delete('/pertumbuhan/{growth}', [GrowthController::class, 'destroy'])->name('growth.destroy');

    /*
     * Perkawinan: daftar jadwal dan riwayat bisa dilihat semua role.
     */
    Route::get('/perkawinan', [BreedingController::class, 'index'])->name('breeding.index');

    /*
     * Logistik: daftar bisa dilihat semua role; perubahan hanya Karyawan.
     */
    Route::get('/logistik', [LogisticsController::class, 'index'])->name('logistics.index');
    Route::middleware('role:Karyawan')->group(function () {
        Route::post('/logistik/item', [LogisticsController::class, 'storeItem'])->name('logistics.items.store');
        Route::put('/logistik/item/{item}', [LogisticsController::class, 'updateItem'])->name('logistics.items.update');
        Route::patch('/logistik/item/{item}/nonaktifkan', [LogisticsController::class, 'deactivateItem'])->name('logistics.items.deactivate');
        Route::post('/logistik/kategori', [LogisticsController::class, 'storeCategory'])->name('logistics.categories.store');
    });

    /*
     * Laporan: Owner mendapat laporan lengkap, Karyawan laporan operasional.
     */
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
});
