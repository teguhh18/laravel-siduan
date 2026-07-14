<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\KategoriPengaduanController;
use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Admin\PengaduanController as AdminPengaduanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Semua route di file ini otomatis mendapat prefix /api
| Autentikasi menggunakan Laravel Sanctum (token-based)
|
*/

// =====================================================================
// PUBLIC ROUTES (Guest)
// =====================================================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// =====================================================================
// AUTHENTICATED ROUTES
// =====================================================================
Route::middleware('auth:sanctum')->group(function () {

    // -----------------------------------------------------------------
    // Auth
    // -----------------------------------------------------------------
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // -----------------------------------------------------------------
    // Profile
    // -----------------------------------------------------------------
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

    // -----------------------------------------------------------------
    // Dashboard (role-adaptive stats)
    // -----------------------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // -----------------------------------------------------------------
    // Kategori Pengaduan
    // - GET index: semua user bisa akses (untuk dropdown form)
    // - CRUD lainnya: admin only
    // -----------------------------------------------------------------
    Route::get('/kategori-pengaduan', [KategoriPengaduanController::class, 'index']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/kategori-pengaduan', [KategoriPengaduanController::class, 'store']);
        Route::get('/kategori-pengaduan/{kategoriPengaduan}', [KategoriPengaduanController::class, 'show']);
        Route::put('/kategori-pengaduan/{kategoriPengaduan}', [KategoriPengaduanController::class, 'update']);
        Route::delete('/kategori-pengaduan/{kategoriPengaduan}', [KategoriPengaduanController::class, 'destroy']);
    });

    // -----------------------------------------------------------------
    // Pengaduan (User - own data)
    // - User hanya bisa akses pengaduan miliknya sendiri
    // - Ownership check dilakukan di controller
    // -----------------------------------------------------------------
    Route::apiResource('pengaduan', PengaduanController::class);

    // -----------------------------------------------------------------
    // Admin: Pengaduan Management
    // - List semua pengaduan, detail, dan tanggapi
    // -----------------------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/{pengaduan}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
        Route::put('/pengaduan/{pengaduan}', [AdminPengaduanController::class, 'update'])->name('pengaduan.update');
    });
});
