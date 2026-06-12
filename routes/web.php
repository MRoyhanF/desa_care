<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Report Routes — accessible by all authenticated users
    Route::get('/pengaduan', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('report.index');
    Route::get('/pengaduan/buat', [\App\Http\Controllers\Admin\ReportController::class, 'create'])->name('report.create');
    Route::post('/pengaduan/simpan', [\App\Http\Controllers\Admin\ReportController::class, 'store'])->name('report.store');
    Route::get('/pengaduan/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'show'])->name('report.show');
    Route::put('/pengaduan/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'update'])->name('report.update');
    Route::delete('/pengaduan/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'destroy'])->name('report.destroy');

    // Admin-only Routes
    Route::middleware('admin')->group(function () {
        // User Management
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Category Management
        Route::get('/kategori', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('category.index');
        Route::post('/kategori', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('category.store');
        Route::put('/kategori/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('category.update');
        Route::patch('/kategori/{category}/toggle', [\App\Http\Controllers\Admin\CategoryController::class, 'toggle'])->name('category.toggle');
        Route::delete('/kategori/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('category.destroy');
    });
});

require __DIR__.'/auth.php';
